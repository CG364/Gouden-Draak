<?php

use App\Models\Discount;
use App\Models\Dish;
use App\Models\DishKind;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the menu page lists dish kinds and their dishes', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '1',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
    ]);

    $this->withUnencryptedCookie('locale', 'nl')
        ->get('/menu')
        ->assertSuccessful()
        ->assertSee('Soep')
        ->assertSee('Kippensoep')
        ->assertSee($dish->menu_number);
});

test('dish kinds without any dishes are not shown on the menu page', function () {
    DishKind::factory()->create(['name' => ['nl' => 'Lege categorie', 'en' => 'Empty category']]);

    $this->get('/menu')
        ->assertSuccessful()
        ->assertDontSee('Lege categorie');
});

test('dishes on the menu page are sorted by menu number naturally, not lexicographically', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    Dish::factory()->create(['dish_kind' => $dishKind->id, 'menu_number' => '10']);
    Dish::factory()->create(['dish_kind' => $dishKind->id, 'menu_number' => '2']);
    Dish::factory()->create(['dish_kind' => $dishKind->id, 'menu_number' => 'M1']);
    Dish::factory()->create(['dish_kind' => $dishKind->id, 'menu_number' => '58A']);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get('/menu');

    $response->assertSuccessful();

    $body = $response->getContent();
    $positions = collect(['&quot;2&quot;', '&quot;10&quot;', '&quot;58A&quot;', '&quot;M1&quot;'])
        ->map(fn (string $needle) => strpos($body, $needle));

    expect($positions->values()->all())->toBe($positions->sort()->values()->all());
});

test('a dish with an active discount shows its discounted price on the menu page', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '1',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
        'price' => 10,
    ]);
    $discount = Discount::factory()->create([
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDays(3),
    ]);
    $discount->dishes()->attach($dish->id, ['discounted_price' => 7.5]);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get('/menu');

    $response->assertSuccessful();
    $response->assertSee('&quot;discountedPrice&quot;:7.5', escape: false);
});

test('a discounted dish also appears under a dedicated special offers category', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '1',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
        'price' => 10,
    ]);
    $discount = Discount::factory()->create([
        'starts_at' => now()->subDay(),
        'ends_at' => now()->addDays(3),
    ]);
    $discount->dishes()->attach($dish->id, ['discounted_price' => 7.5]);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get('/menu');

    $response->assertSuccessful();
    $response->assertSee('Aanbiedingen');

    $body = $response->getContent();
    expect(substr_count($body, 'Kippensoep'))->toBeGreaterThanOrEqual(2);
});

test('the special offers category is absent when nothing is discounted', function () {
    DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);

    $this->withUnencryptedCookie('locale', 'nl')
        ->get('/menu')
        ->assertSuccessful()
        ->assertDontSee('Aanbiedingen');
});

test('a dish with no active discount has a null discounted price on the menu page', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    $dish = Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '1',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
        'price' => 10,
    ]);
    $expiredDiscount = Discount::factory()->create([
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->subDay(),
    ]);
    $expiredDiscount->dishes()->attach($dish->id, ['discounted_price' => 7.5]);

    $response = $this->withUnencryptedCookie('locale', 'nl')->get('/menu');

    $response->assertSuccessful();
    $response->assertSee('&quot;discountedPrice&quot;:null', escape: false);
});

test('the menu page has a link to download the pdf', function () {
    $this->get('/menu')
        ->assertSuccessful()
        ->assertSee(route('menu.pdf'), escape: false);
});

test('the menu pdf can be downloaded', function () {
    $dishKind = DishKind::factory()->create(['name' => ['nl' => 'Soep', 'en' => 'Soup']]);
    Dish::factory()->create([
        'dish_kind' => $dishKind->id,
        'menu_number' => '1',
        'name' => ['nl' => 'Kippensoep', 'en' => 'Chicken soup'],
    ]);

    $response = $this->get(route('menu.pdf'));

    $response->assertSuccessful();
    $response->assertHeader('content-type', 'application/pdf');
    $response->assertHeader('content-disposition', 'attachment; filename=menu-de-gouden-draak.pdf');
});
