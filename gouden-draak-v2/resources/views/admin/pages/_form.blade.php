@if ($errors->any())
    <div class="mb-4 rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-800">
        <ul class="list-disc pl-4 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div>
    <label for="slug" class="block text-sm font-medium text-slate-700">Slug</label>
    <input
        id="slug"
        type="text"
        name="slug"
        value="{{ old('slug', $page->slug) }}"
        required
        class="mt-1 block w-full max-w-md rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
    >
    <p class="mt-1 text-xs text-slate-500">Used in the public URL: {{ url('/pages') }}/{slug}</p>
</div>

<div class="space-y-6">
    @foreach (config('translatable.locales') as $locale => $label)
        <div class="rounded-lg border border-slate-200 p-4">
            <h3 class="mb-3 text-sm font-semibold text-slate-700">{{ $label }}</h3>

            <div class="mb-4">
                <label for="title_{{ $locale }}" class="block text-sm font-medium text-slate-700">Title</label>
                <input
                    id="title_{{ $locale }}"
                    type="text"
                    name="title[{{ $locale }}]"
                    value="{{ old("title.$locale", $page->exists ? $page->getTranslation('title', $locale, false) : '') }}"
                    required
                    class="mt-1 block w-full rounded-lg border-slate-300 bg-white px-3 py-2 shadow-sm transition-colors focus:border-slate-400 focus:outline-none focus:ring-2 focus:ring-slate-900/10"
                >
            </div>

            <div>
                <label for="content_{{ $locale }}_editor" class="block text-sm font-medium text-slate-700">Content</label>
                <input
                    id="content_{{ $locale }}"
                    type="hidden"
                    name="content[{{ $locale }}]"
                    value="{{ old("content.$locale", $page->exists ? $page->getTranslation('content', $locale, false) : '') }}"
                >
                <trix-editor
                    id="content_{{ $locale }}_editor"
                    input="content_{{ $locale }}"
                    class="mt-1 block w-full"
                ></trix-editor>
            </div>
        </div>
    @endforeach
</div>
