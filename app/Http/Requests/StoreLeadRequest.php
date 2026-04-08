<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreLeadRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255'],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s().]{7,20}$/'],
            'company' => ['nullable', 'string', 'max:255'],
            'status_id' => ['required', 'exists:lead_statuses,id'],
            'source_id' => ['nullable', 'exists:lead_sources,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the lead name.',
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address.',
            'phone.regex' => 'Phone number format looks invalid.',
            'status_id.required' => 'Please select a lead status.',
            'status_id.exists' => 'Selected status is not valid.',
            'source_id.exists' => 'Selected source is not valid.',
        ];
    }
}
