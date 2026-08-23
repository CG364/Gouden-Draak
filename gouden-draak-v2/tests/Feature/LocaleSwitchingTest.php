<?php

test('switching to a supported locale sets a cookie and redirects back', function () {
    $response = $this->from('/menu')->get('/lang/en');

    $response->assertRedirect('/menu');
    $response->assertPlainCookie('locale', 'en');
});

test('switching to an unsupported locale returns a 404', function () {
    $this->get('/lang/fr')->assertNotFound();
});

test('the locale cookie determines which language static site text is rendered in', function () {
    $this->withUnencryptedCookie('locale', 'en')
        ->get('/')
        ->assertSee('Welcome to De Gouden Draak', false);

    $this->withUnencryptedCookie('locale', 'nl')
        ->get('/')
        ->assertSee('Welkom bij De Gouden Draak', false);
});
