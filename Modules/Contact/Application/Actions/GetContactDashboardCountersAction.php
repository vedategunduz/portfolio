<?php

namespace Modules\Contact\Application\Actions;

use Modules\Contact\Models\ContactMessage;

class GetContactDashboardCountersAction
{
    /**
     * @return array{total_messages:int,unread_messages:int}
     */
    public function execute(): array
    {
        return [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
        ];
    }
}
