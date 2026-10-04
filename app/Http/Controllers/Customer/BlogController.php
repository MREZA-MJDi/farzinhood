<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\BlogCategory;
use App\Models\Post;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request): View
    {
        $category = $request->string('category')->trim()->toString();
        $search = $request->string('search')->trim()->toString();

        $posts = Post::query()
            ->with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->when(
                $category !== '',
                fn ($query) => $query->whereHas(
                    'category',
                    fn ($q) => $q->where('slug', $category)
                )
            )
            ->when(
                $search !== '',
                function ($query) use ($search) {
                    $query->where(function ($q) use ($search) {
                        $q->where('title', 'like', "%{$search}%")
                            ->orWhere('excerpt', 'like', "%{$search}%")
                            ->orWhere('content', 'like', "%{$search}%");
                    });
                }
            )
            ->latest('published_at')
            ->paginate(9)
            ->withQueryString();

        $categories = BlogCategory::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get();

        return view('blog.index', compact(
            'posts',
            'categories',
            'category',
            'search',
        ));
    }

    public function show(Post $post): View
    {
        abort_unless(
            $post->status === 'published'
            && $post->published_at
            && $post->published_at->lte(now()),
            404
        );

        $post->increment('view_count');

        $post->load('category');

        $relatedPosts = Post::query()
            ->with('category')
            ->where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->where('blog_category_id', $post->blog_category_id)
            ->whereKeyNot($post->id)
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('blog.show', compact(
            'post',
            'relatedPosts'
        ));
    }
}
