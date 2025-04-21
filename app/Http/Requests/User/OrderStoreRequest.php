<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class OrderStoreRequest extends FormRequest
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
        $rules = [
            'category' => 'required|integer|exists:categories,id',
            'service' => 'required|integer|exists:services,id',
            'link' => 'required|url',
            'quantity' => 'required|integer|not_in:0',
            'comments' => 'nullable|string|max:2000',
            'check' => 'required',
        ];

        if ($this->filled('drip_feed')) {
            $rules['runs'] = 'required|integer|not_in:0';
            $rules['interval'] = 'required|integer|not_in:0';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'category.required' => 'The category field is required',
            'service.required' => 'The service field is required',
        ];
    }

}
