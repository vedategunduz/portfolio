<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateAdminProfileRequest;
use App\Models\ClassifiedVisitLog;
use App\Models\ContactMessage;
use App\Models\ExploitSuspiciousEvent;
use App\Models\RawRequestLog;
use App\Services\ServerStatsService;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard(ServerStatsService $serverStats)
    {
        $stats = [
            'total_messages' => ContactMessage::count(),
            'unread_messages' => ContactMessage::where('status', 'unread')->count(),
        ];

        // Yeni ziyaretçi log özeti (insan/bot ayrımı). Tablolar yoksa 0 döner.
        try {
            $stats['total_hits'] = ClassifiedVisitLog::count();
            $stats['human_hits'] = ClassifiedVisitLog::where('traffic_type', 'human')->count();
            $stats['known_bot_hits'] = ClassifiedVisitLog::where('traffic_type', 'known_bot')->count();
            $stats['suspicious_hits'] = ClassifiedVisitLog::where('traffic_type', 'suspicious_bot')->count();
            $stats['unique_human_visitors'] = ClassifiedVisitLog::where('traffic_type', 'human')
                ->distinct('ip_address')->count('ip_address');
            $stats['today_hits'] = ClassifiedVisitLog::whereDate('visited_at', today())->count();
            $stats['suspicious_last_24h'] = ExploitSuspiciousEvent::where('created_at', '>=', now()->subDay())->count();
            $stats['top_request_ip'] = RawRequestLog::select('ip_address')
                ->selectRaw('count(*) as c')
                ->groupBy('ip_address')
                ->orderByDesc('c')
                ->first();
            $stats['top_target_url'] = RawRequestLog::select('path')
                ->selectRaw('count(*) as c')
                ->groupBy('path')
                ->orderByDesc('c')
                ->first();
            $stats['top_suspicious_pattern'] = ExploitSuspiciousEvent::select('matched_rule')
                ->selectRaw('count(*) as c')
                ->whereNotNull('matched_rule')
                ->groupBy('matched_rule')
                ->orderByDesc('c')
                ->first();
        } catch (\Throwable $e) {
            $stats['total_hits'] = $stats['human_hits'] = $stats['known_bot_hits'] = $stats['suspicious_hits'] = 0;
            $stats['unique_human_visitors'] = $stats['today_hits'] = $stats['suspicious_last_24h'] = 0;
            $stats['top_request_ip'] = $stats['top_target_url'] =             $stats['top_suspicious_pattern'] = null;
        }

        // Dashboard üst kartları: toplam/benzersiz artık yeni sistemden (geri uyumluluk için eski isimler)
        $stats['total_visits'] = $stats['total_hits'] ?? 0;
        $stats['unique_visitors'] = $stats['unique_human_visitors'] ?? 0;

        $serverStatsData = $serverStats->getStats();

        return view('admin.dashboard.index', compact('stats', 'serverStatsData'));
    }

    public function serverStatsApi(ServerStatsService $serverStats)
    {
        $data = $serverStats->getStats();
        $data['last_deploy_formatted'] = $data['last_deploy']
            ? \Carbon\Carbon::parse($data['last_deploy'])->format('d.m.Y H:i')
            : null;
        return response()->json($data);
    }

    public function profile(Request $request)
    {
        return view('admin.profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function updateProfile(UpdateAdminProfileRequest $request)
    {
        $user = $request->user();

        $data = $request->safe()->only(['name', 'email']);

        if ($request->filled('password')) {
            $data['password'] = $request->input('password');
        }

        $user->update($data);

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Admin bilgileri güncellendi.',
            ]);
        }

        return back()->with('success', 'Admin bilgileri güncellendi.');
    }

    public function contactMessages(Request $request)
    {
        $query = ContactMessage::query()->orderBy('created_at', 'desc');

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $messages = $query->paginate(20);

        return view('admin.contact-messages.index', compact('messages'));
    }

    public function markMessageAsRead($id)
    {
        $message = ContactMessage::findOrFail($id);
        $message->update(['status' => 'read']);

        return back()->with('success', 'Mesaj okundu olarak işaretlendi.');
    }
}
