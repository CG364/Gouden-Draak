<?php

namespace App\Models;

use Database\Factories\TableFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('nr')]
class Table extends Model
{
    /** @use HasFactory<TableFactory> */
    use HasFactory;

    /**
     * Get the table planning entries for this table.
     *
     * @return HasMany<TablePlanning, $this>
     */
    public function tablePlannings(): HasMany
    {
        return $this->hasMany(TablePlanning::class);
    }

    /**
     * Get the dining sessions held at this table.
     *
     * @return HasMany<DiningSession, $this>
     */
    public function diningSessions(): HasMany
    {
        return $this->hasMany(DiningSession::class);
    }
}
