<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Page::create([
            'slug' => 'openingstijden',
            'title' => [
                'nl' => 'Openingstijden',
                'en' => 'Opening hours',
            ],
            'content' => [
                'nl' => '<p>Wij zijn dagelijks geopend van 12:00 tot 21:30.</p>',
                'en' => '<p>We are open daily from 12:00 to 21:30.</p>',
            ],
        ]);

        Page::create([
            'slug' => 'nieuws',
            'title' => [
                'nl' => 'Nieuws',
                'en' => 'News'
            ],
            'content' => [
                'nl' => '<h3>Door de Corona crisis is De Gouden Draak op het moment slechts beperkt open.<br>Het restaurant-gedeelte is gesloten. U kan uw favoriete gerechten nog wel afhalen.</h3>',
                'en' => '<h3>Due to the Corona crisis, De Gouden Draak is currently only open on a limited basis.<br>The restaurant section is closed. You can still pick up your favorite dishes.</h3>'
            ]
        ]);
    }
}
