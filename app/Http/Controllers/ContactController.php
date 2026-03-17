<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactFormRequest;
use App\Models\ContactMessage;

class ContactController extends Controller
{
    public function submit(ContactFormRequest $request)
    {
        $validated = $request->validated();

        try {
            // Veritabanına kaydet
            ContactMessage::create($validated);

            // E-posta gönderme işlemi (opsiyonel)
            // Mail::to(config('mail.from.address'))->send(new ContactMail($validated));

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('messages.contact.success'),
                ], 200);
            }

            return back()
                ->with('contact_success', __('messages.contact.success'))
                ->withFragment('contact');

        } catch (\Exception $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('messages.contact.error'),
                    'error' => $e->getMessage(),
                ], 500);
            }

            return back()
                ->withInput()
                ->with('contact_error', __('messages.contact.error'))
                ->withFragment('contact');
        }
    }
}

