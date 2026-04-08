<?php

namespace App\Http\Requests;

use App\Models\Lead;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateLeadRequest extends FormRequest
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
        $lead = $this->route('lead');

        return [
            'name' => ['required', 'string', 'min:3', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('leads', 'email')->ignore($lead)],
            'phone' => ['nullable', 'regex:/^[0-9+\-\s().]{7,20}$/'],
            'company' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(Lead::STATUSES)],
            'notes' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Please enter the lead name.',
            'name.min' => 'Lead name should be at least 3 characters.',
            'email.required' => 'Please enter an email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email is already assigned to another lead.',
            'phone.regex' => 'Phone number format looks invalid.',
            'status.required' => 'Please select a lead status.',
            'status.in' => 'Selected status is not valid.',
        ];
    }
}
