<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'status',
        'created_by',
        'updated_by'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the tanks associated with the customer.
     */
    public function tanks(): HasMany
    {
        return $this->hasMany(Tank::class);
    }

    /**
     * Get the maintenance records for the customer.
     */
    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class);
    }

    /**
     * Get the alerts for the customer.
     */
    public function alerts(): HasMany
    {
        return $this->hasMany(Alert::class);
    }

    /**
     * Get the user who created the customer.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the customer.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Scope a query to only include active customers.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include inactive customers.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope a query to only include pending customers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Get the customer's full address.
     */
    public function getFullAddressAttribute(): string
    {
        return $this->address;
    }

    /**
     * Get the customer's active tanks count.
     */
    public function getActiveTanksCountAttribute(): int
    {
        return $this->tanks()->where('status', 'active')->count();
    }

    /**
     * Get the customer's total tanks count.
     */
    public function getTanksCountAttribute(): int
    {
        return $this->tanks()->count();
    }

    /**
     * Get the customer's recent maintenance records.
     */
    public function getRecentMaintenanceAttribute()
    {
        return $this->maintenanceRecords()
            ->latest()
            ->take(5)
            ->get();
    }

    /**
     * Get the customer's recent alerts.
     */
    public function getRecentAlertsAttribute()
    {
        return $this->alerts()
            ->latest()
            ->take(5)
            ->get();
    }
} 