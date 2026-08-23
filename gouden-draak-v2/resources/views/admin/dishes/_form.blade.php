@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div>
        <label for="menu_number" class="block text-sm font-medium text-slate-700">Menu number</label>
        <input
            id="menu_number"
            type="text"
            name="menu_number"
            value="{{ old('menu_number', $dish->menu_number) }}"
            required
            class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
        >
    </div>

    <div>
        <label for="dish_kind" class="block text-sm font-medium text-slate-700">Dish kind</label>
        <select
            id="dish_kind"
            name="dish_kind"
            required
            class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
        >
            <option value="">Select a dish kind</option>
            @foreach ($dishKinds as $kind)
                <option value="{{ $kind->id }}" @selected((int) old('dish_kind', $dish->dish_kind) === $kind->id)>
                    {{ $kind->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="price" class="block text-sm font-medium text-slate-700">Price</label>
        <input
            id="price"
            type="number"
            step="0.01"
            min="0"
            name="price"
            value="{{ old('price', $dish->price) }}"
            required
            class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
        >
    </div>
</div>

<div class="space-y-6">
    @foreach (config('translatable.locales') as $locale => $label)
        <div class="rounded-lg border border-slate-200 p-4">
            <h3 class="mb-3 text-sm font-semibold text-slate-700">{{ $label }}</h3>

            <div class="mb-4">
                <label for="name_{{ $locale }}" class="block text-sm font-medium text-slate-700">Name</label>
                <input
                    id="name_{{ $locale }}"
                    type="text"
                    name="name[{{ $locale }}]"
                    value="{{ old("name.$locale", $dish->exists ? $dish->getTranslation('name', $locale, false) : '') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>

            <div>
                <label for="description_{{ $locale }}" class="block text-sm font-medium text-slate-700">Description</label>
                <textarea
                    id="description_{{ $locale }}"
                    name="description[{{ $locale }}]"
                    rows="3"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >{{ old("description.$locale", $dish->exists ? $dish->getTranslation('description', $locale, false) : '') }}</textarea>
            </div>
        </div>
    @endforeach
</div>
