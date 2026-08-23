<?php

namespace App\Http\Requests\Admin;

use App\Models\DiningSession;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreDiningSessionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation(): void
    {
        $this->merge([
            'guest_ages' => collect(explode(',', (string) $this->input('guest_ages')))
                ->map(fn (string $age): string => trim($age))
                ->filter(fn (string $age): bool => $age !== '')
                ->values()
                ->all(),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'table_id' => [
                'required',
                Rule::exists('tables', 'id'),
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (DiningSession::query()->active()->where('table_id', $value)->exists()) {
                        $fail('This table already has an active dining session.');
                    }
                },
            ],
            'guest_count' => ['required', 'integer', 'min:1', 'max:'.DiningSession::MAX_GUESTS],
            'guest_ages' => [
                'required',
                'array',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (count($value) !== (int) $this->input('guest_count')) {
                        $fail('Please enter exactly one age per guest.');
                    }
                },
            ],
            'guest_ages.*' => ['integer', 'min:0', 'max:120'],
            'wants_extra_deluxe_menu' => ['required', 'boolean'],
        ];
    }
}
