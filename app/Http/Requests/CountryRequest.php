<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CountryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()->can('access-admin');
    }

    /**
     * Get the validation rules that apply to the request. On update, the
     * country's own name and code do not count as taken.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $country = $this->route('country');

        return [
            'name' => ['required', Rule::unique('countries', 'name')->ignore($country)],
            'code' => ['required', Rule::unique('countries', 'code')->ignore($country), 'size:3'],
        ];
    }
}
