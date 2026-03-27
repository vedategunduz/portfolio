<?php

namespace Modules\Contact\Application\Actions;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Modules\Contact\Models\ContactMessage;

class ListContactMessagesAction
{
    public function execute(?string $status): LengthAwarePaginator
    {
        $query = ContactMessage::query()->orderBy('created_at', 'desc');

        if ($status !== null && $status !== '') {
            $query->where('status', $status);
        }

        return $query->paginate(20);
    }
}
