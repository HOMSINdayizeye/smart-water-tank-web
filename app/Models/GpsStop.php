<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GpsStop extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'route_id',
        'name',
        'address',
        'latitude',
        'longitude',
        'sequence',
        'estimated_arrival',
        'estimated_departure',
        'status',
        'notes'
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
        'sequence' => 'integer',
        'estimated_arrival' => 'datetime',
        'estimated_departure' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function route()
    {
        return $this->belongsTo(GpsRoute::class, 'route_id');
    }

    // Scopes
    public function scopeSequence($query, $sequence)
    {
        return $query->where('sequence', $sequence);
    }

    public function scopeStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Accessors
    public function getCoordinatesAttribute()
    {
        return [
            'lat' => $this->latitude,
            'lng' => $this->longitude
        ];
    }

    public function getFormattedArrivalAttribute()
    {
        return $this->estimated_arrival ? $this->estimated_arrival->format('H:i') : 'N/A';
    }

    public function getFormattedDepartureAttribute()
    {
        return $this->estimated_departure ? $this->estimated_departure->format('H:i') : 'N/A';
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => '#f59e0b',
            'arrived' => '#059669',
            'departed' => '#0284c7',
            'skipped' => '#dc2626',
            default => '#64748b'
        };
    }
} 