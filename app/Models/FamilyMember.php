<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;

class FamilyMember extends Model
{
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'firstname', 'lastname', 'email', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'id', 'users_id',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * Get the registrations for the family member.
     */
    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }

    /**
     * Get the committees that the member is a part of.
     */
    public function committees(): HasMany
    {
        return $this->hasMany(ReunionCommittee::class);
    }

    /**
     * Get the user account for the family member account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the image avatar for the family member account.
     */
    public function avatar(): HasOne
    {
        return $this->hasOne(ProfileAvatar::class);
    }

    /**
     * Get the user for the family member account.
     */
    public function full_name()
    {
        return $this->firstname . ' ' . $this->lastname;
    }

    /**
     * Get the user for the family member account.
     */
    public function full_address()
    {
        return $this->address . ', ' . $this->city . ', ' . $this->state . ' ' . $this->zip;
    }

    /**
     * Removed the blanks from an array.
     */
    public function remove_blanks($array)
    {
        $return_array = array();

        if ($array != null && count($array) > 0) {
            foreach ($array as $name) {
                if($name != 'blank') {
                    $return_array[] = $name;
                }
            }
        }

        return $return_array;
    }

    /**
     * Get searched family members
     * @param  $search
     * @param  Builder $query
     */
    public function scopeGetSearches(Builder $query, $search)
    {
        return $query->where('firstname', 'like', '%' . $search . '%')
            ->orWhere('lastname', 'like', '%' . $search . '%')
            ->orWhere('email', 'like', '%' . $search . '%')
            ->orWhere('city', 'like', '%' . $search . '%')
            ->orWhere('state', 'like', '%' . $search . '%')
            ->orWhere('zip', 'like', '%' . $search . '%')
            ->get();
    }

    /**
     * Get the user for the family member account.
     */
    public function scopeHousehold($query, $family_id)
    {
        return $query->where([
            ['family_id', $family_id],
            ['family_id', '<>', 'null']
        ])->get();
    }

    /**
     * Get the user for the family member account.
     */
    public function scopePotentialHousehold($query, $family_member)
    {
        return $query->where([
            ['address', $family_member->address],
            ['city', $family_member->city],
            ['state', $family_member->state]
        ])->get();
    }

    /**
     * Check for duplicated
     */
    public function scopeCheckDuplicates($query)
    {
        return $query->selectRaw('firstname, lastname, city, state')
            ->where('duplicate', null)
            ->groupBy('firstname')
            ->groupBy('lastname')
            ->groupBy('city')
            ->groupBy('state')
            ->havingRaw('COUNT(firstname) > 1 AND COUNT(lastname) > 1 AND COUNT(city) > 1 AND COUNT(state) > 1')
            ->get();
    }

    /**
     * Get all the duplicates that were found
     */
    public function scopeGetDuplicates($query, $firstname, $lastname, $city, $state)
    {
        return $query->where([
            ['firstname', 'LIKE', '%' . $firstname . '%'],
            ['lastname', 'LIKE', '%' . $lastname . '%'],
            ['city', 'LIKE', '%' . $city . '%'],
            ['state', 'LIKE', '%' . $state . '%'],
            ['duplicate', null],
        ]);
    }

    /**
     * Get all the duplicates that were found
     */
    public function scopeUsers($query)
    {
        return $query->where('users_id', '<>', null);
    }

}
