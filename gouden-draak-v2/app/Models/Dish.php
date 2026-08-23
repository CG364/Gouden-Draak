<?php

namespace App\Models;

use Database\Factories\DishFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Collection;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable('menu_number', 'name', 'description', 'dish_kind', 'price')]
#[Translatable('name', 'description')]
class Dish extends Model
{
    /** @use HasFactory<DishFactory> */
    use HasFactory, HasTranslations;

    /**
     * Sort a collection of dishes by menu number using natural ordering, so
     * formats like "2", "10" and "58A" sort in human reading order rather
     * than lexicographically.
     *
     * @param  Collection<int, Dish>  $dishes
     * @return Collection<int, Dish>
     */
    public static function sortByMenuNumber(Collection $dishes): Collection
    {
        return $dishes->sortBy('menu_number', SORT_NATURAL)->values();
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
        ];
    }

    /**
     * Get the dish kind this dish belongs to.
     *
     * @return BelongsTo<DishKind, $this>
     */
    public function dishKind(): BelongsTo
    {
        return $this->belongsTo(DishKind::class, 'dish_kind');
    }

    /**
     * Get the discounts that apply to this dish.
     *
     * @return BelongsToMany<Discount, $this>
     */
    public function discounts(): BelongsToMany
    {
        return $this->belongsToMany(Discount::class)->withPivot('discounted_price');
    }
}
