<?php

namespace Modules\Admin\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Modules\Admin\Models\LoginHistory;

class AdminAuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $ip = $request->ip();
        $userAgent = $request->userAgent();

        if (Auth::attempt($credentials, $request->filled('remember'))) {
            $request->session()->regenerate();
            LoginHistory::logSuccess(Auth::user(), $ip, $userAgent);

            return redirect()->intended(route('admin.dashboard'));
        }

        $failureReason = User::where('email', $credentials['email'])->exists()
            ? LoginHistory::FAILURE_REASON_WRONG_PASSWORD
            : LoginHistory::FAILURE_REASON_USER_NOT_FOUND;

        LoginHistory::logFailed($credentials['email'], $failureReason, $ip, $userAgent);

        return back()->withErrors([
            'email' => __('messages.auth.login_failed'),
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
