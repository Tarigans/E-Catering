<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MenuItemController extends Controller
{
    public function index()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.menus.index', ['menus' => MenuItem::with('category')->latest()->paginate(15)]);
    }

    public function create()
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.menus.form', ['menu' => new MenuItem, 'categories' => Category::all()]);
    }

    public function store(Request $request)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $this->validated($request);
        $data['slug'] = Str::slug($data['name']).'-'.Str::random(5);
        $data['image_url'] = $this->imagePath($request) ?? $data['image_url'] ?? null;
        $data['labels'] = array_filter(array_map('trim', explode(',', $data['labels_text'] ?? '')));
        unset($data['labels_text'], $data['image_file']);
        MenuItem::create($data);

        return redirect()->route('admin.menus.index')->with('success', 'Paket berhasil ditambahkan.');
    }

    public function edit(MenuItem $menu)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);

        return view('admin.menus.form', ['menu' => $menu, 'categories' => Category::all()]);
    }

    public function update(Request $request, MenuItem $menu)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $data = $this->validated($request);
        $newImagePath = $this->imagePath($request);

        if ($newImagePath) {
            $this->deleteUploadedImage($menu);
            $data['image_url'] = $newImagePath;
        } elseif (blank($data['image_url'] ?? null)) {
            unset($data['image_url']);
        }

        $data['labels'] = array_filter(array_map('trim', explode(',', $data['labels_text'] ?? '')));
        unset($data['labels_text'], $data['image_file']);
        $menu->update($data);

        return redirect()->route('admin.menus.index')->with('success', 'Paket berhasil diperbarui.');
    }

    public function destroy(MenuItem $menu)
    {
        abort_unless(auth()->user()?->isAdmin(), 403);
        $menu->delete();

        return back()->with('success', 'Paket dihapus.');
    }

    private function validated(Request $request): array
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:160'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'minimum_order' => ['required', 'integer', 'min:1'],
            'image_url' => ['nullable', 'url'],
            'image_file' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'labels_text' => ['nullable', 'string'],
            'is_popular' => ['nullable', 'boolean'],
            'is_promo' => ['nullable', 'boolean'],
            'is_active' => ['nullable', 'boolean'],
            'flash_sale_price' => ['nullable', 'integer', 'min:0'],
            'flash_sale_starts_at' => ['nullable', 'date_format:H:i'],
            'flash_sale_ends_at' => ['nullable', 'date_format:H:i'],
        ]);

        return $data + ['is_popular' => false, 'is_promo' => false, 'is_active' => false];
    }

    private function imagePath(Request $request): ?string
    {
        if (! $request->hasFile('image_file')) {
            return null;
        }

        $path = $request->file('image_file')->store('menu-images', 'public');

        return Storage::url($path);
    }

    private function deleteUploadedImage(MenuItem $menu): void
    {
        if (! $menu->image_url || ! str_starts_with($menu->image_url, '/storage/menu-images/')) {
            return;
        }

        Storage::disk('public')->delete(Str::after($menu->image_url, '/storage/'));
    }
}
