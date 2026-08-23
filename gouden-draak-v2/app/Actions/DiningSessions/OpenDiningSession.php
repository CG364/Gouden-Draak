<?php

namespace App\Actions\DiningSessions;

use App\Models\DiningSession;
use App\Models\Table;
use App\Models\User;
use Illuminate\Support\Str;

class OpenDiningSession
{
    /**
     * Start a new dining session for a table, generating the access token
     * that the customer's tablet uses to reach its ordering page.
     *
     * @param  array<int, int>  $guestAges
     */
    public function handle(Table $table, User $openedBy, int $guestCount, array $guestAges, bool $wantsExtraDeluxeMenu): DiningSession
    {
        return DiningSession::query()->create([
            'table_id' => $table->id,
            'opened_by' => $openedBy->id,
            'token' => Str::random(40),
            'started_at' => now(),
            'guest_count' => $guestCount,
            'guest_ages' => $guestAges,
            'wants_extra_deluxe_menu' => $wantsExtraDeluxeMenu,
        ]);
    }
}
