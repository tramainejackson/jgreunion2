<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\Storage;

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
        return $this->hasMany(Registration::class);
    }

	/**
     * Get the imagaes for the reunion.
     */
    public function images(): HasMany
    {
        return $this->hasMany(Images::class);
    }

    /**
     * Get the user for the family member account.
     * @return string
     */
    public function get_downloadable_registration()
    {
        if ($this->registration_form != null) {
            if (Storage::disk('local')->exists('public/reg_forms/' . str_ireplace('public/reg_forms/', '', $this->registration_form))) {
                $this->registration_form = asset('storage/reg_forms/' . str_ireplace('public/reg_forms/', '',  $this->registration_form));
            }
        }

        return $this->registration_form;
    }

    public function scopeActive($query) {
		return $query->where('reunion_complete', 'N')->get();
	}

}
