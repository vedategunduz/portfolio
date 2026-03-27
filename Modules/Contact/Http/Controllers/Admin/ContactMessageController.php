<?php

namespace Modules\Contact\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Contact\Application\Actions\ListContactMessagesAction;
use Modules\Contact\Application\Actions\MarkContactMessageAsReadAction;

class ContactMessageController extends Controller
{
    public function index(Request $request, ListContactMessagesAction $listContactMessagesAction): View
    {
        $messages = $listContactMessagesAction->execute($request->has('status') ? (string) $request->status : null);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function markRead(int|string $id, MarkContactMessageAsReadAction $markContactMessageAsReadAction): RedirectResponse
    {
        $markContactMessageAsReadAction->execute($id);

        return back()->with('success', 'Mesaj okundu olarak işaretlendi.');
    }
}
