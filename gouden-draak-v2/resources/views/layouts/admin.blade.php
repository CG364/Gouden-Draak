@extends('rootLayout')

@section('body')
<div class="flex min-h-screen bg-slate-50">
    <aside class="flex w-64 shrink-0 flex-col bg-slate-950 text-slate-200">
        <div class="flex items-center gap-2 border-b border-white/10 px-6 py-5">
            <span class="h-2 w-2 rounded-full bg-amber-400"></span>
            <span class="text-lg font-bold tracking-tight text-white">De Gouden Draak</span>
        </div>

        <nav class="flex-1 space-y-1 px-3 py-4">
            @include('admin.partials.nav')
        </nav>

        <div class="border-t border-white/10 px-3 py-4">
            <form method="POST" action="{{ route('admin.logout') }}">
                @csrf
                <button type="submit" class="block w-full rounded-lg px-3 py-2 text-left text-sm text-slate-400 transition-colors hover:bg-white/5 hover:text-white">
                    Log out
                </button>
            </form>
        </div>
    </aside>

    <div class="flex min-w-0 flex-1 flex-col">
        <header class="border-b border-slate-200 bg-white px-6 py-4">
            <h1 class="text-xl font-semibold text-slate-900">@yield('page-title', 'Admin')</h1>
        </header>

        <main class="flex-1 px-6 py-6">
            @if (session('status'))
                <div class="mb-4 rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-800">
                    {{ session('status') }}
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</div>
@endsection
