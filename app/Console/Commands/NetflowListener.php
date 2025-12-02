<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NetflowCollectorService;

class NetflowListener extends Command
{
    protected $signature = 'netflow:listen {port?}';
    protected $description = 'Listen for NetFlow packets on UDP port';

    private NetflowCollectorService $collector;
    private array $v9Templates = [];

    public function __construct(NetflowCollectorService $collector)
    {
        parent::__construct();
        $this->collector = $collector;
    }

    public function handle()
    {
        $port = $this->argument('port') ?? config('netflow.port', 9995);
        $this->info("Starting NetFlow listener on UDP port {$port}...");

        // Create UDP socket
        $socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);

        if (!$socket) {
            $this->error('Failed to create socket');
            return 1;
        }

        socket_set_option($socket, SOL_SOCKET, SO_REUSEADDR, 1);

        if (!socket_bind($socket, '0.0.0.0', $port)) {
            $this->error('Failed to bind socket to port ' . $port);
            return 1;
        }

        $this->info('✓ NetFlow listener started successfully');
        $this->info('Supported versions: NetFlow v5, v9, IPFIX');
        $this->info('Waiting for NetFlow packets...');

        while (true) {
            $from = '';
            $fromPort = 0;
            $buffer = '';

            $bytes = @socket_recvfrom($socket, $buffer, 65535, 0, $from, $fromPort);

            if ($bytes === false) {
                continue;
            }

            $this->info("Received {$bytes} bytes from {$from}:{$fromPort}");

            try {
                $flowData = $this->parseNetflowPacket($buffer, $from);

                if ($flowData && !empty($flowData['flows'])) {
                    $this->collector->processNetflowPacket($flowData);
                    $this->info("✓ Processed " . count($flowData['flows']) . " flow(s) from {$from}");
                }
            } catch (\Exception $e) {
                $this->error('Error processing packet: ' . $e->getMessage());
            }
        }

        socket_close($socket);
        return 0;
    }

    private function parseNetflowPacket(string $buffer, string $exporterIp): ?array
    {
        if (strlen($buffer) < 4) {
            return null;
        }

        $version = unpack('n', substr($buffer, 0, 2))[1];

        $this->info("Detected NetFlow version: {$version}");

        return match($version) {
            5 => $this->parseNetflowV5($buffer, $exporterIp),
            9 => $this->parseNetflowV9($buffer, $exporterIp),
            10 => $this->parseIPFIX($buffer, $exporterIp), // IPFIX
            default => null
        };
    }

    private function parseNetflowV5(string $buffer, string $exporterIp): ?array
    {
        $header = unpack('nversion/ncount/Nuptime/Nunix_secs/Nunix_nsecs/Nsequence/Cengine_type/Cengine_id', substr($buffer, 0, 24));

        $flows = [];
        $offset = 24;
        $flowRecordSize = 48;

        for ($i = 0; $i < $header['count']; $i++) {
            if ($offset + $flowRecordSize > strlen($buffer)) {
                break;
            }

            $record = substr($buffer, $offset, $flowRecordSize);
            $flow = unpack(
                'Nsrc_ip/Ndst_ip/Nnext_hop/ninput/noutput/' .
                'Npackets/Nbytes/Nfirst/Nlast/' .
                'nsrc_port/ndst_port/Cpad1/Ctcp_flags/' .
                'Cprotocol/Ctos/nsrc_as/ndst_as/Csrc_mask/Cdst_mask',
                $record
            );

            $flows[] = [
                'src_ip' => long2ip($flow['src_ip']),
                'dst_ip' => long2ip($flow['dst_ip']),
                'src_port' => $flow['src_port'],
                'dst_port' => $flow['dst_port'],
                'protocol' => $flow['protocol'],
                'bytes' => $flow['bytes'],
                'packets' => $flow['packets'],
                'first_switched' => now(),
                'last_switched' => now(),
            ];

            $offset += $flowRecordSize;
        }

        return [
            'exporter_ip' => $exporterIp,
            'version' => 5,
            'flows' => $flows,
        ];
    }

    private function parseNetflowV9(string $buffer, string $exporterIp): ?array
    {
        if (strlen($buffer) < 20) {
            return null;
        }

        $header = unpack('nversion/ncount/Nuptime/Nunix_secs/Nsequence/Nsource_id', substr($buffer, 0, 20));

        $this->info("NetFlow v9 Header: count={$header['count']}, seq={$header['sequence']}, source_id={$header['source_id']}");

        $flows = [];
        $offset = 20;
        $flowsetCount = $header['count'];

        while ($offset < strlen($buffer) - 4) {
            if ($offset + 4 > strlen($buffer)) {
                break;
            }

            $flowsetHeader = unpack('nflowset_id/nlength', substr($buffer, $offset, 4));
            $flowsetId = $flowsetHeader['flowset_id'];
            $flowsetLength = $flowsetHeader['length'];

            if ($flowsetLength < 4 || $offset + $flowsetLength > strlen($buffer)) {
                break;
            }

            $this->info("FlowSet ID: {$flowsetId}, Length: {$flowsetLength}");

            if ($flowsetId == 0) {
                // Template FlowSet
                $this->parseV9Template($buffer, $offset, $flowsetLength, $exporterIp, $header['source_id']);
            } elseif ($flowsetId == 1) {
                // Options Template FlowSet (skip for now)
                $this->info("Options template received, skipping");
            } elseif ($flowsetId >= 256) {
                // Data FlowSet
                $templateKey = "{$exporterIp}:{$header['source_id']}:{$flowsetId}";
                if (isset($this->v9Templates[$templateKey])) {
                    $dataFlows = $this->parseV9DataFlowSet($buffer, $offset, $flowsetLength, $templateKey);
                    $flows = array_merge($flows, $dataFlows);
                } else {
                    $this->warn("No template found for FlowSet ID {$flowsetId}");
                }
            }

            $offset += $flowsetLength;
            // Align to 4-byte boundary
            $offset = ($offset + 3) & ~3;
        }

        return [
            'exporter_ip' => $exporterIp,
            'version' => 9,
            'flows' => $flows,
        ];
    }

    private function parseV9Template(string $buffer, int $baseOffset, int $length, string $exporterIp, int $sourceId): void
    {
        $offset = $baseOffset + 4; // Skip flowset header
        $endOffset = $baseOffset + $length;

        while ($offset + 4 <= $endOffset) {
            $templateHeader = unpack('ntemplate_id/nfield_count', substr($buffer, $offset, 4));
            $templateId = $templateHeader['template_id'];
            $fieldCount = $templateHeader['field_count'];
            $offset += 4;

            $fields = [];
            $recordLength = 0;

            for ($i = 0; $i < $fieldCount && $offset + 4 <= $endOffset; $i++) {
                $field = unpack('ntype/nlength', substr($buffer, $offset, 4));
                $fields[] = [
                    'type' => $field['type'],
                    'length' => $field['length']
                ];
                $recordLength += $field['length'];
                $offset += 4;
            }

            $templateKey = "{$exporterIp}:{$sourceId}:{$templateId}";
            $this->v9Templates[$templateKey] = [
                'fields' => $fields,
                'record_length' => $recordLength
            ];

            $this->info("Stored template {$templateKey} with {$fieldCount} fields, record_length={$recordLength}");
        }
    }

    private function parseV9DataFlowSet(string $buffer, int $baseOffset, int $length, string $templateKey): array
    {
        $template = $this->v9Templates[$templateKey];
        $offset = $baseOffset + 4; // Skip flowset header
        $endOffset = $baseOffset + $length;
        $recordLength = $template['record_length'];

        $flows = [];

        while ($offset + $recordLength <= $endOffset) {
            $flowData = $this->parseV9FlowRecord($buffer, $offset, $template['fields']);
            if ($flowData) {
                $flows[] = $flowData;
            }
            $offset += $recordLength;
        }

        return $flows;
    }

    private function parseV9FlowRecord(string $buffer, int $offset, array $fields): ?array
    {
        $flow = [
            'src_ip' => '0.0.0.0',
            'dst_ip' => '0.0.0.0',
            'src_port' => 0,
            'dst_port' => 0,
            'protocol' => 0,
            'bytes' => 0,
            'packets' => 0,
            'first_switched' => now(),
            'last_switched' => now(),
        ];

        $fieldOffset = $offset;

        foreach ($fields as $field) {
            $type = $field['type'];
            $length = $field['length'];
            $data = substr($buffer, $fieldOffset, $length);

            switch ($type) {
                case 8: // IPV4_SRC_ADDR
                    if ($length == 4) {
                        $flow['src_ip'] = long2ip(unpack('N', $data)[1]);
                    }
                    break;
                case 12: // IPV4_DST_ADDR
                    if ($length == 4) {
                        $flow['dst_ip'] = long2ip(unpack('N', $data)[1]);
                    }
                    break;
                case 7: // L4_SRC_PORT
                    if ($length == 2) {
                        $flow['src_port'] = unpack('n', $data)[1];
                    }
                    break;
                case 11: // L4_DST_PORT
                    if ($length == 2) {
                        $flow['dst_port'] = unpack('n', $data)[1];
                    }
                    break;
                case 4: // PROTOCOL
                    if ($length == 1) {
                        $flow['protocol'] = unpack('C', $data)[1];
                    }
                    break;
                case 1: // IN_BYTES
                    $flow['bytes'] = $this->unpackUnsigned($data, $length);
                    break;
                case 2: // IN_PKTS
                    $flow['packets'] = $this->unpackUnsigned($data, $length);
                    break;
                case 23: // OUT_BYTES
                    $flow['bytes'] += $this->unpackUnsigned($data, $length);
                    break;
                case 24: // OUT_PKTS
                    $flow['packets'] += $this->unpackUnsigned($data, $length);
                    break;
            }

            $fieldOffset += $length;
        }

        return $flow;
    }

    private function parseIPFIX(string $buffer, string $exporterIp): ?array
    {
        // IPFIX is similar to NetFlow v9 but with some differences
        // For now, treat it similarly to v9
        if (strlen($buffer) < 16) {
            return null;
        }

        $header = unpack('nversion/nlength/Nexport_time/Nsequence/Nobservation_domain', substr($buffer, 0, 16));

        $this->info("IPFIX Header: length={$header['length']}, seq={$header['sequence']}");

        // IPFIX parsing is similar to v9, reuse the logic
        $flows = [];
        $offset = 16;

        while ($offset < strlen($buffer) - 4) {
            $setHeader = unpack('nset_id/nlength', substr($buffer, $offset, 4));
            $setId = $setHeader['set_id'];
            $setLength = $setHeader['length'];

            if ($setLength < 4 || $offset + $setLength > strlen($buffer)) {
                break;
            }

            if ($setId == 2) {
                // Template Set
                $this->parseIPFIXTemplate($buffer, $offset, $setLength, $exporterIp, $header['observation_domain']);
            } elseif ($setId >= 256) {
                // Data Set
                $templateKey = "{$exporterIp}:{$header['observation_domain']}:{$setId}";
                if (isset($this->v9Templates[$templateKey])) {
                    $dataFlows = $this->parseV9DataFlowSet($buffer, $offset, $setLength, $templateKey);
                    $flows = array_merge($flows, $dataFlows);
                }
            }

            $offset += $setLength;
        }

        return [
            'exporter_ip' => $exporterIp,
            'version' => 10,
            'flows' => $flows,
        ];
    }

    private function parseIPFIXTemplate(string $buffer, int $baseOffset, int $length, string $exporterIp, int $domainId): void
    {
        // IPFIX templates are similar to v9 templates
        $this->parseV9Template($buffer, $baseOffset, $length, $exporterIp, $domainId);
    }

    private function unpackUnsigned(string $data, int $length): int
    {
        return match($length) {
            1 => unpack('C', $data)[1],
            2 => unpack('n', $data)[1],
            4 => unpack('N', $data)[1],
            8 => unpack('J', $data)[1],
            default => 0
        };
    }
}
