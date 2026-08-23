@extends("main.layout")

@section("content")
@php
    $menuTranslations = [
        'favoritesSaved' => __('menu.favorites_saved'),
        'showFavoritesOnly' => __('menu.show_favorites_only'),
        'showFullMenu' => __('menu.show_full_menu'),
        'noFavorites' => __('menu.no_favorites'),
        'addFavorite' => __('menu.add_favorite'),
        'removeFavorite' => __('menu.remove_favorite'),
        'specialOffer' => __('menu.special_offer'),
    ];
@endphp

<div class="bg-white font-[sans-serrif] border border-black">
    <div class="text-center font-bold py-5">
        <p class="text-xl">{{ __('menu.title') }}</p>
        <p class="text-sm font-normal pt-1">{{ __('menu.instructions') }}</p>

        <a href="{{ route('menu.pdf') }}" class="mt-3 inline-block border border-black px-3 py-1 text-sm">
            {{ __('menu.download_pdf') }}
        </a>
    </div>

    <div
        id="menu-app"
        data-dish-kinds="{{ json_encode($dishKinds) }}"
        data-locale="{{ app()->getLocale() }}"
        data-translations="{{ json_encode($menuTranslations) }}"
    ></div>
</div>

@vite('resources/js/menu.js')
@endsection
