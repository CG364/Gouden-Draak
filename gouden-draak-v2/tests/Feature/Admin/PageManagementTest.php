<?php

use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');
});

test('guests cannot access page management', function () {
    $this->get(route('admin.pages.index'))->assertRedirect('/admin/login');
});

test('an admin can view the pages index', function () {
    $page = Page::factory()->create();

    $this->actingAs($this->admin)
        ->get(route('admin.pages.index'))
        ->assertSuccessful()
        ->assertSee($page->slug);
});

test('an admin can create a page', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.pages.store'), [
        'slug' => 'about-us',
        'title' => ['nl' => 'Over ons', 'en' => 'About us'],
        'content' => ['nl' => '<p>Hallo</p>', 'en' => '<p>Hello</p>'],
    ]);

    $response->assertRedirect(route('admin.pages.index'));

    $page = Page::query()->where('slug', 'about-us')->firstOrFail();
    expect($page->getTranslation('title', 'en', false))->toBe('About us');
    expect($page->getTranslation('content', 'nl', false))->toBe('<p>Hallo</p>');
});

test('creating a page requires a translation for every locale', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.pages.store'), [
        'slug' => 'about-us',
        'title' => ['nl' => 'Over ons'],
        'content' => ['nl' => '<p>Hallo</p>'],
    ]);

    $response->assertInvalid(['title.en', 'content.en']);
});

test('an admin can update a page', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($this->admin)->put(route('admin.pages.update', $page), [
        'slug' => $page->slug,
        'title' => ['nl' => 'Bijgewerkt', 'en' => 'Updated'],
        'content' => ['nl' => '<p>Bijgewerkt</p>', 'en' => '<p>Updated</p>'],
    ]);

    $response->assertRedirect(route('admin.pages.index'));
    expect($page->fresh()->getTranslation('title', 'en', false))->toBe('Updated');
});

test('an admin can delete a page', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.pages.destroy', $page));

    $response->assertRedirect(route('admin.pages.index'));
    $this->assertDatabaseMissing('pages', ['id' => $page->id]);
});
