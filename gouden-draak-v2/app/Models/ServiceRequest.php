<?php

namespace App\Models;

use Database\Factories\ServiceRequestFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable('table_id', 'dining_session_id', 'handled')]
class ServiceRequest extends Model
{
    /** @use HasFactory<ServiceRequestFactory> */
    use HasFactory;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'handled' => 'boolean',
        ];
    }

    /**
     * Scope a query to only requests that haven't been handled yet.
     */
    #[Scope]
    protected function unhandled(Builder $query): Builder
    {
        return $query->where('handled', false);
    }

    /**
     * Get the table this service request was made for.
     *
     * @return BelongsTo<Table, $this>
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the dining session this service request was made during, if any.
     *
     * @return BelongsTo<DiningSession, $this>
     */
    public function diningSession(): BelongsTo
    {
        return $this->belongsTo(DiningSession::class);
    }
}
