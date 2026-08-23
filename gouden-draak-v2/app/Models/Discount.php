<?php

namespace App\Models;

use Database\Factories\DiscountFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

#[Fillable('starts_at', 'ends_at')]
class Discount extends Model
{
    /** @use HasFactory<DiscountFactory> */
    use HasFactory;

    /**
     * Scope a query to only discounts that are currently active.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->where('starts_at', '<=', now())->where('ends_at', '>=', now());
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
        ];
    }

    /**
     * Get the dishes included in this discount.
     *
     * @return BelongsToMany<Dish, $this>
     */
    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class)->withPivot('discounted_price');
    }
}
