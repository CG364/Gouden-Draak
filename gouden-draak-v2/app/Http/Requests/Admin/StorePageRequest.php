<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePageRequest extends FormRequest
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
    public function rules(): array
    {
        return [
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('pages', 'slug')],
            'title' => ['required', 'array'],
            'content' => ['required', 'array'],
            ...collect(config('translatable.locales'))->keys()->mapWithKeys(fn (string $locale) => [
                "title.{$locale}" => ['required', 'string', 'max:255'],
                "content.{$locale}" => ['required', 'string'],
            ])->all(),
        ];
    }
}
