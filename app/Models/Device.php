<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'ip_address',
        'type',
        'location',
        'status',
        'device_group',
        'interface_count',
        'flow_count',
        'metadata',
        'last_seen_at'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function interfaces(): HasMany
    {
        return $this->hasMany(DeviceInterface::class);
    }

    public function flows(): HasMany
    {
        return $this->hasMany(Flow::class);
    }

    public function trafficStatistics(): HasMany
    {
        return $this->hasMany(TrafficStatistic::class);
    }

    public function alarms(): HasMany
    {
        return $this->hasMany(Alarm::class);
    }

    public function updateStatus(): void
    {
        $lastSeen = $this->last_seen_at;
        
        if (!$lastSeen || $lastSeen->diffInMinutes(now()) > 5) {
            $this->update(['status' => 'offline']);
        } else {
            $this->update(['status' => 'online']);
        }
    }

    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    public function getTotalTrafficAttribute(): int
    {
        return $this->flows()->sum('bytes');
    }
}