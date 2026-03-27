<?php

namespace Modules\Contact\Application\Actions;

use Modules\Contact\Models\ContactMessage;

class SubmitContactMessageAction
{
    /**
     * @param array{name:string,email:string,message:string} $payload
     */
    public function execute(array $payload): ContactMessage
    {
        return ContactMessage::create($payload);
    }
}
