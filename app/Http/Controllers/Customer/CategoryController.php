<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Contracts\View\View;

class CategoryController extends Controller
{
    public function show(Category $category): View
    {
        abort_unless($category->is_active, 404);

        $products = $category->products()
            ->with([
                'primaryImage',
                'category',
            ])
            ->where('is_active', true)
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view(
            'customer.categories.show',
            compact('category', 'products')
        );
    }
}
