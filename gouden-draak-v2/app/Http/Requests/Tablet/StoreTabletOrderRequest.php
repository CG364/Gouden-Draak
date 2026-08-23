<?php

namespace App\Http\Requests\Tablet;

use App\Models\DiningSession;
use App\Models\Dish;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class StoreTabletOrderRequest extends FormRequest
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
        /** @var DiningSession $diningSession */
        $diningSession = $this->route('diningSession');

        return [
            'quantities' => [
                'required',
                'array',
                'min:1',
                function (string $attribute, mixed $value, Closure $fail) use ($diningSession): void {
                    if (! $diningSession->can_place_order) {
                        $fail('No new order can be placed for this table right now.');

                        return;
                    }

                    $dishIds = array_keys((array) $value);

                    if (Dish::query()->whereIn('id', $dishIds)->count() !== count($dishIds)) {
                        $fail('One of the selected products no longer exists.');
                    }
                },
            ],
            'quantities.*' => ['required', 'integer', 'min:1'],
            'notes' => ['sometimes', 'array'],
            'notes.*' => ['nullable', 'string', 'max:255'],
        ];
    }
}
