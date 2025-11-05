<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeviceInterface extends Model
{
    use HasFactory;

    protected $table = 'interfaces';

    protected $fillable = [
        'device_id',
        'name',
        'description',
        'type',
        'status',
        'speed_bps',
        'utilization_percent',
        'in_octets',
        'out_octets'
    ];

    protected $casts = [
        'speed_bps' => 'integer',
        'utilization_percent' => 'decimal:2',
        'in_octets' => 'integer',
        'out_octets' => 'integer',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function calculateUtilization(): void
    {
        if ($this->speed_bps > 0) {
            $totalBps = ($this->in_octets + $this->out_octets) * 8;
            $this->utilization_percent = min(100, ($totalBps / $this->speed_bps) * 100);
            $this->save();
        }
    }

    public function getFormattedSpeedAttribute(): string
    {
        if ($this->speed_bps >= 1000000000) {
            return round($this->speed_bps / 1000000000, 2) . ' Gbps';
        } elseif ($this->speed_bps >= 1000000) {
            return round($this->speed_bps / 1000000, 2) . ' Mbps';
        }
        return round($this->speed_bps / 1000, 2) . ' Kbps';
    }
}