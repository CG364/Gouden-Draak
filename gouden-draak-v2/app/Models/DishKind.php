<?php

namespace App\Models;

use Database\Factories\DishKindFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable('name')]
#[Translatable('name')]
class DishKind extends Model
{
    /** @use HasFactory<DishKindFactory> */
    use HasFactory, HasTranslations;

    /**
     * Get the dishes belonging to this dish kind.
     *
     * @return HasMany<Dish, $this>
     */
    public function dishes(): HasMany
    {
        return $this->hasMany(Dish::class, 'dish_kind');
    }
}
