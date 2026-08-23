<?php

namespace App\Models;

use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable('placed_by', 'dining_session_id', 'customer_name', 'token')]
class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    /**
     * Get the user who placed this order.
     *
     * @return BelongsTo<User, $this>
     */
    public function placedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'placed_by');
    }

    /**
     * Get the dining session this order was placed during, if it was placed
     * by a customer via a tablet rather than by staff.
     *
     * @return BelongsTo<DiningSession, $this>
     */
    public function diningSession(): BelongsTo
    {
        return $this->belongsTo(DiningSession::class);
    }

    /**
     * Get the line items on this order.
     *
     * @return HasMany<OrderItem, $this>
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the total price of this order.
     */
    protected function total(): Attribute
    {
        return Attribute::get(
            fn (): float => $this->items->sum(fn (OrderItem $item): float => $item->quantity * (float) $item->unit_price)
        );
    }
}
