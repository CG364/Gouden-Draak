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
            <label for="header_{{ $locale }}" class="block text-sm font-medium text-slate-700">Label</label>
            <input
                id="header_{{ $locale }}"
                type="text"
                name="header[{{ $locale }}]"
                value="{{ old("header.$locale", $navbarItem->exists ? $navbarItem->getTranslation('header', $locale, false) : '') }}"
                required
                class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
        </div>
    </div>
    @endforeach
</div>

<div class="rounded-lg border border-slate-200 p-4 space-y-4">
    <div>
        <label for="link_target" class="block text-sm font-medium text-slate-700">Links to</label>
        <select id="link_target" name="link_target" required class="mt-1 block w-full max-w-md rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
            <optgroup label="Site pages">
                @foreach ($hardcodedPages as $key => $hardcodedPage)
                <option value="route:{{ $key }}" @selected(old('link_target', $selectedLinkTarget ?? '' )==="route:{$key}" )>
                    {{ $hardcodedPage['label'] }}
                </option>
                @endforeach
            </optgroup>
            @if ($pages->isNotEmpty())
            <optgroup label="CMS Pages">
                @foreach ($pages as $page)
                <option value="page:{{ $page->id }}" @selected(old('link_target', $selectedLinkTarget ?? '' )==="page:{$page->id}" )>
                    {{ $page->title }}
                </option>
                @endforeach
            </optgroup>
            @endif
            <option value="custom" @selected(old('link_target', $selectedLinkTarget ?? '' )==='custom' )>
                Custom URL&hellip;
            </option>
        </select>
    </div>

    <div>
        <label for="custom_url" class="block text-sm font-medium text-slate-700">Custom URL</label>
        <input
            id="custom_url"
            type="text"
            name="custom_url"
            value="{{ old('custom_url', ($navbarItem->exists && ($selectedLinkTarget ?? '') === 'custom') ? $navbarItem->foreign_url : '') }}"
            placeholder="https://example.com"
            class="mt-1 block w-full max-w-md rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10">
        <p class="mt-1 text-xs text-slate-500">Only used when "Custom URL&hellip;" is selected above.</p>
    </div>
</div>