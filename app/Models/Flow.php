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
        'dscp',
        // Interface fields
        'input_interface',
        'output_interface',
        // Geolocation fields
        'src_country_code',
        'src_country_name',
        'src_city',
        'src_latitude',
        'src_longitude',
        'src_asn',
        'dst_country_code',
        'dst_country_name',
        'dst_city',
        'dst_latitude',
        'dst_longitude',
        'dst_asn',
        'app_category',
    ];

    protected $casts = [
        'bytes' => 'integer',
        'packets' => 'integer',
        'first_switched' => 'datetime',
        'last_switched' => 'datetime',
        'input_interface' => 'integer',
        'output_interface' => 'integer',
        'src_latitude' => 'decimal:7',
        'src_longitude' => 'decimal:7',
        'dst_latitude' => 'decimal:7',
        'dst_longitude' => 'decimal:7',
        'src_asn' => 'integer',
        'dst_asn' => 'integer',
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

    public function scopeByCategory($query, string $category)
    {
        return $query->where('app_category', $category);
    }

    public function scopeByCountry($query, string $countryCode, string $direction = 'dst')
    {
        $column = $direction === 'src' ? 'src_country_code' : 'dst_country_code';
        return $query->where($column, $countryCode);
    }

    public function scopeWithGeo($query)
    {
        return $query->whereNotNull('dst_country_code');
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