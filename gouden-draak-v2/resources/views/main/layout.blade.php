@extends("rootLayout")

@section("body")
<div class="min-h-screen w-screen bg-[darkred] px-3 lg:px-10 pt-3 lg:pt-10 flex flex-col font-[sans-serrif]">

    <div class="flex justify-end gap-2 pb-2 text-sm">
        @foreach (config('translatable.locales') as $code => $label)
            <a
                href="{{ route('locale.switch', $code) }}"
                title="{{ $label }}"
                class="px-2 py-1 border border-yellow-300 {{ app()->getLocale() === $code ? 'bg-yellow-300 text-black font-bold' : 'text-yellow-300' }}"
            >{{ strtoupper($code) }}</a>
        @endforeach
    </div>

    <div class=" bg-[red]">
        <div class="grid grid-cols-1 lg:grid-cols-3 ">
            <div class="font-[chinese] text-[30px] text-yellow-300 hidden lg:flex justify-center">
                <img
                    src="/img/dragon-small.png"
                    class="inline w-13"
                    alt="" />
                DE GOUDEN DRAAK
                <img
                    src="/img/dragon-small-flipped.png"
                    alt=""
                    class="inline w-13" />
            </div>
            <a href="{{ route('menu') }}" class="marquee block text-yellow-300">
                <div class="marquee-track">
                    <span class="marquee-item">{{ __('home.welcome') }}</span>
                    <span class="marquee-item" aria-hidden="true">{{ __('home.welcome') }}</span>
                </div>
            </a>

            <div class="font-[chinese] text-[30px] text-yellow-300 hidden lg:flex justify-center">
                <img
                    src="/img/dragon-small.png"
                    class="inline w-13"
                    alt="" />
                DE GOUDEN DRAAK
                <img
                    src="/img/dragon-small-flipped.png"
                    alt=""
                    class="inline w-13" />
            </div>
        </div>

        <div class="traditional-border" style="border-image-source: url('/img/border.png');">
            <div class="flex">
                <img src="{{asset('img/dragon-small.png')}}"  alt="" class="w-50 hidden lg:block">
                <div class="grow">
                    <div class="text-yellow-300 text-center">
                        <p class="text-3xl font-bold">{{ __('home.tagline') }}</p>
                        <h1 class="text-5xl pt-3 font-bold">De Gouden Draak</h1>
                    </div>

                    <div class="flex w-full justify-center pt-5">
                        <div class="border flex text-white">
                            @foreach ($navbarItems ?? [] as $navbarItem)
                                <a href="{{ $navbarItem->url }}" class="m-1 px-2 py-1" style="background-image: url('/img/menu_bg_gradient.png')">{{ $navbarItem->header }}</a>
                            @endforeach
                        </div>
                    </div>
                </div>
                <img src="{{asset('img/dragon-small-flipped.png')}}" alt="" class="w-50 hidden lg:block">

            </div>
            <div class="flex-1">
                @yield('content')
            </div>
            <div class="flex justify-center pt-5">
                <a href="{{ route('contact') }}" class="text-yellow-300">{{ __('nav.to_contact') }}</a>
            </div>
        </div>
    </div>
</div>

@endsection