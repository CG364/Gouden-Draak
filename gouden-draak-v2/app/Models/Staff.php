<?php

namespace App\Models;

use Database\Factories\StaffFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('first_name', 'last_name')]
class Staff extends Model
{
    /** @use HasFactory<StaffFactory> */
    use HasFactory;

    /**
     * Get the table planning entries assigned to this staff member.
     *
     * @return HasMany<TablePlanning, $this>
     */
    public function tablePlannings(): HasMany
    {
        return $this->hasMany(TablePlanning::class);
    }
}
