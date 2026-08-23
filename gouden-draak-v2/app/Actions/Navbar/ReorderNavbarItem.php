<?php

namespace App\Actions\Navbar;

use App\Models\SiteNavbarItem;
use Illuminate\Support\Facades\DB;

class ReorderNavbarItem
{
    /**
     * Swap a navbar item's position with its neighbor, moving it one step
     * earlier or later in the nav.
     */
    public function handle(SiteNavbarItem $navbarItem, string $direction): void
    {
        $neighbor = $direction === 'up'
            ? SiteNavbarItem::query()->where('order', '<', $navbarItem->order)->orderByDesc('order')->first()
            : SiteNavbarItem::query()->where('order', '>', $navbarItem->order)->orderBy('order')->first();

        if ($neighbor === null) {
            return;
        }

        DB::transaction(function () use ($navbarItem, $neighbor): void {
            $navbarItemOrder = $navbarItem->order;
            $neighborOrder = $neighbor->order;

            // A direct swap would momentarily give two rows the same value,
            // tripping the `order` column's unique constraint, so free up
            // the slot via a temporary value first.
            $navbarItem->update(['order' => -1]);
            $neighbor->update(['order' => $navbarItemOrder]);
            $navbarItem->update(['order' => $neighborOrder]);
        });
    }
}
