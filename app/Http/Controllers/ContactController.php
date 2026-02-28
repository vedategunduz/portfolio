<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;

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

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Mesajınız başarıyla gönderildi!',
                ], 200);
            }

            return back()
                ->with('contact_success', 'Mesajınız başarıyla gönderildi!')
                ->withFragment('contact');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.',
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()
                ->withInput()
                ->with('contact_error', 'Bir hata oluştu. Lütfen daha sonra tekrar deneyin.')
                ->withFragment('contact');
        }
    }
}
