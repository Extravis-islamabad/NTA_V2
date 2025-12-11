<?php

namespace App\Services;

class ASLookupService
{
    /**
     * Extended AS database with common cloud providers and major networks
     * For production with high volume, consider using MaxMind GeoLite2-ASN database
     */
    private array $asDatabase = [
        // AWS
        ['cidr' => '3.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '13.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '15.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '18.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '35.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '52.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],
        ['cidr' => '54.0.0.0/8', 'asn' => 16509, 'name' => 'Amazon AWS', 'country' => 'US'],

        // Microsoft Azure
        ['cidr' => '13.64.0.0/11', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '20.0.0.0/8', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '23.96.0.0/13', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '40.64.0.0/10', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '51.0.0.0/8', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '104.40.0.0/13', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '137.116.0.0/15', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],
        ['cidr' => '168.61.0.0/16', 'asn' => 8075, 'name' => 'Microsoft Azure', 'country' => 'US'],

        // Google Cloud / Google LLC
        ['cidr' => '8.8.0.0/16', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        ['cidr' => '8.34.208.0/20', 'asn' => 15169, 'name' => 'Google Cloud', 'country' => 'US'],
        ['cidr' => '34.64.0.0/10', 'asn' => 15169, 'name' => 'Google Cloud', 'country' => 'US'],
        ['cidr' => '35.184.0.0/13', 'asn' => 15169, 'name' => 'Google Cloud', 'country' => 'US'],
        ['cidr' => '35.192.0.0/12', 'asn' => 15169, 'name' => 'Google Cloud', 'country' => 'US'],
        ['cidr' => '104.196.0.0/14', 'asn' => 15169, 'name' => 'Google Cloud', 'country' => 'US'],
        ['cidr' => '142.250.0.0/15', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        ['cidr' => '172.217.0.0/16', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        ['cidr' => '173.194.0.0/16', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        ['cidr' => '209.85.128.0/17', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],
        ['cidr' => '216.58.192.0/19', 'asn' => 15169, 'name' => 'Google LLC', 'country' => 'US'],

        // Cloudflare
        ['cidr' => '1.1.1.0/24', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '104.16.0.0/12', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '172.64.0.0/13', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '131.0.72.0/22', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '141.101.64.0/18', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '162.158.0.0/15', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '173.245.48.0/20', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '188.114.96.0/20', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '190.93.240.0/20', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '197.234.240.0/22', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],
        ['cidr' => '198.41.128.0/17', 'asn' => 13335, 'name' => 'Cloudflare', 'country' => 'US'],

        // Akamai
        ['cidr' => '23.0.0.0/12', 'asn' => 20940, 'name' => 'Akamai Technologies', 'country' => 'US'],
        ['cidr' => '23.32.0.0/11', 'asn' => 20940, 'name' => 'Akamai Technologies', 'country' => 'US'],
        ['cidr' => '104.64.0.0/10', 'asn' => 20940, 'name' => 'Akamai Technologies', 'country' => 'US'],

        // Facebook/Meta
        ['cidr' => '31.13.24.0/21', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '31.13.64.0/18', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '66.220.144.0/20', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '69.63.176.0/20', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '69.171.224.0/19', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '157.240.0.0/16', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '173.252.64.0/18', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],
        ['cidr' => '185.60.216.0/22', 'asn' => 32934, 'name' => 'Meta Platforms', 'country' => 'US'],

        // DigitalOcean
        ['cidr' => '64.225.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '67.205.128.0/17', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '104.131.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '138.68.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '139.59.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '142.93.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '157.230.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '159.65.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '159.89.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '161.35.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '162.243.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '165.22.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '167.71.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '167.172.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '178.62.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '178.128.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],
        ['cidr' => '206.189.0.0/16', 'asn' => 14061, 'name' => 'DigitalOcean', 'country' => 'US'],

        // Netflix
        ['cidr' => '23.246.0.0/18', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '45.57.0.0/17', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '64.120.128.0/17', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '66.197.128.0/17', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '108.175.32.0/20', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '192.173.64.0/18', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '198.38.96.0/19', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],
        ['cidr' => '198.45.48.0/20', 'asn' => 2906, 'name' => 'Netflix', 'country' => 'US'],

        // Twitter/X
        ['cidr' => '104.244.40.0/21', 'asn' => 13414, 'name' => 'Twitter/X', 'country' => 'US'],
        ['cidr' => '199.16.156.0/22', 'asn' => 13414, 'name' => 'Twitter/X', 'country' => 'US'],
        ['cidr' => '199.59.148.0/22', 'asn' => 13414, 'name' => 'Twitter/X', 'country' => 'US'],
        ['cidr' => '199.96.56.0/21', 'asn' => 13414, 'name' => 'Twitter/X', 'country' => 'US'],

        // Apple
        ['cidr' => '17.0.0.0/8', 'asn' => 714, 'name' => 'Apple Inc', 'country' => 'US'],

        // Fastly CDN
        ['cidr' => '23.235.32.0/20', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],
        ['cidr' => '104.156.80.0/20', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],
        ['cidr' => '146.75.0.0/17', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],
        ['cidr' => '151.101.0.0/16', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],
        ['cidr' => '167.82.0.0/17', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],
        ['cidr' => '199.232.0.0/16', 'asn' => 54113, 'name' => 'Fastly', 'country' => 'US'],

        // Linode
        ['cidr' => '45.33.0.0/17', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '45.56.64.0/18', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '45.79.0.0/16', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '50.116.0.0/17', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '66.175.208.0/20', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '69.164.192.0/18', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '96.126.96.0/19', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '173.230.128.0/17', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],
        ['cidr' => '173.255.192.0/18', 'asn' => 63949, 'name' => 'Linode', 'country' => 'US'],

        // OVH
        ['cidr' => '51.68.0.0/14', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '54.36.0.0/14', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '91.134.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '92.222.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '135.125.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '137.74.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '145.239.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '147.135.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '149.202.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '151.80.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '158.69.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '164.132.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '176.31.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '178.32.0.0/15', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '188.165.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],
        ['cidr' => '192.99.0.0/16', 'asn' => 16276, 'name' => 'OVH SAS', 'country' => 'FR'],

        // Hetzner
        ['cidr' => '5.9.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '78.46.0.0/15', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '88.198.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '88.99.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '94.130.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '95.216.0.0/15', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '116.202.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '116.203.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '135.181.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '136.243.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '138.201.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '144.76.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '148.251.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '157.90.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '159.69.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '162.55.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '168.119.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '176.9.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '178.63.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '188.40.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],
        ['cidr' => '195.201.0.0/16', 'asn' => 24940, 'name' => 'Hetzner Online', 'country' => 'DE'],

        // Vultr
        ['cidr' => '45.32.0.0/15', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '45.63.0.0/17', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '45.76.0.0/15', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '95.179.128.0/17', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '108.61.0.0/16', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '140.82.0.0/16', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '144.202.0.0/16', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '149.28.0.0/16', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '155.138.128.0/17', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],
        ['cidr' => '207.148.64.0/18', 'asn' => 20473, 'name' => 'Vultr Holdings', 'country' => 'US'],

        // Private Networks (RFC 1918)
        ['cidr' => '10.0.0.0/8', 'asn' => 0, 'name' => 'Private Network', 'country' => 'LAN'],
        ['cidr' => '172.16.0.0/12', 'asn' => 0, 'name' => 'Private Network', 'country' => 'LAN'],
        ['cidr' => '192.168.0.0/16', 'asn' => 0, 'name' => 'Private Network', 'country' => 'LAN'],

        // Loopback
        ['cidr' => '127.0.0.0/8', 'asn' => 0, 'name' => 'Loopback', 'country' => 'LOCAL'],

        // Link-local
        ['cidr' => '169.254.0.0/16', 'asn' => 0, 'name' => 'Link-Local', 'country' => 'LOCAL'],
    ];

    public function lookupAS(string $ip): ?array
    {
        foreach ($this->asDatabase as $entry) {
            if ($this->ipInRange($ip, $entry['cidr'])) {
                return [
                    'asn' => $entry['asn'],
                    'name' => $entry['name'],
                    'country' => $entry['country'],
                    'ip' => $ip
                ];
            }
        }

        // Return unknown for unmatched IPs
        return [
            'asn' => null,
            'name' => 'Unknown',
            'country' => 'Unknown',
            'ip' => $ip
        ];
    }

    private function ipInRange(string $ip, string $cidr): bool
    {
        list($subnet, $mask) = explode('/', $cidr);

        $ip = ip2long($ip);
        $subnet = ip2long($subnet);

        if ($ip === false || $subnet === false) {
            return false;
        }

        $mask = ~((1 << (32 - $mask)) - 1);

        return ($ip & $mask) === ($subnet & $mask);
    }

    /**
     * Get all known AS names for display
     */
    public function getKnownProviders(): array
    {
        $providers = [];
        foreach ($this->asDatabase as $entry) {
            if ($entry['asn'] > 0 && !in_array($entry['name'], $providers)) {
                $providers[] = $entry['name'];
            }
        }
        return array_unique($providers);
    }
}
