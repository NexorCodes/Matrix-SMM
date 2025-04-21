<?php

namespace App\Http\Requests\Admin\ApiProvider;

use Illuminate\Foundation\Http\FormRequest;

class ApiProviderStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'api_name' => 'required|string|min:1|max:191',
            'url' => 'required|url|min:1|max:191',
            'api_key' => 'required|string|min:1|max:191',
            'conversion_rate' => 'required|numeric|not_in:0',
            'status' => 'nullable|numeric|in:0,1',
        ];
    }
}
