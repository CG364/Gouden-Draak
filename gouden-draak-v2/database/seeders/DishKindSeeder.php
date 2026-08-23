<?php

namespace Database\Seeders;

use App\Models\DishKind;
use Illuminate\Database\Seeder;

class DishKindSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DishKind::create([
            'name' => [
                'nl' => 'Soep',
                'en' => 'Soup',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Voorgerecht',
                'en' => 'Starters',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Bami & Nasi gerechten',
                'en' => 'Bami & Nasi dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Combinatie gerechten (met witte rijst)',
                'en' => 'Combination dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Mihoen gerechten',
                'en' => 'Rice noodle dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Chinese bami gerechten',
                'en' => 'Chinese bami dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Indische gerechten',
                'en' => 'Indian dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Eiergerechten',
                'en' => 'Egg dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Groenten gerechten',
                'en' => 'Vegetable dishes',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Vleesgerechten (met witte rijst)',
                'en' => 'Meat dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Kipgerechten (met witte rijst)',
                'en' => 'Chicken dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Garnalen gerechten (met witte rijst)',
                'en' => 'Prawn dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Ossenhaas gerechten (met witte rijst)',
                'en' => 'Beef tenderloin dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Vissen gerechten (met witte rijst)',
                'en' => 'Fish dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Peking eend gerechten (met witte rijst)',
                'en' => 'Peking duck dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Tiepan gerechten (met witte rijst)',
                'en' => 'Tiepan dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Vegetarische gerechten (met witte rijst)',
                'en' => 'Vegetarian dishes (with white rice)',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Kindermenu\'s',
                'en' => 'Children\'s menus',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Rijsttafels',
                'en' => 'Rijsttafels',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Buffet',
                'en' => 'Buffet',
            ],
        ]);

        DishKind::create([
            'name' => [
                'nl' => 'Diversen',
                'en' => 'Miscellaneous',
            ],
        ]);
    }
}
