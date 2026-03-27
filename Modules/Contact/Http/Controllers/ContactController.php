<?php

namespace Modules\Contact\Http\Controllers;

use App\Http\Controllers\Controller;
use Modules\Contact\Application\Actions\SubmitContactMessageAction;
use Modules\Contact\Http\Requests\ContactFormRequest;

class ContactController extends Controller
{
    public function submit(ContactFormRequest $request, SubmitContactMessageAction $submitContactMessageAction)
    {
        $validated = $request->validated();

        try {
            $submitContactMessageAction->execute($validated);

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
