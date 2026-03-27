<?php

namespace Modules\Blog\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Modules\Blog\Application\Actions\AutosavePostAction;
use Modules\Blog\Application\Actions\CreatePostAction;
use Modules\Blog\Application\Actions\UpdatePostAction;
use Modules\Blog\Application\Services\PostMediaService;
use Modules\Blog\Application\ViewModels\AdminPostFormInsightsViewModel;
use Modules\Blog\Http\Requests\AutosavePostRequest;
use Modules\Blog\Http\Requests\StorePostRequest;
use Modules\Blog\Http\Requests\UpdatePostRequest;
use Modules\Blog\Models\Post;

class PostController extends Controller
{
    public function __construct(
        private PostMediaService $postMediaService,
        private AdminPostFormInsightsViewModel $formInsightsViewModel
    ) {}

    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $search = $request->query('search', '');
        $status = $request->string('status')->toString();
        $localeFilter = $request->string('locale')->toString();
        $supportedLocales = config('app.supported_locales', ['tr', 'en']);

        $query = Post::query()
            ->with(['user', 'translations', 'galleryImages'])
            ->withCount('galleryImages');

        // Search by title or slug
        if ($search !== '') {
            $query->whereHas('translations', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($status === 'published') {
            $query->where('published', true)->whereNotNull('published_at');
        } elseif ($status === 'draft') {
            $query->where(function ($q) {
                $q->where('published', false)->orWhereNull('published_at');
            });
        } elseif ($status === 'featured') {
            $query->where('is_featured', true);
        }

        if ($localeFilter !== '' && in_array($localeFilter, $supportedLocales, true)) {
            $query->whereHas('translations', function ($q) use ($localeFilter) {
                $q->where('locale', $localeFilter);
            });
        }

        $posts = $query->latest('updated_at')
            ->paginate(15)
            ->withQueryString();

        return view('admin.posts.index', compact('posts', 'locale', 'supportedLocales'));
    }

    public function create(): View
    {
        $locales = config('app.supported_locales', ['tr', 'en']);
        $formInsights = $this->formInsightsViewModel->build(null, $locales);

        return view('admin.posts.create', array_merge(compact('locales'), $formInsights));
    }

    public function autosaveStore(AutosavePostRequest $request, AutosavePostAction $autosavePostAction): JsonResponse
    {
        $post = $autosavePostAction->store(
            $request->validated(),
            (int) $request->user()->id,
            $request->file('gallery_images', []),
            $request->file('cover_image')
        );

        return response()->json([
            'ok' => true,
            'post_id' => $post->id,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    public function autosaveUpdate(AutosavePostRequest $request, Post $post, AutosavePostAction $autosavePostAction): JsonResponse
    {
        $post = $autosavePostAction->update(
            $post,
            $request->validated(),
            $request->file('gallery_images', []),
            $request->file('cover_image')
        );

        return response()->json([
            'ok' => true,
            'post_id' => $post->id,
            'saved_at' => now()->toIso8601String(),
        ]);
    }

    public function store(StorePostRequest $request, CreatePostAction $createPostAction): RedirectResponse
    {
        $createPostAction->execute(
            $request->validated(),
            (int) $request->user()->id,
            $request->file('gallery_images', []),
            $request->file('cover_image')
        );

        return redirect()->route('admin.posts.index')->with('success', __('messages.blog_admin.created'));
    }

    public function edit(Post $post): View
    {
        $post->load(['translations', 'galleryImages']);
        $locales = config('app.supported_locales', ['tr', 'en']);
        $formInsights = $this->formInsightsViewModel->build($post, $locales);

        return view('admin.posts.edit', array_merge(compact('post', 'locales'), $formInsights));
    }

    public function update(UpdatePostRequest $request, Post $post, UpdatePostAction $updatePostAction): RedirectResponse
    {
        $updatePostAction->execute(
            $post,
            $request->validated(),
            $request->file('gallery_images', []),
            $request->file('cover_image')
        );

        return redirect()->route('admin.posts.index')->with('success', __('messages.blog_admin.updated'));
    }

    public function destroy(Post $post): RedirectResponse
    {
        $post->load('galleryImages');

        $this->postMediaService->deleteStoredFile($post->cover_image);
        foreach ($post->galleryImages as $image) {
            $this->postMediaService->deleteStoredFile($image->image_path);
        }

        $post->delete();

        return back()->with('success', __('messages.blog_admin.deleted'));
    }

}
