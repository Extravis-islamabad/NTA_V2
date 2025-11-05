<?php

namespace App\Services;

class ASLookupService
{
    // Simulated AS database - in production, you'd use a real GeoIP/AS database
    private array $asDatabase = [
        '8.8.8.0/24' => ['asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        '1.1.1.0/24' => ['asn' => 13335, 'name' => 'Cloudflare Inc', 'country' => 'US'],
        '13.0.0.0/8' => ['asn' => 16509, 'name' => 'Amazon.com Inc', 'country' => 'US'],
        '40.0.0.0/8' => ['asn' => 8075, 'name' => 'Microsoft Corporation', 'country' => 'US'],
        '34.0.0.0/8' => ['asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        '192.168.0.0/16' => ['asn' => 0, 'name' => 'Private Network', 'country' => 'Private'],
        '10.0.0.0/8' => ['asn' => 0, 'name' => 'Private Network', 'country' => 'Private'],
        '172.16.0.0/12' => ['asn' => 0, 'name' => 'Private Network', 'country' => 'Private'],
    ];

    public function lookupAS(string $ip): ?array
    {
        foreach ($this->asDatabase as $range => $asInfo) {
            if ($this->ipInRange($ip, $range)) {
                return array_merge($asInfo, ['ip' => $ip]);
            }
        }

        // Default for unknown IPs
        return [
            'asn' => 0,
            'name' => 'Unknown AS',
            'country' => 'Unknown',
            'ip' => $ip
        ];
    }

    private function ipInRange(string $ip, string $range): bool
    {
        list($subnet, $mask) = explode('/', $range);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - (int)$mask);
        
        return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
    }
}