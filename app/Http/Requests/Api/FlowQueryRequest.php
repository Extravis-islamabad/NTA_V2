<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;

class FlowQueryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'device_id' => 'nullable|integer|exists:devices,id',
            'protocol' => 'nullable|string|max:20',
            'application' => 'nullable|string|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'range' => 'nullable|string|in:1hour,6hours,24hours,7days',
            'limit' => 'nullable|integer|min:1|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'device_id.exists' => 'The selected device does not exist.',
            'end_date.after_or_equal' => 'End date must be after or equal to start date.',
            'limit.max' => 'Maximum limit is 1000 records.',
        ];
    }
}
