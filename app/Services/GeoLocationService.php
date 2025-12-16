<?php

namespace App\Services;

use App\Models\GeolocationCache;
use App\Models\Flow;
use GeoIp2\Database\Reader;
use GeoIp2\Exception\AddressNotFoundException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class GeoLocationService
{
    private ?Reader $cityReader = null;
    private ?Reader $asnReader = null;
    private array $memoryCache = [];
    private const MEMORY_CACHE_LIMIT = 1000;
    private const DB_CACHE_TTL_DAYS = 30;

    public function __construct()
    {
        $this->initReaders();
    }

    /**
     * Initialize MaxMind database readers
     */
    private function initReaders(): void
    {
        $cityDbPath = config('services.maxmind.database_path') . '/' . config('services.maxmind.city_db', 'GeoLite2-City.mmdb');
        $asnDbPath = config('services.maxmind.database_path') . '/' . config('services.maxmind.asn_db', 'GeoLite2-ASN.mmdb');

        try {
            if (file_exists($cityDbPath)) {
                $this->cityReader = new Reader($cityDbPath);
            }
            if (file_exists($asnDbPath)) {
                $this->asnReader = new Reader($asnDbPath);
            }
        } catch (\Exception $e) {
            Log::warning('Failed to initialize MaxMind readers: ' . $e->getMessage());
        }
    }

    /**
     * Lookup geolocation for a single IP
     */
    public function lookup(string $ip): ?array
    {
        // Check if private IP
        if ($this->isPrivateIP($ip)) {
            return $this->getPrivateIPResult($ip);
        }

        // Check memory cache first
        if (isset($this->memoryCache[$ip])) {
            return $this->memoryCache[$ip];
        }

        // Check database cache
        $cached = GeolocationCache::getCached($ip);
        if ($cached) {
            $result = $cached->toLocationArray();
            $this->addToMemoryCache($ip, $result);
            return $result;
        }

        // Fetch from MaxMind
        $result = $this->fetchFromMaxMind($ip);

        // Cache the result
        if ($result) {
            GeolocationCache::store($ip, $result, self::DB_CACHE_TTL_DAYS);
            $this->addToMemoryCache($ip, $result);
        }

        return $result;
    }

    /**
     * Batch lookup for multiple IPs (more efficient)
     */
    public function batchLookup(array $ips): array
    {
        $results = [];
        $toFetch = [];

        // First pass: check memory and database cache
        foreach ($ips as $ip) {
            if ($this->isPrivateIP($ip)) {
                $results[$ip] = $this->getPrivateIPResult($ip);
                continue;
            }

            if (isset($this->memoryCache[$ip])) {
                $results[$ip] = $this->memoryCache[$ip];
                continue;
            }

            $toFetch[] = $ip;
        }

        // Batch fetch from database cache
        if (!empty($toFetch)) {
            $cachedRecords = GeolocationCache::whereIn('ip_address', $toFetch)
                ->valid()
                ->get()
                ->keyBy('ip_address');

            foreach ($toFetch as $ip) {
                if ($cachedRecords->has($ip)) {
                    $result = $cachedRecords[$ip]->toLocationArray();
                    $results[$ip] = $result;
                    $this->addToMemoryCache($ip, $result);
                } else {
                    // Need to fetch from MaxMind
                    $result = $this->fetchFromMaxMind($ip);
                    if ($result) {
                        GeolocationCache::store($ip, $result, self::DB_CACHE_TTL_DAYS);
                        $this->addToMemoryCache($ip, $result);
                    }
                    $results[$ip] = $result;
                }
            }
        }

        return $results;
    }

    /**
     * Fetch geolocation from MaxMind databases
     */
    private function fetchFromMaxMind(string $ip): ?array
    {
        $result = [
            'country_code' => null,
            'country_name' => null,
            'city' => null,
            'latitude' => null,
            'longitude' => null,
            'asn' => null,
            'as_name' => null,
            'is_private' => false,
        ];

        // Try city database
        if ($this->cityReader) {
            try {
                $record = $this->cityReader->city($ip);
                $result['country_code'] = $record->country->isoCode;
                $result['country_name'] = $record->country->name;
                $result['city'] = $record->city->name;
                $result['latitude'] = $record->location->latitude;
                $result['longitude'] = $record->location->longitude;
            } catch (AddressNotFoundException $e) {
                // IP not found in database
            } catch (\Exception $e) {
                Log::warning("MaxMind city lookup failed for {$ip}: " . $e->getMessage());
            }
        }

        // Try ASN database
        if ($this->asnReader) {
            try {
                $record = $this->asnReader->asn($ip);
                $result['asn'] = $record->autonomousSystemNumber;
                $result['as_name'] = $record->autonomousSystemOrganization;
            } catch (AddressNotFoundException $e) {
                // IP not found in database
            } catch (\Exception $e) {
                Log::warning("MaxMind ASN lookup failed for {$ip}: " . $e->getMessage());
            }
        }

        // Return null if we couldn't get any useful data
        if (!$result['country_code'] && !$result['asn']) {
            return null;
        }

        return $result;
    }

    /**
     * Check if an IP is private/local
     */
    public function isPrivateIP(string $ip): bool
    {
        // Check for IPv4 private ranges
        $privateRanges = [
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
            '127.0.0.0/8',
            '169.254.0.0/16',
            '0.0.0.0/8',
            '100.64.0.0/10', // CGNAT
        ];

        $ipLong = ip2long($ip);
        if ($ipLong === false) {
            return true; // Invalid IP, treat as private
        }

        foreach ($privateRanges as $range) {
            [$subnet, $mask] = explode('/', $range);
            $subnetLong = ip2long($subnet);
            $maskLong = -1 << (32 - (int)$mask);

            if (($ipLong & $maskLong) === ($subnetLong & $maskLong)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Get result for private IP addresses
     */
    private function getPrivateIPResult(string $ip): array
    {
        return [
            'country_code' => 'XX',
            'country_name' => 'Private Network',
            'city' => 'Local',
            'latitude' => null,
            'longitude' => null,
            'asn' => null,
            'as_name' => 'Private Network',
            'is_private' => true,
        ];
    }

    /**
     * Add to memory cache with size limit
     */
    private function addToMemoryCache(string $ip, ?array $result): void
    {
        if (count($this->memoryCache) >= self::MEMORY_CACHE_LIMIT) {
            // Remove oldest entries (FIFO)
            $this->memoryCache = array_slice($this->memoryCache, -500, null, true);
        }
        $this->memoryCache[$ip] = $result;
    }

    /**
     * Get traffic grouped by country for a time range
     */
    public function getTrafficByCountry(string $timeRange = '1hour', int $limit = 20): Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::select('dst_country_code', 'dst_country_name')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->where('created_at', '>=', $start)
            ->whereNotNull('dst_country_code')
            ->where('dst_country_code', '!=', 'XX')
            ->groupBy('dst_country_code', 'dst_country_name')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }

    /**
     * Get map data for visualization
     */
    public function getMapData(string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        // Get traffic by destination coordinates
        $destinations = Flow::select('dst_country_code', 'dst_country_name', 'dst_city', 'dst_latitude', 'dst_longitude')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('created_at', '>=', $start)
            ->whereNotNull('dst_latitude')
            ->whereNotNull('dst_longitude')
            ->groupBy('dst_country_code', 'dst_country_name', 'dst_city', 'dst_latitude', 'dst_longitude')
            ->orderByDesc('total_bytes')
            ->limit(100)
            ->get();

        // Get traffic flows (source to destination)
        $flows = Flow::select(
            'src_country_code', 'src_latitude', 'src_longitude',
            'dst_country_code', 'dst_latitude', 'dst_longitude'
        )
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('created_at', '>=', $start)
            ->whereNotNull('src_latitude')
            ->whereNotNull('dst_latitude')
            ->where('src_country_code', '!=', 'XX')
            ->where('dst_country_code', '!=', 'XX')
            ->groupBy('src_country_code', 'src_latitude', 'src_longitude', 'dst_country_code', 'dst_latitude', 'dst_longitude')
            ->orderByDesc('total_bytes')
            ->limit(50)
            ->get();

        return [
            'destinations' => $destinations->map(fn($d) => [
                'country_code' => $d->dst_country_code,
                'country_name' => $d->dst_country_name,
                'city' => $d->dst_city,
                'latitude' => (float) $d->dst_latitude,
                'longitude' => (float) $d->dst_longitude,
                'flow_count' => $d->flow_count,
                'bytes' => $d->total_bytes,
            ])->toArray(),
            'flows' => $flows->map(fn($f) => [
                'src_lat' => (float) $f->src_latitude,
                'src_lng' => (float) $f->src_longitude,
                'dst_lat' => (float) $f->dst_latitude,
                'dst_lng' => (float) $f->dst_longitude,
                'bytes' => $f->total_bytes,
            ])->toArray(),
        ];
    }

    /**
     * Get traffic grouped by city within a country
     */
    public function getTrafficByCity(string $countryCode, string $timeRange = '1hour', int $limit = 20): Collection
    {
        $start = $this->getTimeRangeStart($timeRange);

        return Flow::select('dst_city', 'dst_latitude', 'dst_longitude')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('created_at', '>=', $start)
            ->where('dst_country_code', $countryCode)
            ->whereNotNull('dst_city')
            ->groupBy('dst_city', 'dst_latitude', 'dst_longitude')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();
    }

    /**
     * Get country statistics summary
     */
    public function getCountryStats(string $timeRange = '1hour'): array
    {
        $start = $this->getTimeRangeStart($timeRange);

        $stats = Flow::selectRaw('COUNT(DISTINCT dst_country_code) as country_count')
            ->selectRaw('COUNT(DISTINCT dst_city) as city_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('created_at', '>=', $start)
            ->whereNotNull('dst_country_code')
            ->where('dst_country_code', '!=', 'XX')
            ->first();

        return [
            'country_count' => $stats->country_count ?? 0,
            'city_count' => $stats->city_count ?? 0,
            'total_bytes' => $stats->total_bytes ?? 0,
        ];
    }

    /**
     * Convert time range string to Carbon date
     */
    private function getTimeRangeStart(string $timeRange): \Carbon\Carbon
    {
        return match ($timeRange) {
            '15min' => now()->subMinutes(15),
            '30min' => now()->subMinutes(30),
            '1hour' => now()->subHour(),
            '6hours' => now()->subHours(6),
            '24hours' => now()->subDay(),
            '7days' => now()->subDays(7),
            '30days' => now()->subDays(30),
            default => now()->subHour(),
        };
    }

    /**
     * Check if MaxMind database is available
     */
    public function isDatabaseAvailable(): bool
    {
        return $this->cityReader !== null;
    }

    /**
     * Get database info
     */
    public function getDatabaseInfo(): array
    {
        $cityDbPath = config('services.maxmind.database_path') . '/' . config('services.maxmind.city_db', 'GeoLite2-City.mmdb');
        $asnDbPath = config('services.maxmind.database_path') . '/' . config('services.maxmind.asn_db', 'GeoLite2-ASN.mmdb');

        return [
            'city_db' => [
                'exists' => file_exists($cityDbPath),
                'path' => $cityDbPath,
                'size' => file_exists($cityDbPath) ? filesize($cityDbPath) : 0,
                'modified' => file_exists($cityDbPath) ? date('Y-m-d H:i:s', filemtime($cityDbPath)) : null,
            ],
            'asn_db' => [
                'exists' => file_exists($asnDbPath),
                'path' => $asnDbPath,
                'size' => file_exists($asnDbPath) ? filesize($asnDbPath) : 0,
                'modified' => file_exists($asnDbPath) ? date('Y-m-d H:i:s', filemtime($asnDbPath)) : null,
            ],
        ];
    }
}
