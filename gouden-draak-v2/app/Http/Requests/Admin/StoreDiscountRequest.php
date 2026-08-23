<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiscountRequest extends FormRequest
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
            'starts_at' => ['required', 'date'],
            'ends_at' => [
                'required',
                'date',
                'after:starts_at',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (Carbon::parse($value)->gt(Carbon::parse($this->input('starts_at'))->addDays(7))) {
                        $fail('A discount can span a maximum of one week.');
                    }
                },
            ],
            'dish_ids' => [
                'required',
                'array',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail): void {
                    $prices = $this->input('discounted_prices', []);

                    foreach ((array) $value as $dishId) {
                        if (! isset($prices[$dishId]) || ! is_numeric($prices[$dishId]) || (float) $prices[$dishId] < 0) {
                            $fail('Each selected product needs a valid discounted price.');
                        }
                    }
                },
            ],
            'dish_ids.*' => [Rule::exists('dishes', 'id')],
            'discounted_prices' => ['required', 'array'],
        ];
    }
}
