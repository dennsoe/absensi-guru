<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Crypt;

class ApiKey extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'api_keys';

    protected $fillable = [
        'service_name',
        'provider',
        'api_key',
        'api_secret',
        'api_url',
        'config',
        'is_active',
        'last_used_at',
        'usage_count',
        'monthly_limit',
        'expired_at',
        'notes',
    ];

    protected $casts = [
        'config' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
        'expired_at' => 'date',
        'usage_count' => 'integer',
        'monthly_limit' => 'integer',
    ];

    protected $hidden = [
        'api_key',
        'api_secret',
    ];

    /**
     * Accessors & Mutators
     */
    public function getApiKeyAttribute($value)
    {
        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setApiKeyAttribute($value)
    {
        $this->attributes['api_key'] = Crypt::encryptString($value);
    }

    public function getApiSecretAttribute($value)
    {
        if (!$value) return null;

        try {
            return Crypt::decryptString($value);
        } catch (\Exception $e) {
            return $value;
        }
    }

    public function setApiSecretAttribute($value)
    {
        if ($value) {
            $this->attributes['api_secret'] = Crypt::encryptString($value);
        }
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeByService($query, $serviceName)
    {
        return $query->where('service_name', $serviceName);
    }

    /**
     * Helpers
     */
    public function incrementUsage()
    {
        $this->increment('usage_count');
        $this->update(['last_used_at' => now()]);
    }

    public function isExpired()
    {
        return $this->expired_at && $this->expired_at->isPast();
    }

    public function hasReachedLimit()
    {
        if (!$this->monthly_limit) {
            return false;
        }

        // Get usage count for current month
        $currentMonthUsage = $this->usage_count; // Simplified, should check monthly
        return $currentMonthUsage >= $this->monthly_limit;
    }
}
