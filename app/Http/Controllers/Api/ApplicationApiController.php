<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ApplicationIdentificationService;
use App\Models\Flow;
use App\Models\Device;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ApplicationApiController extends Controller
{
    public function __construct(
        private ApplicationIdentificationService $appService
    ) {}

    /**
     * Get all application categories
     */
    public function categories(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->appService->getCategories(),
        ]);
    }

    /**
     * Get all applications grouped by category
     */
    public function byCategory(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->appService->getApplicationsByCategory(),
        ]);
    }

    /**
     * Get all applications with their metadata
     */
    public function all(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $this->appService->getAllApplications(),
        ]);
    }

    /**
     * Get top applications by traffic
     */
    public function top(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $limit = min($request->input('limit', 10), 50);
        $start = $this->getTimeRangeStart($timeRange);

        $apps = Flow::select('application', 'app_category')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('SUM(packets) as total_packets')
            ->where('created_at', '>=', $start)
            ->whereNotNull('application')
            ->groupBy('application', 'app_category')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        // Enrich with icons and colors
        $apps = $apps->map(function ($app) {
            $info = $this->appService->getApplicationInfo($app->application);
            return [
                'name' => $app->application,
                'category' => $app->app_category ?? $info['category'],
                'icon' => $info['icon'],
                'color' => $info['color'],
                'flow_count' => $app->flow_count,
                'total_bytes' => $app->total_bytes,
                'total_packets' => $app->total_packets,
                'formatted_bytes' => $this->formatBytes($app->total_bytes),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $apps,
        ]);
    }

    /**
     * Get applications by category with traffic stats
     */
    public function categoryTraffic(Request $request): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $start = $this->getTimeRangeStart($timeRange);

        $categories = Flow::select('app_category')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(DISTINCT application) as app_count')
            ->where('created_at', '>=', $start)
            ->whereNotNull('app_category')
            ->groupBy('app_category')
            ->orderByDesc('total_bytes')
            ->get();

        // Enrich with category metadata
        $categoryMeta = $this->appService->getCategories();
        $categories = $categories->map(function ($cat) use ($categoryMeta) {
            $meta = $categoryMeta[$cat->app_category] ?? [
                'icon' => 'help-circle',
                'color' => '#6B7280',
                'priority' => 99,
            ];
            return [
                'name' => $cat->app_category,
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'priority' => $meta['priority'],
                'flow_count' => $cat->flow_count,
                'total_bytes' => $cat->total_bytes,
                'app_count' => $cat->app_count,
                'formatted_bytes' => $this->formatBytes($cat->total_bytes),
            ];
        })->sortBy('priority')->values();

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get application trends over time
     */
    public function trends(Request $request, string $application): JsonResponse
    {
        $timeRange = $request->input('range', '24hours');
        $start = $this->getTimeRangeStart($timeRange);

        // Determine interval based on time range
        // PostgreSQL date_trunc only accepts: microseconds, milliseconds, second, minute, hour, day, week, month, quarter, year
        // For 10 minute intervals, we use a custom expression
        $truncExpression = match ($timeRange) {
            '1hour' => "date_trunc('minute', created_at)",
            '6hours' => "date_trunc('hour', created_at) + (INTERVAL '10 minutes' * FLOOR(EXTRACT(MINUTE FROM created_at)::integer / 10))",
            '24hours' => "date_trunc('hour', created_at)",
            '7days' => "date_trunc('day', created_at)",
            default => "date_trunc('hour', created_at)",
        };

        $trends = Flow::selectRaw("{$truncExpression} as time_bucket")
            ->selectRaw('SUM(bytes) as total_bytes')
            ->selectRaw('COUNT(*) as flow_count')
            ->where('application', $application)
            ->where('created_at', '>=', $start)
            ->groupBy('time_bucket')
            ->orderBy('time_bucket')
            ->get();

        return response()->json([
            'success' => true,
            'application' => $application,
            'info' => $this->appService->getApplicationInfo($application),
            'data' => [
                'labels' => $trends->pluck('time_bucket')->map(fn($t) => \Carbon\Carbon::parse($t)->format('H:i'))->toArray(),
                'bytes' => $trends->pluck('total_bytes')->toArray(),
                'flows' => $trends->pluck('flow_count')->toArray(),
            ],
        ]);
    }

    /**
     * Get device-specific application traffic
     */
    public function deviceApplications(Request $request, Device $device): JsonResponse
    {
        $timeRange = $request->input('range', '1hour');
        $limit = min($request->input('limit', 10), 50);
        $start = $this->getTimeRangeStart($timeRange);

        $apps = Flow::select('application', 'app_category')
            ->selectRaw('COUNT(*) as flow_count')
            ->selectRaw('SUM(bytes) as total_bytes')
            ->where('device_id', $device->id)
            ->where('created_at', '>=', $start)
            ->whereNotNull('application')
            ->groupBy('application', 'app_category')
            ->orderByDesc('total_bytes')
            ->limit($limit)
            ->get();

        $apps = $apps->map(function ($app) {
            $info = $this->appService->getApplicationInfo($app->application);
            return [
                'name' => $app->application,
                'category' => $app->app_category ?? $info['category'],
                'icon' => $info['icon'],
                'color' => $info['color'],
                'flow_count' => $app->flow_count,
                'total_bytes' => $app->total_bytes,
                'formatted_bytes' => $this->formatBytes($app->total_bytes),
            ];
        });

        return response()->json([
            'success' => true,
            'device' => $device->name,
            'data' => $apps,
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

    /**
     * Format bytes to human readable string
     */
    private function formatBytes(int $bytes): string
    {
        if ($bytes >= 1073741824) {
            return round($bytes / 1073741824, 2) . ' GB';
        } elseif ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }
}
