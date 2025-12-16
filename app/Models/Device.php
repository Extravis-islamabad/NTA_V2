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
        // SSH fields
        'ssh_host',
        'ssh_port',
        'ssh_username',
        'ssh_password',
        'ssh_private_key',
        'ssh_enabled',
        'last_ssh_connection',
        'ssh_connection_status',
        // SNMP fields
        'snmp_enabled',
        'snmp_version',
        'snmp_port',
        'snmp_community',
        'snmp_username',
        'snmp_security_level',
        'snmp_auth_protocol',
        'snmp_auth_password',
        'snmp_priv_protocol',
        'snmp_priv_password',
        'snmp_poll_interval',
        'last_snmp_poll',
        'snmp_connection_status',
        'snmp_sys_name',
        'snmp_sys_descr',
        'snmp_sys_location',
        'snmp_sys_contact',
        'snmp_sys_uptime'
    ];

    protected $casts = [
        'metadata' => 'array',
        'last_seen_at' => 'datetime',
        'last_ssh_connection' => 'datetime',
        'ssh_enabled' => 'boolean',
        'ssh_port' => 'integer',
        // SNMP casts
        'snmp_enabled' => 'boolean',
        'snmp_port' => 'integer',
        'snmp_poll_interval' => 'integer',
        'last_snmp_poll' => 'datetime',
        'snmp_sys_uptime' => 'integer',
    ];

    protected $hidden = [
        'ssh_password',
        'ssh_private_key',
        'snmp_community',
        'snmp_auth_password',
        'snmp_priv_password',
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

    // Encrypt SNMP community string when setting
    public function setSnmpCommunityAttribute($value)
    {
        if ($value) {
            $this->attributes['snmp_community'] = Crypt::encryptString($value);
        } else {
            $this->attributes['snmp_community'] = null;
        }
    }

    // Decrypt SNMP community string when getting
    public function getSnmpCommunityAttribute($value)
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

    // Encrypt SNMP auth password when setting
    public function setSnmpAuthPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['snmp_auth_password'] = Crypt::encryptString($value);
        } else {
            $this->attributes['snmp_auth_password'] = null;
        }
    }

    // Decrypt SNMP auth password when getting
    public function getSnmpAuthPasswordAttribute($value)
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

    // Encrypt SNMP priv password when setting
    public function setSnmpPrivPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['snmp_priv_password'] = Crypt::encryptString($value);
        } else {
            $this->attributes['snmp_priv_password'] = null;
        }
    }

    // Decrypt SNMP priv password when getting
    public function getSnmpPrivPasswordAttribute($value)
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

    /**
     * Get interface count from relationships (computed accessor)
     */
    public function getInterfaceCountAttribute($value): int
    {
        // If value is stored and valid, use it; otherwise compute from relationship
        if ($value !== null && $value > 0) {
            return $value;
        }
        return $this->interfaces()->count();
    }

    /**
     * Get flow count from relationships (computed accessor)
     */
    public function getFlowCountAttribute($value): int
    {
        // If value is stored and valid, use it; otherwise compute from relationship
        if ($value !== null && $value > 0) {
            return $value;
        }
        return $this->flows()->count();
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

    // SNMP Helper Methods
    public function scopeSnmpEnabled($query)
    {
        return $query->where('snmp_enabled', true);
    }

    public function hasSnmpCredentials(): bool
    {
        if (!$this->snmp_enabled) {
            return false;
        }

        if ($this->snmp_version === 'v3') {
            return !empty($this->snmp_username);
        }

        return !empty($this->snmp_community);
    }

    public function getFormattedUptimeAttribute(): ?string
    {
        if (!$this->snmp_sys_uptime) {
            return null;
        }

        $seconds = $this->snmp_sys_uptime;
        $days = floor($seconds / 86400);
        $hours = floor(($seconds % 86400) / 3600);
        $minutes = floor(($seconds % 3600) / 60);

        $parts = [];
        if ($days > 0) $parts[] = "{$days}d";
        if ($hours > 0) $parts[] = "{$hours}h";
        if ($minutes > 0) $parts[] = "{$minutes}m";

        return implode(' ', $parts) ?: '0m';
    }

    public function needsSnmpPoll(): bool
    {
        if (!$this->snmp_enabled || !$this->hasSnmpCredentials()) {
            return false;
        }

        if (!$this->last_snmp_poll) {
            return true;
        }

        return $this->last_snmp_poll->addSeconds($this->snmp_poll_interval)->isPast();
    }
}
