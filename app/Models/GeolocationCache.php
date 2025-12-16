<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GeolocationCache extends Model
{
    use HasFactory;

    protected $table = 'geolocation_cache';

    protected $fillable = [
        'ip_address',
        'country_code',
        'country_name',
        'city',
        'latitude',
        'longitude',
        'asn',
        'as_name',
        'is_private',
        'expires_at',
    ];

    protected $casts = [
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
        'asn' => 'integer',
        'is_private' => 'boolean',
        'expires_at' => 'datetime',
    ];

    /**
     * Scope to get only valid (non-expired) cache entries
     */
    public function scopeValid($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get expired entries for cleanup
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Get cached geolocation for an IP
     */
    public static function getCached(string $ip): ?self
    {
        return static::where('ip_address', $ip)
            ->valid()
            ->first();
    }

    /**
     * Store or update geolocation cache
     */
    public static function store(string $ip, array $data, int $ttlDays = 30): self
    {
        return static::updateOrCreate(
            ['ip_address' => $ip],
            array_merge($data, [
                'expires_at' => now()->addDays($ttlDays),
            ])
        );
    }

    /**
     * Clean up expired cache entries
     */
    public static function cleanup(): int
    {
        return static::expired()->delete();
    }

    /**
     * Get location as array
     */
    public function toLocationArray(): array
    {
        return [
            'country_code' => $this->country_code,
            'country_name' => $this->country_name,
            'city' => $this->city,
            'latitude' => $this->latitude,
            'longitude' => $this->longitude,
            'asn' => $this->asn,
            'as_name' => $this->as_name,
            'is_private' => $this->is_private,
        ];
    }
}
