<?php

namespace App\Http\Requests\Admin;

use App\Actions\Navbar\ListHardcodedPages;
use App\Models\Page;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateSiteNavbarItemRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(ListHardcodedPages $listHardcodedPages): array
    {
        $validLinkTargets = collect($listHardcodedPages->handle())
            ->keys()
            ->map(fn (string $key): string => "route:{$key}")
            ->merge(Page::query()->pluck('id')->map(fn (int $id): string => "page:{$id}"))
            ->push('custom');

        return [
            'header' => ['required', 'array'],
            ...collect(config('translatable.locales'))->keys()->mapWithKeys(fn (string $locale) => [
                "header.{$locale}" => ['required', 'string', 'max:255'],
            ])->all(),
            'link_target' => ['required', Rule::in($validLinkTargets)],
            'custom_url' => ['required_if:link_target,custom', 'nullable', 'string', 'max:2048'],
        ];
    }
}
