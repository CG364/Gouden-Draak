<?php

namespace App\Actions\Dishes;

use App\Models\Dish;
use Illuminate\Pagination\LengthAwarePaginator;

class ListDishesPaginated
{
    /**
     * List dishes for the admin dish index, naturally sorted by menu number.
     *
     * Sorting happens in PHP rather than SQL because natural ordering (so
     * formats like "2", "10" and "58A" sort in human reading order) isn't
     * portable across database drivers, so the collection is paginated
     * manually instead of via the query builder.
     */
    public function handle(int $perPage = 15): LengthAwarePaginator
    {
        $dishes = Dish::sortByMenuNumber(Dish::query()->with('dishKind')->get());
        $page = LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $dishes->forPage($page, $perPage)->values(),
            $dishes->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }
}
