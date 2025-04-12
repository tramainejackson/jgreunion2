<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReunionCommittee extends Model
{
    /**
     * Get the distribution list member for the committee member.
     */
    public function family_member(): BelongsTo
    {
        return $this->belongsTo(FamilyMember::class);
    }

	/**
     * Get the reunion for the committee member.
     */
    public function reunion(): BelongsTo
    {
        return $this->belongsTo(Reunion::class);
    }

	/**
     * Get the president for the committee member.
     */
    public function scopePresident($query)
    {
        return $query->where('member_title', 'president')->first();
    }
}
