<?php

namespace Modules\Admin\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateAdminProfileRequest;
use App\Services\ServerStatsService;
use Illuminate\Http\Request;
use Modules\Admin\Models\LoginHistory;
use Modules\Analytics\Application\Actions\GetAnalyticsDashboardStatsAction;
use Modules\Contact\Application\Actions\GetContactDashboardCountersAction;

class AdminController extends Controller
{
    public function dashboard(
        ServerStatsService $serverStats,
        GetContactDashboardCountersAction $getContactDashboardCountersAction,
        GetAnalyticsDashboardStatsAction $getAnalyticsDashboardStatsAction
    ) {
        $stats = array_merge(
            $getContactDashboardCountersAction->execute(),
            $getAnalyticsDashboardStatsAction->execute()
        );

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

    public function loginHistory(Request $request)
    {
        $query = LoginHistory::query()->with('user')->orderByDesc('attempted_at');

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }
        if ($request->filled('email')) {
            $query->where('email', 'like', '%'.$request->email.'%');
        }
        if ($request->filled('ip')) {
            $query->where('ip_address', 'like', '%'.$request->ip.'%');
        }
        if ($request->filled('date_from')) {
            $query->whereDate('attempted_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('attempted_at', '<=', $request->date_to);
        }

        $logs = $query->paginate(25)->withQueryString();

        return view('admin.login-history.index', compact('logs'));
    }
}
