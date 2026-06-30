<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request)
    {
        $menus = MenuItem::query()
            ->with('category')
            ->available()
            ->when($request->filled('q'), fn ($query) => $query->where('name', 'like', '%'.$request->q.'%'))
            ->when($request->filled('category'), fn ($query) => $query->whereHas('category', fn ($category) => $category->where('slug', $request->category)))
            ->when($request->boolean('popular'), fn ($query) => $query->where('is_popular', true))
            ->when($request->boolean('promo'), fn ($query) => $query->where('is_promo', true))
            ->when($request->filled('min'), fn ($query) => $query->where('price', '>=', (int) $request->min))
            ->when($request->filled('max'), fn ($query) => $query->where('price', '<=', (int) $request->max))
            ->latest()
            ->paginate(12)
            ->withQueryString();

        $categories = Category::where('is_active', true)->get();

        return view('menus.index', compact('menus', 'categories'));
    }
}
