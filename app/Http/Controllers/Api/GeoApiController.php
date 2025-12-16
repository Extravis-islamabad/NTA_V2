<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\GeoLocationService;
use App\Models\Flow;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class GeoApiController extends Controller
{
    public function __construct(
        private GeoLocationService $geoService
    ) {}

    /**
     * Get traffic by country
     */
    public function trafficByCountry(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $limit = min($request->input('limit', 20), 100);

        $data = $this->geoService->getTrafficByCountry($timeRange, $limit);

        return response()->json([
            'success' => true,
            'data' => $data,
            'stats' => $this->geoService->getCountryStats($timeRange),
        ]);
    }

    /**
     * Get traffic by city within a country
     */
    public function trafficByCity(Request $request, string $countryCode): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $limit = min($request->input('limit', 20), 100);

        $data = $this->geoService->getTrafficByCity($countryCode, $timeRange, $limit);

        return response()->json([
            'success' => true,
            'country_code' => $countryCode,
            'data' => $data,
        ]);
    }

    /**
     * Get map data for visualization
     */
    public function mapData(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $deviceId = $request->input('device_id');

        $data = $this->geoService->getMapData($timeRange);

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * Get traffic flows between locations
     */
    public function trafficFlows(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);
        $limit = min($request->input('limit', 50), 200);

        $flows = Flow::select(
            'src_country_code', 'src_country_name', 'src_latitude', 'src_longitude',
            'dst_country_code', 'dst_country_name', 'dst_latitude', 'dst_longitude'
        )
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('created_at', '>=', $start)
            ->whereNotNull('src_latitude')
            ->whereNotNull('dst_latitude')
            ->where('src_country_code', '!=', 'XX')
            ->where('dst_country_code', '!=', 'XX')
            ->groupBy(
                'src_country_code', 'src_country_name', 'src_latitude', 'src_longitude',
                'dst_country_code', 'dst_country_name', 'dst_latitude', 'dst_longitude'
            )
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'data' => $flows->map(fn($f) => [
                'source' => [
                    'country_code' => $f->src_country_code,
                    'country_name' => $f->src_country_name,
                    'latitude' => (float) $f->src_latitude,
                    'longitude' => (float) $f->src_longitude,
                ],
                'destination' => [
                    'country_code' => $f->dst_country_code,
                    'country_name' => $f->dst_country_name,
                    'latitude' => (float) $f->dst_latitude,
                    'longitude' => (float) $f->dst_longitude,
                ],
                'flow_count' => $f->flow_count,
                'bytes' => $f->total_bytes,
            ]),
        ]);
    }

    /**
     * Get geolocation for device-specific traffic
     */
    public function deviceGeoTraffic(Request $request, Device $device): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);
        $limit = min($request->input('limit', 20), 100);

        $traffic = Flow::select('dst_country_code', 'dst_country_name')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->whereNotNull('dst_country_code')
            ->where('dst_country_code', '!=', 'XX')
            ->groupBy('dst_country_code', 'dst_country_name')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        return response()->json([
            'success' => true,
            'device' => $device->name,
            'data' => $traffic,
        ]);
    }

    /**
     * Get GeoIP database status
     */
    public function databaseStatus(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'available' => $this->geoService->isDatabaseAvailable(),
            'database' => $this->geoService->getDatabaseInfo(),
        ]);
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
}
