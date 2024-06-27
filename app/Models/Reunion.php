<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Reunion extends Model
{
    /**
     * Get the hotel for the reunion.
     */
    public function hotel(): HasOne
    {
        return $this->hasOne(ReunionHotel::class);
    }

	/**
     * Get the committee members for the reunion.
     */
    public function committee(): HasMany
    {
        return $this->hasMany(ReunionCommittee::class);
    }

	/**
     * Get the committee members for the reunion.
     */
    public function events(): HasMany
    {
        return $this->hasMany(ReunionEvent::class);
    }

	/**
     * Get the registered members for the reunion.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class, 'reunion_id')->parents();
    }

	/**
     * Get the registered members for the reunion.
     */
    public function images(): HasMany
    {
        return $this->hasMany(ReunionImage::class);
    }

    public function scopeActive($query) {
		return $query->where('reunion_complete', 'N')->get();
	}

}
