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
            'admin.blog.posts.create',
            compact('categories')
        );
    }

    public function store(
        PostRequest $request,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $image = $request->file('featured_image');

        unset($validated['featured_image']);

        $post = DB::transaction(function () use (
            $validated,
            $image,
            $request,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $postData = $validated;

            unset(
                $postData['meta_title'],
                $postData['meta_description'],
                $postData['canonical_url'],
                $postData['noindex']
            );

            $postData['author_id'] = $request->user()->id;

            if ($postData['status'] === 'published') {
                $postData['published_at'] ??= now();
            } else {
                $postData['published_at'] = null;
            }

            if ($image) {
                $postData['featured_image'] = $image->store(
                    'blog/posts',
                    'public'
                );
            }

            $post = Post::create($postData);

            $seoService->apply(
                $post,
                $seoData
            );

            return $post;
        });

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post created successfully.'
            );
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
            'admin.blog.posts.edit',
            compact('post', 'categories')
        );
    }

    public function update(
        PostRequest $request,
        Post $post,
        SeoService $seoService
    ): RedirectResponse {
        $validated = $request->validated();

        $image = $request->file('featured_image');

        unset($validated['featured_image']);

        DB::transaction(function () use (
            $validated,
            $image,
            $post,
            $seoService
        ) {
            $seoData = $seoService->data($validated);

            $postData = $validated;

            unset(
                $postData['meta_title'],
                $postData['meta_description'],
                $postData['canonical_url'],
                $postData['noindex']
            );

            if (
                $postData['status'] === 'published'
                && ! $post->published_at
            ) {
                $postData['published_at'] = now();
            }

            if ($postData['status'] === 'draft') {
                $postData['published_at'] = null;
            }

            if ($image) {
                if ($post->featured_image) {
                    Storage::disk('public')
                        ->delete($post->featured_image);
                }

                $postData['featured_image'] = $image->store(
                    'blog/posts',
                    'public'
                );
            }

            $post->update($postData);

            $seoService->apply(
                $post,
                $seoData
            );
        });

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post updated successfully.'
            );
    }

    public function destroy(
        Post $post
    ): RedirectResponse {
        if ($post->featured_image) {
            Storage::disk('public')
                ->delete($post->featured_image);
        }

        $post->delete();

        return redirect()
            ->route('admin.posts.index')
            ->with(
                'success',
                'Post deleted successfully.'
            );
    }
}
