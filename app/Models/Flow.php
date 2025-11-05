<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Flow extends Model
{
    use HasFactory;

    protected $fillable = [
        'device_id',
        'source_ip',
        'destination_ip',
        'source_port',
        'destination_port',
        'protocol',
        'bytes',
        'packets',
        'first_switched',
        'last_switched',
        'application',
        'dscp'
    ];

    protected $casts = [
        'bytes' => 'integer',
        'packets' => 'integer',
        'first_switched' => 'datetime',
        'last_switched' => 'datetime',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(Device::class);
    }

    public function scopeByProtocol($query, string $protocol)
    {
        return $query->where('protocol', $protocol);
    }

    public function scopeByTimeRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }

    public function scopeByApplication($query, string $application)
    {
        return $query->where('application', $application);
    }

    public function getFormattedBytesAttribute(): string
    {
        $bytes = $this->bytes;
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