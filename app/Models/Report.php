<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Report extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'type',
        'title',
        'description',
        'data',
        'start_date',
        'end_date',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'data' => 'array',
        'start_date' => 'datetime',
        'end_date' => 'datetime',
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

    // Scopes
    public function scopeType($query, $type)
    {
        return $query->where('type', $type);
    }

    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    // Accessors
    public function getFormattedDataAttribute()
    {
        return json_encode($this->data, JSON_PRETTY_PRINT);
    }

    public function getDateRangeAttribute()
    {
        return $this->start_date->format('M d, Y') . ' - ' . $this->end_date->format('M d, Y');
    }

    public function getStatusColorAttribute()
    {
        return match($this->type) {
            'overview' => '#0284c7',
            'tank_performance' => '#059669',
            'maintenance' => '#f59e0b',
            'alerts' => '#ef4444',
            'customer' => '#8b5cf6',
            default => '#64748b'
        };
    }
} 