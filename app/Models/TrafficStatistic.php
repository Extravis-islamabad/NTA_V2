<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrafficStatistic extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'interface_id',
        'protocol',
        'application',
        'total_bytes',
        'total_packets',
        'flow_count',
        'avg_speed_bps',
        'max_speed_bps',
        'interval_type',
        'interval_start',
        'interval_end'
    ];

    protected $casts = [
        'total_bytes' => 'integer',
        'total_packets' => 'integer',
        'flow_count' => 'integer',
        'avg_speed_bps' => 'decimal:2',
        'max_speed_bps' => 'decimal:2',
        'interval_start' => 'datetime',
        'interval_end' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function interface(): BelongsTo
    {
        return $this->belongsTo(DeviceInterface::class);
    }

    public function getFormattedBytesAttribute(): string
    {
        $bytes = $this->total_bytes;
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