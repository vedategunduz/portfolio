<?php

namespace App\Http\Controllers;

use App\Models\PageHistory;
use App\Models\ContactMessage;
use App\Services\ServerStatsService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(ServerStatsService $serverStats)
    {
        $stats = [
            'total_visits' => PageHistory::count(),
            'unique_visitors' => PageHistory::distinct('ip_address')->count('ip_address'),
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
        ];

        $serverStatsData = $serverStats->getStats();

        return view('admin.dashboard', compact('stats', 'serverStatsData'));
    }

    public function serverStatsApi(ServerStatsService $serverStats)
    {
        $data = $serverStats->getStats();
        $data['last_deploy_formatted'] = $data['last_deploy']
            ? \Carbon\Carbon::parse($data['last_deploy'])->format('d.m.Y H:i')
            : null;
        return response()->json($data);
    }

    public function pageHistory(Request $request)
    {
        $query = PageHistory::query()->orderBy('created_at', 'desc');

        if ($request->has('ip')) {
            $query->where('ip_address', $request->ip);
        }

        $history = $query->paginate(50);

        return view('admin.page-history', compact('history'));
    }

    public function contactMessages(Request $request)
    {
        $query = ContactMessage::query()->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20);

        return view('admin.contact-messages', compact('messages'));
    }

    public function markMessageAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'read']);

        return back()->with('success', 'Mesaj okundu olarak işaretlendi.');
    }
}
