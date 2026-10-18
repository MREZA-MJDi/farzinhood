<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\PostIndexRequest;
use App\Http\Requests\Admin\PostRequest;
use App\Models\BlogCategory;
use App\Models\Post;
use App\Services\SeoService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(PostIndexRequest $request): View
    {
        $posts = Post::query()
            ->with(['category', 'author'])
            ->when($request->filled('search'), function ($query) use ($request) {
                $search = $request->input('search');

                $query->where(function ($q) use ($search) {
                    $q->where('title', 'like', "%{$search}%")
                        ->orWhere('slug', 'like', "%{$search}%")
                        ->orWhere('excerpt', 'like', "%{$search}%");
                });
            })
            ->when($request->filled('status'), fn ($query) =>
            $query->where('status', $request->input('status'))
            )
            ->when($request->filled('blog_category_id'), fn ($query) =>
            $query->where('blog_category_id', $request->integer('blog_category_id'))
            )
            ->latest()
            ->paginate(15);

        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'admin.posts.create',
            compact('categories')
        );
    }

    public function store(
        PostRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();
        $seoData = $seoService->data($validated);
        $image = $request->file('featured_image');
        unset(
            $validated['meta_title'],
            $validated['meta_description'],
            $validated['canonical_url'],
            $validated['noindex'],
            $validated['featured_image']
        );

        $imagePath = $image?->store('blog/posts', 'public');

        if ($imagePath) {
            $validated['featured_image'] = $imagePath;
        }

        $validated['author_id'] = $request->user()->id;

        if ($validated['status'] === 'published') {
            $validated['published_at'] ??= now();
        } else {
            $validated['published_at'] = null;
        }

        try {
            DB::transaction(function () use ($validated, $seoData, $seoService): void {
                $postData = $validated;

                unset(
                    $postData['meta_title'],
                    $postData['meta_description'],
                    $postData['canonical_url'],
                    $postData['noindex']
                );

                $post = Post::create($postData);
                $seoService->apply($post, $seoData);
            });
        } catch (\Throwable $exception) {
            if ($imagePath) {
                Storage::disk('public')->delete($imagePath);
            }

            throw $exception;
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function edit(Post $post): View
    {
        $post->load([
            'category',
            'author',
        ]);

        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view(
            'admin.posts.edit',
            compact('post', 'categories')
        );
    }

    public function update(
        PostRequest $request,
        Post $post,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();
        $seoData = $seoService->data($validated);
        $image = $request->file('featured_image');
        unset(
            $validated['meta_title'],
            $validated['meta_description'],
            $validated['canonical_url'],
            $validated['noindex'],
            $validated['featured_image']
        );

        $newImagePath = $image?->store('blog/posts', 'public');
        $oldImagePath = $post->featured_image;

        if ($newImagePath) {
            $validated['featured_image'] = $newImagePath;
        }

        try {
            DB::transaction(function () use ($validated, $seoData, $post, $seoService): void {
                $lockedPost = Post::query()
                    ->whereKey($post->getKey())
                    ->lockForUpdate()
                    ->firstOrFail();

                $postData = $validated;

                if ($postData['status'] === 'published' && ! $lockedPost->published_at) {
                    $postData['published_at'] = now();
                }

                if ($postData['status'] === 'draft') {
                    $postData['published_at'] = null;
                }

                $lockedPost->update($postData);
                $seoService->apply($lockedPost, $seoData);
            });
        } catch (\Throwable $exception) {
            if ($newImagePath) {
                Storage::disk('public')->delete($newImagePath);
            }

            throw $exception;
        }

        if ($newImagePath && $oldImagePath && $oldImagePath !== $newImagePath) {
            Storage::disk('public')->delete($oldImagePath);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $imagePath = DB::transaction(function () use ($post): ?string {
            $lockedPost = Post::query()
                ->whereKey($post->getKey())
                ->lockForUpdate()
                ->firstOrFail();

            $path = $lockedPost->featured_image;
            $lockedPost->delete();

            return $path;
        });

        if ($imagePath) {
            Storage::disk('public')->delete($imagePath);
        }

        return redirect()
            ->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
