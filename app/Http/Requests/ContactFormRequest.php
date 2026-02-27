<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ContactFormRequest extends FormRequest
{
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
            'name.required' => 'Ad Soyad gereklidir',
            'email.required' => 'E-posta gereklidir',
            'email.email' => 'Geçerli bir e-posta adresi girin',
            'message.required' => 'Mesaj gereklidir',
            'message.min' => 'Mesaj en az 10 karakter olmalıdır',
        ];
    }
}
