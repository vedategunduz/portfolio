<?php

namespace Modules\PublicSite\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Blog\Models\Post;

class HomeController extends Controller
{
    public function redirectRoot(Request $request): RedirectResponse
    {
        $supported = config('app.supported_locales', ['tr', 'en']);
        $locale = $request->session()->get('app_locale');

        if (! in_array($locale, $supported, true)) {
            $locale = $request->getPreferredLanguage($supported) ?? config('app.locale', 'tr');
        }

        return redirect('/'.$locale, 302);
    }

    public function home(string $locale): View
    {
        $posts = Post::query()
            ->published()
            ->latestPublished()
            ->with(['user', 'translations'])
            ->take(3)
            ->get();

        return view('home', compact('posts'));
    }
}
