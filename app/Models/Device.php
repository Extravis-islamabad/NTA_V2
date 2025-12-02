<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Crypt;

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
        'last_seen_at',
        'ssh_host',
        'ssh_port',
        'ssh_username',
        'ssh_password',
        'ssh_private_key',
        'ssh_enabled',
        'last_ssh_connection',
        'ssh_connection_status'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_seen_at' => 'datetime',
        'last_ssh_connection' => 'datetime',
        'ssh_enabled' => 'boolean',
        'ssh_port' => 'integer',
    ];

    protected $hidden = [
        'ssh_password',
        'ssh_private_key',
    ];

    // Encrypt SSH password when setting
    public function setSshPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['ssh_password'] = Crypt::encryptString($value);
        } else {
            $this->attributes['ssh_password'] = null;
        }
    }

    // Decrypt SSH password when getting
    public function getSshPasswordAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

    // Encrypt SSH private key when setting
    public function setSshPrivateKeyAttribute($value)
    {
        if ($value) {
            $this->attributes['ssh_private_key'] = Crypt::encryptString($value);
        } else {
            $this->attributes['ssh_private_key'] = null;
        }
    }

    // Decrypt SSH private key when getting
    public function getSshPrivateKeyAttribute($value)
    {
        if ($value) {
            try {
                return Crypt::decryptString($value);
            } catch (\Exception $e) {
                return null;
            }
        }
        return null;
    }

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

    public function scopeSshEnabled($query)
    {
        return $query->where('ssh_enabled', true);
    }

    public function getTotalTrafficAttribute(): int
    {
        return $this->flows()->sum('bytes');
    }

    public function hasSshCredentials(): bool
    {
        return $this->ssh_enabled &&
               $this->ssh_username &&
               ($this->ssh_password || $this->ssh_private_key);
    }

    public function getSshHostAddress(): string
    {
        return $this->ssh_host ?: $this->ip_address;
    }
}
