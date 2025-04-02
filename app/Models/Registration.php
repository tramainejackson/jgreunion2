<?php

namespace App\Models;

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
    public function reunion_dl(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class, 'family_id');
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
     * Get only the parent registration.
    */
    public function scopeParents($query)
    {
        return $query->where('parent_registration_id', null);
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
			['family_members_id', $member_id],
			['reunion_id', $reunion_id],
		]);
    }
}
