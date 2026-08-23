<?php

namespace Database\Seeders;

use App\Models\Staff;
use App\Models\Table;
use App\Models\TablePlanning;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $table = Table::query()->create([
                'nr' => $i
            ]);

            $staff = Staff::factory()->create();

            // Seed table service
            TablePlanning::query()->create([
                'table_id' => $table->id,
                'staff_id' => $staff->id,
                'start' => Carbon::now(),
                'end' => Carbon::now()->addWeek()
            ]);
        }
    }
}
