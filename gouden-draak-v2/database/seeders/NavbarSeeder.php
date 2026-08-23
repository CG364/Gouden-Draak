<?php

namespace Database\Seeders;

use App\Models\SiteNavbarItem;
use Illuminate\Database\Seeder;

class NavbarSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteNavbarItem::create([
            'header' => [
                'nl' => 'Home',
                'en' => 'Home',
            ],
            'foreign_url' => route('home'),
            'order' => 0,
        ]);

        SiteNavbarItem::create([
            'header' => [
                'nl' => 'Menukaart',
                'en' => 'Menu',
            ],
            'foreign_url' => route('menu'),
            'order' => 1,
        ]);

        SiteNavbarItem::create([
            'header' => [
                'nl' => 'Bestel Online',
                'en' => 'Order Online',
            ],
            'foreign_url' => route('order.create'),
            'order' => 2,
        ]);

        SiteNavbarItem::create([
            'header' => [
                'nl' => 'Contact',
                'en' => 'Contact',
            ],
            'foreign_url' => route('contact'),
            'order' => 3,
        ]);
    }
}
