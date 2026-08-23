<?php

use App\Models\Page;
use App\Models\SiteNavbarItem;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;

uses(RefreshDatabase::class);

beforeEach(function () {
    Role::create(['name' => 'admin']);
    Role::create(['name' => 'cashier']);
    Role::create(['name' => 'waiter']);

    $this->admin = User::factory()->create();
    $this->admin->assignRole('admin');

    $this->cashier = User::factory()->create();
    $this->cashier->assignRole('cashier');

    $this->waiter = User::factory()->create();
    $this->waiter->assignRole('waiter');
});

test('guests cannot access navbar management', function () {
    $this->get(route('admin.navbar-items.index'))->assertRedirect('/admin/login');
});

test('a cashier cannot access navbar management', function () {
    $this->actingAs($this->cashier)
        ->get(route('admin.navbar-items.index'))
        ->assertForbidden();
});

test('a waiter cannot access navbar management', function () {
    $this->actingAs($this->waiter)
        ->get(route('admin.navbar-items.index'))
        ->assertForbidden();
});

test('an admin can view the navbar items in order', function () {
    SiteNavbarItem::factory()->create(['header' => ['nl' => 'Tweede', 'en' => 'Second'], 'order' => 1]);
    SiteNavbarItem::factory()->create(['header' => ['nl' => 'Eerste', 'en' => 'First'], 'order' => 0]);

    $response = $this->actingAs($this->admin)->get(route('admin.navbar-items.index'));

    $response->assertSuccessful();

    $body = $response->getContent();
    expect(strpos($body, 'First'))->toBeLessThan(strpos($body, 'Second'));
});

test('the navbar item creation page lists both site pages and database pages', function () {
    $page = Page::factory()->create(['title' => ['nl' => 'Over ons', 'en' => 'About us']]);

    $response = $this->actingAs($this->admin)->get(route('admin.navbar-items.create'));

    $response->assertSuccessful();
    $response->assertSee('Menu');
    $response->assertSee('About us');
});

test('an admin can create a navbar item linking to a hardcoded site page', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.navbar-items.store'), [
        'header' => ['nl' => 'Menukaart', 'en' => 'Menu'],
        'link_target' => 'route:menu',
    ]);

    $response->assertRedirect(route('admin.navbar-items.index'));

    $navbarItem = SiteNavbarItem::query()->first();
    expect($navbarItem)->not->toBeNull();
    expect($navbarItem->page_id)->toBeNull();
    expect($navbarItem->foreign_url)->toBe(route('menu'));
    expect($navbarItem->url)->toBe(route('menu'));
});

test('an admin can create a navbar item linking to a database page', function () {
    $page = Page::factory()->create();

    $response = $this->actingAs($this->admin)->post(route('admin.navbar-items.store'), [
        'header' => ['nl' => 'Info', 'en' => 'Info'],
        'link_target' => "page:{$page->id}",
    ]);

    $response->assertRedirect(route('admin.navbar-items.index'));

    $navbarItem = SiteNavbarItem::query()->first();
    expect($navbarItem->page_id)->toBe($page->id);
    expect($navbarItem->foreign_url)->toBeNull();
    expect($navbarItem->url)->toBe(route('pages.show', $page));
});

test('an admin can create a navbar item with a custom url', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.navbar-items.store'), [
        'header' => ['nl' => 'Extern', 'en' => 'External'],
        'link_target' => 'custom',
        'custom_url' => 'https://example.com',
    ]);

    $response->assertRedirect(route('admin.navbar-items.index'));

    $navbarItem = SiteNavbarItem::query()->first();
    expect($navbarItem->page_id)->toBeNull();
    expect($navbarItem->foreign_url)->toBe('https://example.com');
});

test('a custom url is required when the custom link target is chosen', function () {
    $response = $this->actingAs($this->admin)->post(route('admin.navbar-items.store'), [
        'header' => ['nl' => 'Extern', 'en' => 'External'],
        'link_target' => 'custom',
        'custom_url' => '',
    ]);

    $response->assertInvalid(['custom_url']);
    expect(SiteNavbarItem::query()->count())->toBe(0);
});

test('a new navbar item is appended to the end of the nav', function () {
    SiteNavbarItem::factory()->create(['order' => 0]);
    SiteNavbarItem::factory()->create(['order' => 1]);

    $this->actingAs($this->admin)->post(route('admin.navbar-items.store'), [
        'header' => ['nl' => 'Nieuw', 'en' => 'New'],
        'link_target' => 'route:contact',
    ]);

    $newest = SiteNavbarItem::query()->orderByDesc('order')->first();
    expect($newest->order)->toBe(2);
});

test('an admin can update a navbar item', function () {
    $navbarItem = SiteNavbarItem::factory()->create(['header' => ['nl' => 'Oud', 'en' => 'Old']]);

    $response = $this->actingAs($this->admin)->patch(route('admin.navbar-items.update', $navbarItem), [
        'header' => ['nl' => 'Nieuw', 'en' => 'New'],
        'link_target' => 'route:contact',
    ]);

    $response->assertRedirect(route('admin.navbar-items.index'));

    $navbarItem->refresh();
    expect($navbarItem->getTranslation('header', 'en', false))->toBe('New');
    expect($navbarItem->foreign_url)->toBe(route('contact'));
});

test('an admin can delete a navbar item', function () {
    $navbarItem = SiteNavbarItem::factory()->create();

    $response = $this->actingAs($this->admin)->delete(route('admin.navbar-items.destroy', $navbarItem));

    $response->assertRedirect(route('admin.navbar-items.index'));
    $this->assertDatabaseMissing('site_navbar_items', ['id' => $navbarItem->id]);
});

test('an admin can move a navbar item up and down', function () {
    $first = SiteNavbarItem::factory()->create(['order' => 0]);
    $second = SiteNavbarItem::factory()->create(['order' => 1]);

    $this->actingAs($this->admin)->patch(route('admin.navbar-items.move-down', $first));

    expect($first->fresh()->order)->toBe(1);
    expect($second->fresh()->order)->toBe(0);

    $this->actingAs($this->admin)->patch(route('admin.navbar-items.move-up', $first));

    expect($first->fresh()->order)->toBe(0);
    expect($second->fresh()->order)->toBe(1);
});

test('moving the first item up does nothing', function () {
    $first = SiteNavbarItem::factory()->create(['order' => 0]);
    $second = SiteNavbarItem::factory()->create(['order' => 1]);

    $this->actingAs($this->admin)->patch(route('admin.navbar-items.move-up', $first));

    expect($first->fresh()->order)->toBe(0);
    expect($second->fresh()->order)->toBe(1);
});

test('the public site navbar reflects the configured items', function () {
    SiteNavbarItem::query()->delete();
    SiteNavbarItem::factory()->create(['header' => ['nl' => 'Speciaal', 'en' => 'Special Link'], 'foreign_url' => 'https://example.com', 'order' => 0]);

    $response = $this->get('/');

    $response->assertSuccessful();
    $response->assertSee('Special Link');
    $response->assertSee('https://example.com', escape: false);
});
