<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\NetflowCollectorService;

class NetflowListener extends Command
{
    protected $signature = 'netflow:listen {port=9996}';
    protected $description = 'Listen for NetFlow packets on UDP port';

    private NetflowCollectorService $collector;

    public function __construct(NetflowCollectorService $collector)
    {
        parent::__construct();
        $this->collector = $collector;
    }

    public function handle()
    {
        $port = $this->argument('port');
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
        $this->info('Waiting for NetFlow packets...');

        while (true) {
            $from = '';
            $fromPort = 0;
            $buffer = '';

            $bytes = @socket_recvfrom($socket, $buffer, 8192, 0, $from, $fromPort);

            if ($bytes === false) {
                continue;
            }

            $this->info("Received {$bytes} bytes from {$from}:{$fromPort}");

            try {
                $flowData = $this->parseNetflowPacket($buffer, $from);
                
                if ($flowData) {
                    $this->collector->processNetflowPacket($flowData);
                    $this->info("✓ Processed flow data from {$from}");
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
        // This is a simplified NetFlow v5 parser
        // For production, use a proper NetFlow library
        
        $header = unpack('nversion/ncount/Nuptime/Nunix_secs/Nunix_nsecs/Nsequence/Cengine_type/Cengine_id', substr($buffer, 0, 24));

        if ($header['version'] !== 5) {
            $this->warn('Only NetFlow v5 is supported in this example');
            return null;
        }

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
            'flows' => $flows,
        ];
    }
}