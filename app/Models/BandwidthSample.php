<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BandwidthSample extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'interface_id',
        'in_bytes',
        'out_bytes',
        'in_packets',
        'out_packets',
        'in_bps',
        'out_bps',
        'flow_count',
        'sampled_at',
    ];

    protected $casts = [
        'in_bytes' => 'integer',
        'out_bytes' => 'integer',
        'in_packets' => 'integer',
        'out_packets' => 'integer',
        'in_bps' => 'integer',
        'out_bps' => 'integer',
        'flow_count' => 'integer',
        'sampled_at' => 'datetime',
    ];

    /**
     * Get the device that owns the sample
     */
    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    /**
     * Get the interface that owns the sample (optional)
     */
    public function interface(): BelongsTo
    {
        return $this->belongsTo(DeviceInterface::class, 'interface_id');
    }

    /**
     * Scope to filter by device
     */
    public function scopeForDevice($query, int $deviceId)
    {
        return $query->where('device_id', $deviceId);
    }

    /**
     * Scope to filter by interface
     */
    public function scopeForInterface($query, int $interfaceId)
    {
        return $query->where('interface_id', $interfaceId);
    }

    /**
     * Scope to get samples within a time range
     */
    public function scopeWithinTime($query, $start, $end = null)
    {
        $end = $end ?? now();
        return $query->whereBetween('sampled_at', [$start, $end]);
    }

    /**
     * Scope to get recent samples (for sparklines)
     */
    public function scopeRecent($query, int $minutes = 30)
    {
        return $query->where('sampled_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Get sparkline data points for a device
     */
    public static function getSparklineData(int $deviceId, int $points = 20, int $minutes = 30): array
    {
        $samples = static::forDevice($deviceId)
            ->whereNull('interface_id')
            ->recent($minutes)
            ->orderBy('sampled_at', 'asc')
            ->limit($points)
            ->get();

        return [
            'labels' => $samples->pluck('sampled_at')->map(fn($t) => $t->format('H:i'))->toArray(),
            'in_bps' => $samples->pluck('in_bps')->toArray(),
            'out_bps' => $samples->pluck('out_bps')->toArray(),
            'total_bps' => $samples->map(fn($s) => $s->in_bps + $s->out_bps)->toArray(),
        ];
    }

    /**
     * Get the total bandwidth (in + out)
     */
    public function getTotalBpsAttribute(): int
    {
        return $this->in_bps + $this->out_bps;
    }

    /**
     * Get formatted bandwidth string
     */
    public function getFormattedBandwidthAttribute(): string
    {
        $bps = $this->total_bps;

        if ($bps >= 1000000000) {
            return round($bps / 1000000000, 2) . ' Gbps';
        } elseif ($bps >= 1000000) {
            return round($bps / 1000000, 2) . ' Mbps';
        } elseif ($bps >= 1000) {
            return round($bps / 1000, 2) . ' Kbps';
        }

        return $bps . ' bps';
    }

    /**
     * Store a new bandwidth sample
     */
    public static function storeSample(int $deviceId, array $metrics, ?int $interfaceId = null): self
    {
        return static::create([
            'device_id' => $deviceId,
            'interface_id' => $interfaceId,
            'in_bytes' => $metrics['in_bytes'] ?? 0,
            'out_bytes' => $metrics['out_bytes'] ?? 0,
            'in_packets' => $metrics['in_packets'] ?? 0,
            'out_packets' => $metrics['out_packets'] ?? 0,
            'in_bps' => $metrics['in_bps'] ?? 0,
            'out_bps' => $metrics['out_bps'] ?? 0,
            'flow_count' => $metrics['flow_count'] ?? 0,
            'sampled_at' => now(),
        ]);
    }

    /**
     * Clean up old samples (keep only last X days)
     */
    public static function cleanup(int $keepDays = 7): int
    {
        return static::where('sampled_at', '<', now()->subDays($keepDays))->delete();
    }
}
