<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;

class UpdateAdminProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()?->id),
            ],
            'current_password' => ['nullable', 'required_with:password', 'current_password'],
            'password' => ['nullable', 'confirmed', Password::min(8)],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.profile.validation.name_required'),
            'email.required' => __('messages.profile.validation.email_required'),
            'email.email' => __('messages.profile.validation.email_email'),
            'email.unique' => __('messages.profile.validation.email_unique'),
            'current_password.required_with' => __('messages.profile.validation.current_password_required_with'),
            'current_password.current_password' => __('messages.profile.validation.current_password_current_password'),
            'password.confirmed' => __('messages.profile.validation.password_confirmed'),
            'password.min' => __('messages.profile.validation.password_min'),
        ];
    }
}
