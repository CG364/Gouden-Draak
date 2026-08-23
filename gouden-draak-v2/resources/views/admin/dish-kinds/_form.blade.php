@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="space-y-6">
    @foreach (config('translatable.locales') as $locale => $label)
        <div class="rounded-lg border border-slate-200 p-4">
            <h3 class="mb-3 text-sm font-semibold text-slate-700">{{ $label }}</h3>

            <div>
                <label for="name_{{ $locale }}" class="block text-sm font-medium text-slate-700">Name</label>
                <input
                    id="name_{{ $locale }}"
                    type="text"
                    name="name[{{ $locale }}]"
                    value="{{ old("name.$locale", $dishKind->exists ? $dishKind->getTranslation('name', $locale, false) : '') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>
        </div>
    @endforeach
</div>
