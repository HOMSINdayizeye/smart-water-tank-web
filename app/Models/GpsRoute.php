<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GpsRoute extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'start_location',
        'end_location',
        'waypoints',
        'optimization_type',
        'vehicle_type',
        'total_distance',
        'estimated_time',
        'fuel_cost',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'waypoints' => 'array',
        'total_distance' => 'float',
        'estimated_time' => 'integer',
        'fuel_cost' => 'float',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function stops()
    {
        return $this->hasMany(GpsStop::class, 'route_id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOptimizationType($query, $type)
    {
        return $query->where('optimization_type', $type);
    }

    public function scopeVehicleType($query, $type)
    {
        return $query->where('vehicle_type', $type);
    }

    // Accessors
    public function getFormattedDistanceAttribute()
    {
        return number_format($this->total_distance, 2) . ' km';
    }

    public function getFormattedTimeAttribute()
    {
        $hours = floor($this->estimated_time / 60);
        $minutes = $this->estimated_time % 60;
        return sprintf('%02d:%02d', $hours, $minutes);
    }

    public function getFormattedFuelCostAttribute()
    {
        return '$' . number_format($this->fuel_cost, 2);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'active' => '#059669',
            'completed' => '#0284c7',
            'cancelled' => '#dc2626',
            default => '#64748b'
        };
    }
} 