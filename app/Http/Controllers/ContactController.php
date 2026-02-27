<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use Illuminate\Support\Facades\Mail;

class ContactController extends Controller
{
    public function submit(ContactFormRequest $request)
    {
        $validated = $request->validated();

        try {
            // E-posta gönderme işlemi (opsiyonel)
            // Mail::to(config('mail.from.address'))->send(new ContactMail($validated));

            // Basit DB kaydı yapılabilir
            // ContactRequest::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Mesajınız başarıyla gönderildi!',
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
