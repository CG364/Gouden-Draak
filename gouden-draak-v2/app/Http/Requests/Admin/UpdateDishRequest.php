<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDishRequest extends FormRequest
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
            'menu_number' => ['required', 'string', 'max:255'],
            'dish_kind' => ['required', Rule::exists('dish_kinds', 'id')],
            'price' => ['required', 'numeric', 'min:0'],
            'name' => ['required', 'array'],
            'description' => ['required', 'array'],
            ...collect(config('translatable.locales'))->keys()->mapWithKeys(fn (string $locale) => [
                "name.{$locale}" => ['required', 'string', 'max:255'],
                "description.{$locale}" => ['required', 'string'],
            ])->all(),
        ];
    }
}
