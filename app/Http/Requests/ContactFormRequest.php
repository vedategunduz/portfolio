<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
    protected function getRedirectUrl(): string
    {
        $url = parent::getRedirectUrl();

        if (str_contains($url, '#')) {
            return $url;
        }

        return $url . '#contact';
    }

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'message' => 'required|string|min:10|max:5000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => __('messages.contact.validation.name_required'),
            'email.required' => __('messages.contact.validation.email_required'),
            'email.email' => __('messages.contact.validation.email_invalid'),
            'message.required' => __('messages.contact.validation.message_required'),
            'message.min' => __('messages.contact.validation.message_min'),
        ];
    }
}
