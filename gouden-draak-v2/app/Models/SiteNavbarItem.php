<?php

namespace App\Models;

use Database\Factories\SiteNavbarItemFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\Attributes\Translatable;
use Spatie\Translatable\HasTranslations;

#[Fillable('header', 'page_id', 'order', 'foreign_url')]
#[Translatable('header')]
class SiteNavbarItem extends Model
{
    /** @use HasFactory<SiteNavbarItemFactory> */
    use HasFactory, HasTranslations;

    /**
     * Get the database page this navbar item links to, if any.
     *
     * @return BelongsTo<Page, $this>
     */
    public function page(): BelongsTo
    {
        return $this->belongsTo(Page::class);
    }

    /**
     * Get the URL this navbar item links to.
     */
    protected function url(): Attribute
    {
        return Attribute::get(fn (): string => $this->page_id !== null
            ? route('pages.show', $this->page)
            : (string) $this->foreign_url);
    }
}
