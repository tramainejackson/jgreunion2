<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReunionHotel extends Model
{
    /**
     * Get the features for the hotel.
     */
    public function hotel()
    {
        return $this->belongsTo('App\ReunionHotel');
    }
}
