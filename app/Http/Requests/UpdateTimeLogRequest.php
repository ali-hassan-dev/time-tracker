<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTimeLogRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'login_time' => 'nullable|date',
            'logout_time' => 'nullable|date',
            'talk_type' => 'nullable|in:Chat,Talk',
            'talk_time' => 'nullable|integer',
            'client' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'payout' => 'nullable|numeric|min:0',
            'country' => ['nullable', 'string', 'max:255', Rule::exists('countries', 'name')],
            'payment_method' => 'nullable|in:Phone,Crypto',
        ];
    }
}
