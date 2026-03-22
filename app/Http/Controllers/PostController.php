<?php

namespace App\Http\Controllers;

use App\Models\Post;

class PostController extends Controller
{
    public function index(): \Illuminate\View\View
    {
        $locale = app()->getLocale();

        $posts = Post::with(['translations' => function ($query) use ($locale) {
                $query->where('locale', $locale);
            }, 'user'])
            ->latestPublished()
            ->paginate(10);

        return view('blog.index', compact('posts'));
    }

    public function show(string $slug): \Illuminate\View\View
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
