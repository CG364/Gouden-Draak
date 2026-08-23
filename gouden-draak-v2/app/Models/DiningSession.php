<?php

namespace App\Models;

use Database\Factories\DiningSessionFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

#[Fillable('table_id', 'opened_by', 'token', 'started_at', 'ended_at', 'guest_count', 'guest_ages', 'wants_extra_deluxe_menu')]
class DiningSession extends Model
{
    /** @use HasFactory<DiningSessionFactory> */
    use HasFactory;

    /**
     * The maximum number of orders (rounds) a dining session may place.
     */
    public const int MAX_ORDERS = 5;

    /**
     * The mandatory number of minutes between orders within a session.
     */
    public const int ORDER_COOLDOWN_MINUTES = 10;

    /**
     * The maximum number of guests a table's dining session may register.
     */
    public const int MAX_GUESTS = 8;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'started_at' => 'datetime',
            'ended_at' => 'datetime',
            'guest_ages' => 'array',
            'wants_extra_deluxe_menu' => 'boolean',
        ];
    }

    /**
     * Use the session's access token for route model binding, so both the
     * tablet's own URL and any links to it never expose the numeric ID.
     */
    public function getRouteKeyName(): string
    {
        return 'token';
    }

    /**
     * Scope a query to only sessions that haven't been closed yet.
     */
    #[Scope]
    protected function active(Builder $query): Builder
    {
        return $query->whereNull('ended_at');
    }

    /**
     * Get the table this dining session is seated at.
     *
     * @return BelongsTo<Table, $this>
     */
    public function table(): BelongsTo
    {
        return $this->belongsTo(Table::class);
    }

    /**
     * Get the staff member (user) who opened this dining session.
     *
     * @return BelongsTo<User, $this>
     */
    public function openedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'opened_by');
    }

    /**
     * Get the orders placed during this dining session.
     *
     * @return HasMany<Order, $this>
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the waiter-call requests made during this dining session.
     *
     * @return HasMany<ServiceRequest, $this>
     */
    public function serviceRequests(): HasMany
    {
        return $this->hasMany(ServiceRequest::class);
    }

    /**
     * Determine whether this session already has an unhandled waiter call,
     * so the tablet doesn't flood the waiter's list with repeat taps.
     */
    protected function hasPendingServiceRequest(): Attribute
    {
        return Attribute::get(fn (): bool => $this->serviceRequests()->unhandled()->exists());
    }

    /**
     * Get the number of rounds still available in this session.
     */
    protected function roundsRemaining(): Attribute
    {
        return Attribute::get(fn (): int => max(0, self::MAX_ORDERS - $this->orders()->count()));
    }

    /**
     * Get the moment at which the next order may be placed, or null if one can be placed right now.
     */
    protected function nextOrderAvailableAt(): Attribute
    {
        return Attribute::get(function (): ?Carbon {
            $lastOrderedAt = $this->orders()->latest('created_at')->value('created_at');

            if ($lastOrderedAt === null) {
                return null;
            }

            $availableAt = $lastOrderedAt->addMinutes(self::ORDER_COOLDOWN_MINUTES);

            return $availableAt->isFuture() ? $availableAt : null;
        });
    }

    /**
     * Determine whether a new order can be placed right now.
     */
    protected function canPlaceOrder(): Attribute
    {
        return Attribute::get(fn (): bool => $this->ended_at === null
            && $this->rounds_remaining > 0
            && $this->next_order_available_at === null);
    }
}
