<?php

namespace App\Services;

use App\Models\Device;
use App\Models\DeviceInterface;
use Exception;
use Illuminate\Support\Facades\Log;

class SNMPService
{
    // Standard SNMP OIDs
    private const OID_SYS_DESCR = '1.3.6.1.2.1.1.1.0';
    private const OID_SYS_OBJECT_ID = '1.3.6.1.2.1.1.2.0';
    private const OID_SYS_UPTIME = '1.3.6.1.2.1.1.3.0';
    private const OID_SYS_CONTACT = '1.3.6.1.2.1.1.4.0';
    private const OID_SYS_NAME = '1.3.6.1.2.1.1.5.0';
    private const OID_SYS_LOCATION = '1.3.6.1.2.1.1.6.0';

    // Interface OIDs
    private const OID_IF_NUMBER = '1.3.6.1.2.1.2.1.0';
    private const OID_IF_TABLE = '1.3.6.1.2.1.2.2';
    private const OID_IF_INDEX = '1.3.6.1.2.1.2.2.1.1';
    private const OID_IF_DESCR = '1.3.6.1.2.1.2.2.1.2';
    private const OID_IF_TYPE = '1.3.6.1.2.1.2.2.1.3';
    private const OID_IF_MTU = '1.3.6.1.2.1.2.2.1.4';
    private const OID_IF_SPEED = '1.3.6.1.2.1.2.2.1.5';
    private const OID_IF_ADMIN_STATUS = '1.3.6.1.2.1.2.2.1.7';
    private const OID_IF_OPER_STATUS = '1.3.6.1.2.1.2.2.1.8';
    private const OID_IF_IN_OCTETS = '1.3.6.1.2.1.2.2.1.10';
    private const OID_IF_IN_ERRORS = '1.3.6.1.2.1.2.2.1.14';
    private const OID_IF_OUT_OCTETS = '1.3.6.1.2.1.2.2.1.16';
    private const OID_IF_OUT_ERRORS = '1.3.6.1.2.1.2.2.1.20';

    // High Capacity Counters (64-bit) for high-speed interfaces
    private const OID_IF_HC_IN_OCTETS = '1.3.6.1.2.1.31.1.1.1.6';
    private const OID_IF_HC_OUT_OCTETS = '1.3.6.1.2.1.31.1.1.1.10';
    private const OID_IF_HIGH_SPEED = '1.3.6.1.2.1.31.1.1.1.15';
    private const OID_IF_ALIAS = '1.3.6.1.2.1.31.1.1.1.18';

    private int $timeout = 1000000; // 1 second in microseconds
    private int $retries = 2;

    /**
     * Check if SNMP extension is available
     */
    public static function isAvailable(): bool
    {
        return extension_loaded('snmp');
    }

    /**
     * Test SNMP connection to a device
     */
    public function testConnection(Device $device): array
    {
        if (!self::isAvailable()) {
            return [
                'success' => false,
                'message' => 'PHP SNMP extension is not installed. SNMP polling is not available.',
                'data' => null
            ];
        }

        if (!$device->hasSnmpCredentials()) {
            return [
                'success' => false,
                'message' => 'SNMP credentials are not configured for this device.',
                'data' => null
            ];
        }

        try {
            $session = $this->createSession($device);
            $sysName = $this->snmpGet($session, self::OID_SYS_NAME);
            $sysDescr = $this->snmpGet($session, self::OID_SYS_DESCR);

            $device->update([
                'snmp_connection_status' => 'Connected successfully',
                'last_snmp_poll' => now()
            ]);

            return [
                'success' => true,
                'message' => 'SNMP connection successful',
                'data' => [
                    'sysName' => $sysName,
                    'sysDescr' => $sysDescr
                ]
            ];
        } catch (Exception $e) {
            $device->update([
                'snmp_connection_status' => 'Connection failed: ' . $e->getMessage(),
                'last_snmp_poll' => now()
            ]);

            return [
                'success' => false,
                'message' => $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Poll device for system information
     */
    public function pollSystemInfo(Device $device): array
    {
        if (!self::isAvailable()) {
            throw new Exception('PHP SNMP extension is not available');
        }

        if (!$device->hasSnmpCredentials()) {
            throw new Exception('SNMP credentials not configured');
        }

        try {
            $session = $this->createSession($device);

            $sysInfo = [
                'snmp_sys_name' => $this->snmpGet($session, self::OID_SYS_NAME),
                'snmp_sys_descr' => $this->snmpGet($session, self::OID_SYS_DESCR),
                'snmp_sys_contact' => $this->snmpGet($session, self::OID_SYS_CONTACT),
                'snmp_sys_location' => $this->snmpGet($session, self::OID_SYS_LOCATION),
                'snmp_sys_uptime' => $this->parseUptime($this->snmpGet($session, self::OID_SYS_UPTIME)),
                'last_snmp_poll' => now(),
                'snmp_connection_status' => 'Poll successful'
            ];

            $device->update($sysInfo);

            return [
                'success' => true,
                'message' => 'System info polled successfully',
                'data' => $sysInfo
            ];
        } catch (Exception $e) {
            $device->update([
                'snmp_connection_status' => 'Poll failed: ' . $e->getMessage(),
                'last_snmp_poll' => now()
            ]);

            throw $e;
        }
    }

    /**
     * Poll device interfaces
     */
    public function pollInterfaces(Device $device): array
    {
        if (!self::isAvailable()) {
            throw new Exception('PHP SNMP extension is not available');
        }

        if (!$device->hasSnmpCredentials()) {
            throw new Exception('SNMP credentials not configured');
        }

        try {
            $session = $this->createSession($device);

            // Get interface count
            $ifNumber = (int) $this->snmpGet($session, self::OID_IF_NUMBER);

            // Walk interface table
            $ifIndexes = $this->snmpWalk($session, self::OID_IF_INDEX);
            $ifDescrs = $this->snmpWalk($session, self::OID_IF_DESCR);
            $ifTypes = $this->snmpWalk($session, self::OID_IF_TYPE);
            $ifSpeeds = $this->snmpWalk($session, self::OID_IF_SPEED);
            $ifAdminStatus = $this->snmpWalk($session, self::OID_IF_ADMIN_STATUS);
            $ifOperStatus = $this->snmpWalk($session, self::OID_IF_OPER_STATUS);
            $ifInOctets = $this->snmpWalk($session, self::OID_IF_IN_OCTETS);
            $ifOutOctets = $this->snmpWalk($session, self::OID_IF_OUT_OCTETS);
            $ifAliases = $this->snmpWalk($session, self::OID_IF_ALIAS);
            $ifHighSpeeds = $this->snmpWalk($session, self::OID_IF_HIGH_SPEED);

            $interfaces = [];
            foreach ($ifIndexes as $key => $ifIndex) {
                $idx = (int) $ifIndex;

                $interface = [
                    'device_id' => $device->id,
                    'if_index' => $idx,
                    'name' => $ifDescrs[$key] ?? "Interface {$idx}",
                    'description' => $ifAliases[$key] ?? null,
                    'type' => $this->getInterfaceTypeName((int)($ifTypes[$key] ?? 0)),
                    'speed' => $this->calculateSpeed($ifSpeeds[$key] ?? 0, $ifHighSpeeds[$key] ?? 0),
                    'admin_status' => ($ifAdminStatus[$key] ?? 2) == 1 ? 'up' : 'down',
                    'oper_status' => ($ifOperStatus[$key] ?? 2) == 1 ? 'up' : 'down',
                    'in_octets' => (int)($ifInOctets[$key] ?? 0),
                    'out_octets' => (int)($ifOutOctets[$key] ?? 0),
                    'last_polled' => now()
                ];

                // Update or create interface
                DeviceInterface::updateOrCreate(
                    ['device_id' => $device->id, 'if_index' => $idx],
                    $interface
                );

                $interfaces[] = $interface;
            }

            // Update device interface count
            $device->update([
                'interface_count' => count($interfaces),
                'last_snmp_poll' => now(),
                'snmp_connection_status' => 'Interfaces polled successfully'
            ]);

            return [
                'success' => true,
                'message' => 'Polled ' . count($interfaces) . ' interfaces',
                'data' => $interfaces
            ];
        } catch (Exception $e) {
            $device->update([
                'snmp_connection_status' => 'Interface poll failed: ' . $e->getMessage(),
                'last_snmp_poll' => now()
            ]);

            throw $e;
        }
    }

    /**
     * Full poll - system info and interfaces
     */
    public function pollDevice(Device $device): array
    {
        $results = [
            'system' => null,
            'interfaces' => null,
            'success' => false,
            'message' => ''
        ];

        try {
            $results['system'] = $this->pollSystemInfo($device);
            $results['interfaces'] = $this->pollInterfaces($device);
            $results['success'] = true;
            $results['message'] = 'Device polled successfully';
        } catch (Exception $e) {
            $results['success'] = false;
            $results['message'] = $e->getMessage();
            Log::error("SNMP poll failed for device {$device->name}: " . $e->getMessage());
        }

        return $results;
    }

    /**
     * Create SNMP session based on version
     */
    private function createSession(Device $device)
    {
        $host = $device->ip_address;
        $port = $device->snmp_port ?? 161;
        $target = "{$host}:{$port}";

        snmp_set_oid_numeric_print(true);
        snmp_set_quick_print(true);
        snmp_set_enum_print(true);

        switch ($device->snmp_version) {
            case 'v1':
                return [
                    'version' => SNMP::VERSION_1,
                    'host' => $target,
                    'community' => $device->snmp_community
                ];

            case 'v2c':
                return [
                    'version' => SNMP::VERSION_2c,
                    'host' => $target,
                    'community' => $device->snmp_community
                ];

            case 'v3':
                return [
                    'version' => SNMP::VERSION_3,
                    'host' => $target,
                    'username' => $device->snmp_username,
                    'security_level' => $device->snmp_security_level,
                    'auth_protocol' => $device->snmp_auth_protocol,
                    'auth_password' => $device->snmp_auth_password,
                    'priv_protocol' => $device->snmp_priv_protocol,
                    'priv_password' => $device->snmp_priv_password
                ];

            default:
                throw new Exception("Unsupported SNMP version: {$device->snmp_version}");
        }
    }

    /**
     * Perform SNMP GET
     */
    private function snmpGet(array $session, string $oid): ?string
    {
        try {
            if ($session['version'] === SNMP::VERSION_3) {
                $result = @snmp3_get(
                    $session['host'],
                    $session['username'],
                    $this->mapSecurityLevel($session['security_level']),
                    $this->mapAuthProtocol($session['auth_protocol']),
                    $session['auth_password'] ?? '',
                    $this->mapPrivProtocol($session['priv_protocol']),
                    $session['priv_password'] ?? '',
                    $oid,
                    $this->timeout,
                    $this->retries
                );
            } else {
                $result = @snmpget(
                    $session['host'],
                    $session['community'],
                    $oid,
                    $this->timeout,
                    $this->retries
                );
            }

            if ($result === false) {
                $error = error_get_last();
                throw new Exception($error['message'] ?? 'SNMP GET failed');
            }

            return $this->cleanValue($result);
        } catch (Exception $e) {
            Log::warning("SNMP GET failed for OID {$oid}: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Perform SNMP WALK
     */
    private function snmpWalk(array $session, string $oid): array
    {
        try {
            if ($session['version'] === SNMP::VERSION_3) {
                $result = @snmp3_walk(
                    $session['host'],
                    $session['username'],
                    $this->mapSecurityLevel($session['security_level']),
                    $this->mapAuthProtocol($session['auth_protocol']),
                    $session['auth_password'] ?? '',
                    $this->mapPrivProtocol($session['priv_protocol']),
                    $session['priv_password'] ?? '',
                    $oid,
                    $this->timeout,
                    $this->retries
                );
            } else {
                $result = @snmpwalk(
                    $session['host'],
                    $session['community'],
                    $oid,
                    $this->timeout,
                    $this->retries
                );
            }

            if ($result === false) {
                return [];
            }

            return array_map([$this, 'cleanValue'], $result);
        } catch (Exception $e) {
            Log::warning("SNMP WALK failed for OID {$oid}: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Clean SNMP value
     */
    private function cleanValue($value): string
    {
        if ($value === null) {
            return '';
        }

        $value = (string) $value;

        // Remove type prefixes
        $value = preg_replace('/^(STRING|INTEGER|Counter32|Counter64|Gauge32|Timeticks|OID|IpAddress|Hex-STRING):\s*/i', '', $value);

        // Remove quotes
        $value = trim($value, '"\'');

        return trim($value);
    }

    /**
     * Parse uptime from timeticks
     */
    private function parseUptime(?string $value): ?int
    {
        if (!$value) {
            return null;
        }

        // Timeticks are in hundredths of a second
        if (preg_match('/^\((\d+)\)/', $value, $matches)) {
            return (int) ($matches[1] / 100);
        }

        // Try to parse as integer
        if (is_numeric($value)) {
            return (int) ($value / 100);
        }

        return null;
    }

    /**
     * Map security level string to SNMP constant
     */
    private function mapSecurityLevel(string $level): string
    {
        return match ($level) {
            'noAuthNoPriv' => 'noAuthNoPriv',
            'authNoPriv' => 'authNoPriv',
            'authPriv' => 'authPriv',
            default => 'authPriv'
        };
    }

    /**
     * Map auth protocol to SNMP format
     */
    private function mapAuthProtocol(string $protocol): string
    {
        return match (strtoupper($protocol)) {
            'MD5' => 'MD5',
            'SHA', 'SHA1' => 'SHA',
            'SHA256' => 'SHA-256',
            'SHA512' => 'SHA-512',
            default => 'SHA'
        };
    }

    /**
     * Map privacy protocol to SNMP format
     */
    private function mapPrivProtocol(string $protocol): string
    {
        return match (strtoupper($protocol)) {
            'DES' => 'DES',
            'AES', 'AES128' => 'AES',
            'AES192' => 'AES-192',
            'AES256' => 'AES-256',
            default => 'AES'
        };
    }

    /**
     * Get interface type name from IANA ifType
     */
    private function getInterfaceTypeName(int $type): string
    {
        $types = [
            1 => 'other',
            6 => 'ethernetCsmacd',
            7 => 'iso88023Csmacd',
            23 => 'ppp',
            24 => 'softwareLoopback',
            53 => 'propVirtual',
            131 => 'tunnel',
            135 => 'l2vlan',
            136 => 'l3ipvlan',
            161 => 'ieee8023adLag',
        ];

        return $types[$type] ?? "type-{$type}";
    }

    /**
     * Calculate interface speed
     */
    private function calculateSpeed($ifSpeed, $ifHighSpeed): int
    {
        // Use high-speed counter if available and non-zero (in Mbps)
        if ($ifHighSpeed && (int)$ifHighSpeed > 0) {
            return (int)$ifHighSpeed * 1000000; // Convert to bps
        }

        // Fall back to regular speed (already in bps, but capped at ~4Gbps)
        return (int)$ifSpeed;
    }

    /**
     * Poll all devices that need polling
     */
    public function pollAllDevices(): array
    {
        $results = [];

        $devices = Device::snmpEnabled()->get()->filter(function ($device) {
            return $device->needsSnmpPoll();
        });

        foreach ($devices as $device) {
            $results[$device->id] = $this->pollDevice($device);
        }

        return $results;
    }
}
