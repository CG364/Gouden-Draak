@extends('layouts.admin')

@section('page-title', 'New order')

@section('content')
@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form method="POST" action="{{ route('admin.orders.store') }}">
    @csrf

    <div
        id="order-app"
        data-dishes="{{ json_encode($dishes) }}"
        data-dish-kinds="{{ json_encode($dishKinds) }}"
    ></div>
</form>

@vite('resources/js/order.js')
@endsection
