<?php

namespace Modules\Contact\Application\Actions;

use Modules\Contact\Models\ContactMessage;

class MarkContactMessageAsReadAction
{
    public function execute(int|string $id): ContactMessage
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'read']);

        return $message;
    }
}
