<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class ReunionEvent extends Model
{
    use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Set the event date.
     *
     * @param string $value
     * @return void
     */
    public function setEventDateAttribute($value)
    {
        $formatted_date = Carbon::createFromFormat('m-d-Y', $value);

        $this->attributes['event_date'] = $formatted_date->toDateString();
    }

    /**
     * Get the formatted date.
     */
    public function formatted_date()
    {
        $newDate = new Carbon($this->event_date);

        return $newDate->format('m/d/Y');
    }
}
