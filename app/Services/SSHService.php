<?php

namespace App\Services;

use App\Models\Device;
use Exception;

class SSHService
{
    private $connection = null;
    private $device = null;

    public function connect(Device $device): bool
    {
        if (!$device->hasSshCredentials()) {
            throw new Exception('SSH credentials not configured for this device');
        }

        $this->device = $device;
        $host = $device->getSshHostAddress();
        $port = $device->ssh_port ?? 22;

        $this->connection = @ssh2_connect($host, $port);

        if (!$this->connection) {
            $device->update([
                'ssh_connection_status' => 'Connection failed: Unable to connect to ' . $host . ':' . $port,
                'last_ssh_connection' => now()
            ]);
            throw new Exception('Unable to connect to ' . $host . ':' . $port);
        }

        // Authenticate
        $authenticated = false;

        if ($device->ssh_private_key) {
            // Try key-based authentication
            $keyFile = tempnam(sys_get_temp_dir(), 'ssh_key_');
            file_put_contents($keyFile, $device->ssh_private_key);
            chmod($keyFile, 0600);

            $authenticated = @ssh2_auth_pubkey_file(
                $this->connection,
                $device->ssh_username,
                $keyFile . '.pub',
                $keyFile,
                $device->ssh_password ?? ''
            );

            unlink($keyFile);
        }

        if (!$authenticated && $device->ssh_password) {
            // Try password authentication
            $authenticated = @ssh2_auth_password(
                $this->connection,
                $device->ssh_username,
                $device->ssh_password
            );
        }

        if (!$authenticated) {
            $device->update([
                'ssh_connection_status' => 'Authentication failed',
                'last_ssh_connection' => now()
            ]);
            throw new Exception('SSH authentication failed');
        }

        $device->update([
            'ssh_connection_status' => 'Connected successfully',
            'last_ssh_connection' => now()
        ]);

        return true;
    }

    public function executeCommand(string $command): array
    {
        if (!$this->connection) {
            throw new Exception('Not connected to any device');
        }

        $stream = ssh2_exec($this->connection, $command);

        if (!$stream) {
            throw new Exception('Failed to execute command');
        }

        stream_set_blocking($stream, true);
        $output = stream_get_contents($stream);

        $errorStream = ssh2_fetch_stream($stream, SSH2_STREAM_STDERR);
        stream_set_blocking($errorStream, true);
        $error = stream_get_contents($errorStream);

        fclose($stream);
        fclose($errorStream);

        return [
            'success' => empty($error),
            'output' => $output,
            'error' => $error
        ];
    }

    public function testConnection(Device $device): array
    {
        try {
            $this->connect($device);
            $result = $this->executeCommand('show version 2>/dev/null || uname -a');
            $this->disconnect();

            return [
                'success' => true,
                'message' => 'Connection successful',
                'output' => $result['output']
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'output' => null
            ];
        }
    }

    public function pushNetFlowConfig(Device $device, string $collectorIp, int $collectorPort): array
    {
        try {
            $this->connect($device);

            $commands = $this->getNetFlowCommands($device->type, $collectorIp, $collectorPort);

            $results = [];
            foreach ($commands as $command) {
                $results[] = $this->executeCommand($command);
            }

            $this->disconnect();

            return [
                'success' => true,
                'message' => 'NetFlow configuration pushed successfully',
                'results' => $results
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage(),
                'results' => []
            ];
        }
    }

    private function getNetFlowCommands(string $deviceType, string $collectorIp, int $collectorPort): array
    {
        switch ($deviceType) {
            case 'cisco_router':
            case 'router':
                return [
                    'configure terminal',
                    'flow exporter NETFLOW-EXPORT',
                    "destination {$collectorIp}",
                    "transport udp {$collectorPort}",
                    'exit',
                    'flow monitor NETFLOW-MONITOR',
                    'exporter NETFLOW-EXPORT',
                    'record netflow ipv4 original-input',
                    'exit',
                    'end',
                    'write memory'
                ];

            case 'fortigate':
            case 'firewall':
                return [
                    'config system netflow',
                    "set collector-ip {$collectorIp}",
                    "set collector-port {$collectorPort}",
                    'set source-ip auto',
                    'end'
                ];

            case 'palo_alto':
                return [
                    'configure',
                    "set deviceconfig system netflow exporter-1 server {$collectorIp}",
                    "set deviceconfig system netflow exporter-1 port {$collectorPort}",
                    'commit',
                    'exit'
                ];

            case 'checkpoint':
                return [
                    'set flow-export on',
                    "set flow-export destination {$collectorIp}:{$collectorPort}",
                    'save config'
                ];

            default:
                return [];
        }
    }

    public function getNetFlowConfigTemplate(string $deviceType, string $collectorIp, int $collectorPort): string
    {
        $commands = $this->getNetFlowCommands($deviceType, $collectorIp, $collectorPort);
        return implode("\n", $commands);
    }

    public function disconnect(): void
    {
        if ($this->connection) {
            // SSH2 connections don't have explicit close, they close on unset
            $this->connection = null;
            $this->device = null;
        }
    }

    public function __destruct()
    {
        $this->disconnect();
    }
}
