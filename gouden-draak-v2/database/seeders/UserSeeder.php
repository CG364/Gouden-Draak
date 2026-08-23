<?php

namespace Database\Seeders;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminUser = User::factory()->create([
            "name" => "Admin",
            "email" => "admin@example.com",
            "password" => Hash::make('pwd'),
            "email_verified_at" => Carbon::now(),
        ]);
        $adminUser->assignRole('admin');

        $waiterUser = User::factory()->create([
            "name" => "Waiter",
            "email" => "waiter@example.com",
            "password" => Hash::make('pwd'),
            "email_verified_at" => Carbon::now(),
        ]);
        $waiterUser->assignRole('waiter');

        $cashierUser = User::factory()->create([
            "name" => "Cashier",
            "email" => "cashier@example.com",
            "password" => Hash::make('pwd'),
            "email_verified_at" => Carbon::now(),
        ]);
        $cashierUser->assignRole('cashier');
    }
}
