<?php

namespace App\Models;

use App\Models\AreaCodes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class Registration extends Model
{
	use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the activities for the trip.
     */
    public function family_member(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

	/**
     * Get the activities for the trip.
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

	/**
     * Get the additional members on this registration.
    */
    public function children_reg(): HasMany
    {
        return $this->hasMany(Registration::class, 'parent_registration_id');
    }

    /**
     * Get the full name formatted.
     */
    public function full_name()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    /**
     * Get a formatted and cleaned version of the phone number.
     */
    public function phone_number($requested_number)
    {
        preg_match('~.*(\d{3})[^\d]{0,7}(\d{3})[^\d]{0,7}(\d{4}).*~', $requested_number, $matches);
        $cleaned_number = $matches[1] . '-' .$matches[2] . '-' . $matches[3];;

        if(in_array($matches[1], AreaCodes::all_codes())){
            return $cleaned_number;
        } else {
            return false;
        }
    }

    /**
     * Format the shirt sized for the youth shirts.
     */
    public function youth_shirts_formatted($size_to_format)
    {
        if($size_to_format == 'XS') {
            $size_to_format = 'Youth XS';
        } elseif($size_to_format == 'S') {
            $size_to_format = 'Youth Small';
        } elseif($size_to_format == 'M') {
            $size_to_format = 'Youth Medium';
        } elseif($size_to_format == 'L') {
            $size_to_format = 'Youth Large';
        } elseif($size_to_format == 'XL') {
            $size_to_format = 'Adult Small';
        } elseif($size_to_format == 'XXL') {
           $size_to_format = 'Adult Medium';
        }

        return $size_to_format;
    }

    /**
     * Format the shirt sized for the youth shirts.
     */
    public function children_shirts_formatted($size_to_format)
    {
        if($size_to_format == 'S') {
            $size_to_format = '12 Months';
        } elseif($size_to_format == 'M') {
            $size_to_format = '2T';
        } elseif($size_to_format == 'L') {
            $size_to_format = '3T';
        } elseif($size_to_format == 'XL') {
            $size_to_format = '4T';
        } elseif($size_to_format == 'XXL') {
            $size_to_format = '5T';
        } elseif($size_to_format == 'XXXL') {
            $size_to_format = '6T';
        }

        return $size_to_format;
    }

	/**
     * Get only the parent registration.
    */
    public function scopeParents($query)
    {
        return $query->where('parent_registration_id', null)
            ->get();
    }

	/**
     * Get the total cost of all the registrations.
    */
    public function scopeTotalRegFees($query)
    {
        return $query->select((DB::raw('SUM(due_at_reg) as totalRegFees')))->first()->totalRegFees;
    }

	/**
     * Get the total of all the registration fees paid.
    */
    public function scopeTotalRegFeesPaid($query)
    {
        return $query->select((DB::raw('SUM(total_amount_paid) as totalRegFeesPaid')))->first()->totalRegFeesPaid;
    }

	/**
     * Get the total of all the registration fees left to be paid.
    */
    public function scopeTotalRegFeesDue($query)
    {
        return $query->select((DB::raw('SUM(total_amount_due) as totalRegFeesDue')))
			->first()
			->totalRegFeesDue;
    }

	/**
     * Get the total of all the registration fees left to be paid.
    */
    public function scopeMemberRegistered($query, $member_id, $reunion_id)
    {
        return $query->where([
			['family_member_id', $member_id],
			['reunion_id', $reunion_id],
		]);
    }
}
