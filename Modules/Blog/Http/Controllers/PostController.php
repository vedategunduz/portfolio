<?php

namespace Modules\Blog\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\View\View;
use Modules\Blog\Models\Post;

class PostController extends Controller
{
    public function index(): View
    {
        $locale = app()->getLocale();

        $posts = Post::with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'user'])
            ->latestPublished()
            ->paginate(10);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug): View
    {
        $locale = app()->getLocale();

        $post = Post::with(['translations', 'user', 'galleryImages'])
            ->latestPublished()
            ->whereHas('translations', function ($query) use ($slug, $locale) {
                $query->where('slug', $slug)
                    ->where('locale', $locale);
            })
            ->firstOrFail();

        return view('blog.show', compact('post'));
    }
}
