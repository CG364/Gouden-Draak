@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="rounded-lg border border-slate-200 p-4 space-y-4">
    <div>
        <label for="first_name" class="block text-sm font-medium text-slate-700">First name</label>
        <input
            id="first_name"
            type="text"
            name="first_name"
            value="{{ old('first_name', $staff->first_name) }}"
            required
            class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
        >
    </div>

    <div>
        <label for="last_name" class="block text-sm font-medium text-slate-700">Last name</label>
        <input
            id="last_name"
            type="text"
            name="last_name"
            value="{{ old('last_name', $staff->last_name) }}"
            required
            class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
        >
    </div>
</div>
