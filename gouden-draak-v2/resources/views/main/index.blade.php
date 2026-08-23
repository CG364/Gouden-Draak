@extends("main.layout")

@section("content")
<div class="bg-white font-[sans-serrif] border border-black">
    <div class="text-center font-bold">
        <p class="text-xl">{{ __('home.intro_line_1') }}<br />
            {{ __('home.intro_line_2') }}</p>


        <h2 class="text-2xl underline py-10">{{ __('home.student_offer_title') }}</h2>

        <p class="text-3xl pb-10">{{ __('home.rijsttafel_title') }}</p>
        <p class="text-xl">{{ __('home.rijsttafel_intro') }}</p>
        <div class="grid grid-cols-2 gap-5 text-xl pt-5">
            <div>
                <ul class="text-right">
                    <li>{{ __('home.dish_koe_loe_yuk') }}</li>
                    <li>{{ __('home.dish_tjap_tjoy') }}</li>
                    <li>{{ __('home.dish_babi_pangang') }}</li>
                </ul>
            </div>
            <div>
                <ul class="text-left">
                    <li>{{ __('home.dish_foe_yong_hai') }}</li>
                    <li>{{ __('home.dish_garnalen_knoflook') }}</li>
                    <li>{{ __('home.dish_kipfilet_zwarte_bonen') }}</li>
                </ul>
            </div>
        </div>

        <p class="text-xl pt-5">{{ __('home.rijsttafel_note') }}</p>
        <p class="text-4xl py-8 ">{{ __('home.rijsttafel_price') }}</p>

    </div>

</div>
@endsection