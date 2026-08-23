<?php

namespace App\Http\Requests\Admin;

use Carbon\Carbon;
use Closure;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreTablePlanningRequest extends FormRequest
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
            'staff_id' => ['required', Rule::exists('staff', 'id')],
            'table_ids' => ['required', 'array', 'min:1'],
            'table_ids.*' => [Rule::exists('tables', 'id')],
            'start' => ['required', 'date'],
            'end' => [
                'required',
                'date',
                'after:start',
                function (string $attribute, mixed $value, Closure $fail): void {
                    if (Carbon::parse($value)->gt(Carbon::parse($this->input('start'))->addDays(7))) {
                        $fail('A planning can span a maximum of one week.');
                    }
                },
            ],
        ];
    }
}
