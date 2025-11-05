<?php

namespace App\Services;

class CloudProviderService
{
    private array $cloudProviders = [
        'aws' => [
            'name' => 'Amazon Web Services',
            'ranges' => [
                '3.0.0.0/8',
                '13.0.0.0/8',
                '18.0.0.0/8',
                '52.0.0.0/8',
                '54.0.0.0/8',
                '99.0.0.0/8',
            ]
        ],
        'azure' => [
            'name' => 'Microsoft Azure',
            'ranges' => [
                '13.64.0.0/11',
                '40.0.0.0/8',
                '104.0.0.0/8',
                '137.116.0.0/14',
            ]
        ],
        'gcp' => [
            'name' => 'Google Cloud Platform',
            'ranges' => [
                '34.0.0.0/8',
                '35.0.0.0/8',
                '130.211.0.0/16',
                '146.148.0.0/17',
            ]
        ],
        'cloudflare' => [
            'name' => 'Cloudflare',
            'ranges' => [
                '173.245.48.0/20',
                '103.21.244.0/22',
                '104.16.0.0/12',
            ]
        ],
        'digitalocean' => [
            'name' => 'DigitalOcean',
            'ranges' => [
                '159.65.0.0/16',
                '167.99.0.0/16',
                '178.128.0.0/16',
            ]
        ]
    ];

    public function identifyProvider(string $ip): ?array
    {
        $ipLong = ip2long($ip);
        
        foreach ($this->cloudProviders as $key => $provider) {
            foreach ($provider['ranges'] as $range) {
                if ($this->ipInRange($ip, $range)) {
                    return [
                        'provider' => $key,
                        'name' => $provider['name'],
                        'ip' => $ip,
                    ];
                }
            }
        }

        return null;
    }

    private function ipInRange(string $ip, string $range): bool
    {
        list($subnet, $mask) = explode('/', $range);
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - (int)$mask);
        
        return ($ipLong & $maskLong) == ($subnetLong & $maskLong);
    }

    public function getAllProviders(): array
    {
        return array_map(fn($provider) => $provider['name'], $this->cloudProviders);
    }
}