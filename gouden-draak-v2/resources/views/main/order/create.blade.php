@extends('main.layout')

@section('content')
<div class="bg-white font-[sans-serrif] border border-black">
    <div class="text-center font-bold py-5">
        <p class="text-xl">{{ __('order.title') }}</p>
        <p class="text-sm font-normal pt-1">{{ __('order.instructions') }}</p>
    </div>

    @if ($errors->any())
        <div class="mx-4 mb-4 rounded border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
            <ul class="list-disc pl-4 space-y-1">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('order.store') }}">
        @csrf

        <div class="px-4 pb-4">
            <label for="customer_name" class="block text-sm font-medium text-gray-700">{{ __('order.customer_name_label') }}</label>
            <input
                type="text"
                id="customer_name"
                name="customer_name"
                placeholder="{{ __('order.customer_name_placeholder') }}"
                value="{{ old('customer_name') }}"
                required
                maxlength="255"
                class="mt-1 block w-full max-w-sm rounded border-gray-300 shadow-sm focus:border-gray-500 focus:ring-gray-500"
            >
        </div>

        <div
            id="online-order-app"
            data-dish-kinds="{{ json_encode($dishKinds) }}"
        ></div>
    </form>
</div>

@vite('resources/js/online-order.js')
@endsection
