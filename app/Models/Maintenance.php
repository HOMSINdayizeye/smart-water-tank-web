<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Maintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'description',
        'status',
        'team_id',
        'assigned_at',
        'completed_at',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'completed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function team()
    {
        return $this->belongsTo(Team::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('assigned_at', '<', now())
            ->where('status', '!=', 'completed');
    }

    public function scopeHighPriority($query)
    {
        return $query->where('priority', 'high');
    }

    // Accessors
    public function getIsOverdueAttribute()
    {
        return $this->assigned_at < now() && $this->status !== 'completed';
    }

    public function getDaysUntilDueAttribute()
    {
        return now()->diffInDays($this->assigned_at, false);
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'pending' => '#fef3c7',
            'in_progress' => '#dbeafe',
            'completed' => '#dcfce7',
            default => '#e2e8f0'
        };
    }

    public function getPriorityColorAttribute()
    {
        return match($this->priority) {
            'high' => '#fee2e2',
            'medium' => '#fef3c7',
            'low' => '#dcfce7',
            default => '#e2e8f0'
        };
    }
} 