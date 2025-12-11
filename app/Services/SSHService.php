<?php

namespace App\Services;

use App\Models\Device;
use Exception;

class SSHService
{
    private $connection = null;
    private $device = null;
    private $usePhpseclib = false;
    private $phpseclibConnection = null;

    public function __construct()
    {
        // Check if ssh2 extension is available, otherwise we'll use simulation mode
        $this->usePhpseclib = !function_exists('ssh2_connect');
    }

    public function connect(Device $device): bool
    {
        if (!$device->hasSshCredentials()) {
            throw new Exception('SSH credentials not configured for this device');
        }

        $this->device = $device;
        $host = $device->getSshHostAddress();
        $port = $device->ssh_port ?? 22;

        // Check if SSH2 extension is available
        if (!function_exists('ssh2_connect')) {
            // Log warning but don't fail - we can still show config templates
            $device->update([
                'ssh_connection_status' => 'SSH2 extension not available - manual configuration required',
                'last_ssh_connection' => now()
            ]);
            throw new Exception('SSH2 PHP extension is not installed. Please configure the device manually using the provided template.');
        }

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

            // For key auth, we need both private and public key
            // Generate public key path - some setups require it
            $pubKeyFile = $keyFile . '.pub';

            // Try to extract public key from private key if possible
            if (function_exists('openssl_pkey_get_private')) {
                $privateKey = openssl_pkey_get_private($device->ssh_private_key);
                if ($privateKey) {
                    $keyDetails = openssl_pkey_get_details($privateKey);
                    if (isset($keyDetails['key'])) {
                        file_put_contents($pubKeyFile, $keyDetails['key']);
                    }
                }
            }

            // Try authentication with key
            if (file_exists($pubKeyFile)) {
                $authenticated = @ssh2_auth_pubkey_file(
                    $this->connection,
                    $device->ssh_username,
                    $pubKeyFile,
                    $keyFile,
                    $device->ssh_password ?? ''
                );
                @unlink($pubKeyFile);
            }

            @unlink($keyFile);
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

            if (empty($commands)) {
                return [
                    'success' => false,
                    'message' => 'No NetFlow configuration commands available for device type: ' . $device->type,
                    'results' => []
                ];
            }

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
                    'export-protocol netflow-v9',
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

            case 'switch':
                return [
                    'configure terminal',
                    'flow exporter NETFLOW-EXPORT',
                    "destination {$collectorIp}",
                    "transport udp {$collectorPort}",
                    'exit',
                    'end',
                    'write memory'
                ];

            default:
                return [];
        }
    }

    public function getNetFlowConfigTemplate(string $deviceType, string $collectorIp, int $collectorPort): string
    {
        $commands = $this->getNetFlowCommands($deviceType, $collectorIp, $collectorPort);

        if (empty($commands)) {
            return "# No template available for device type: {$deviceType}\n# Please consult your device documentation for NetFlow configuration.";
        }

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

    /**
     * Check if SSH functionality is available
     */
    public static function isAvailable(): bool
    {
        return function_exists('ssh2_connect');
    }
}
