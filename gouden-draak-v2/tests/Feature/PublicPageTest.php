<?php

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('a page can be viewed on the public site by its slug', function () {
    $page = Page::factory()->create([
        'slug' => 'openingstijden',
        'title' => ['nl' => 'Openingstijden', 'en' => 'Opening hours'],
        'content' => ['nl' => '<p>Wij zijn open</p>', 'en' => '<p>We are open</p>'],
    ]);

    $this->withUnencryptedCookie('locale', 'nl')
        ->get('/pages/openingstijden')
        ->assertSuccessful()
        ->assertSee('Openingstijden')
        ->assertSee('Wij zijn open', false);
});

test('an unknown page slug returns a 404', function () {
    $this->get('/pages/does-not-exist')->assertNotFound();
});
