<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tank extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'location',
        'capacity',
        'current_level',
        'ph_level',
        'chloride_level',
        'fluoride_level',
        'nitrate_level',
        'status',
        'last_maintenance',
    ];

    protected $casts = [
        'last_maintenance' => 'datetime',
    ];

    /**
     * Get the user that owns the tank
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the water quality status
     */
    public function getWaterQualityAttribute()
    {
        if ($this->ph_level < 5 || $this->ph_level > 10 || 
            $this->chloride_level > 750 || 
            $this->fluoride_level > 4 || 
            $this->nitrate_level > 150) {
            return 'unsafe';
        } elseif (($this->ph_level >= 5 && $this->ph_level < 6) || 
                 ($this->ph_level > 9 && $this->ph_level <= 10) || 
                 ($this->chloride_level >= 500 && $this->chloride_level <= 750) || 
                 ($this->fluoride_level >= 3 && $this->fluoride_level <= 4) || 
                 ($this->nitrate_level >= 100 && $this->nitrate_level <= 150)) {
            return 'moderate_risk';
        } elseif (($this->ph_level >= 6 && $this->ph_level <= 6.5) || 
                 ($this->ph_level >= 8.5 && $this->ph_level <= 9) || 
                 ($this->chloride_level >= 250 && $this->chloride_level < 500) || 
                 ($this->fluoride_level >= 1.5 && $this->fluoride_level < 3) || 
                 ($this->nitrate_level >= 50 && $this->nitrate_level < 100)) {
            return 'moderate';
        } else {
            return 'safe';
        }
    }
}