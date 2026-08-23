<?php

namespace App\Actions\DiningSessions;

use App\Models\Table;
use Illuminate\Database\Eloquent\Collection;

class ListAvailableTables
{
    /**
     * List tables that don't currently have an active dining session, so a
     * waiter can only start a new session at a table that's actually free.
     *
     * @return Collection<int, Table>
     */
    public function handle(): Collection
    {
        return Table::query()
            ->whereDoesntHave('diningSessions', fn ($query) => $query->active())
            ->orderBy('nr')
            ->get();
    }
}
