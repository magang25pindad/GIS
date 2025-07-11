<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'ip',
        'status',
        'latitude',
        'longitude',
        'last_ping',
        'uptime',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the status color for the device.
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'online' => 'green',
            'offline' => 'red',
            'partial' => 'yellow',
            default => 'gray',
        };
    }

    /**
     * Get the formatted coordinates.
     */
    public function getFormattedCoordinatesAttribute()
    {
        return number_format($this->latitude, 6) . ', ' . number_format($this->longitude, 6);
    }

    /**
     * Scope a query to only include online devices.
     */
    public function scopeOnline($query)
    {
        return $query->where('status', 'online');
    }

    /**
     * Scope a query to only include offline devices.
     */
    public function scopeOffline($query)
    {
        return $query->where('status', 'offline');
    }

    /**
     * Scope a query to only include partial devices.
     */
    public function scopePartial($query)
    {
        return $query->where('status', 'partial');
    }
}