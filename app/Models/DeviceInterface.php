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
        'if_index',
        'name',
        'description',
        'type',
        'status',
        'admin_status',
        'oper_status',
        'speed',
        'speed_bps',
        'utilization_percent',
        'in_octets',
        'out_octets',
        'in_errors',
        'out_errors',
        'last_polled',
        'prev_in_octets',
        'prev_out_octets',
        'prev_poll_time',
        'in_bps',
        'out_bps',
        'in_utilization',
        'out_utilization'
    ];

    protected $casts = [
        'if_index' => 'integer',
        'speed' => 'integer',
        'speed_bps' => 'integer',
        'utilization_percent' => 'decimal:2',
        'in_octets' => 'integer',
        'out_octets' => 'integer',
        'in_errors' => 'integer',
        'out_errors' => 'integer',
        'prev_in_octets' => 'integer',
        'prev_out_octets' => 'integer',
        'in_bps' => 'integer',
        'out_bps' => 'integer',
        'in_utilization' => 'decimal:2',
        'out_utilization' => 'decimal:2',
        'last_polled' => 'datetime',
        'prev_poll_time' => 'datetime',
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