<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $isElderly = $this->user()->account_type === 'elderly';
        $isVolunteer = $this->user()->account_type === 'volunteer';

        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'dob' => ['required', 'date', 'before:today'],
            'phone' => ['required', 'string', 'max:30'],
            'id_number' => [Rule::requiredIf($isVolunteer), 'nullable', 'string', 'max:255', Rule::unique('registration_profiles', 'identity_number')->ignore($this->user()->registrationProfile?->id)],
            'city' => [Rule::requiredIf($isElderly), 'nullable', 'string', 'max:255'],
            'address' => [Rule::requiredIf($isElderly), 'nullable', 'string', 'max:1000'],
            'housing_type' => [Rule::requiredIf($isElderly), 'nullable', Rule::in(['apartment', 'house', 'family'])],
            'extra_info' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
