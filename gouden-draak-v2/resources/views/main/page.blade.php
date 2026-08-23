@extends("main.layout")

@section("content")
<div class="bg-white font-[sans-serrif] border border-black p-6">
    <h1 class="text-3xl font-bold mb-6">{{ $page->title }}</h1>

    <div>
        {!! $page->content !!}
    </div>
</div>
@endsection
