<x-layouts.app title="Form Paket Catering">
    <main class="container py-4" style="max-width:860px">
        <div class="mb-3"><div class="eyebrow">Admin Paket</div><h1 class="fw-bold">{{ $menu->exists ? 'Edit Paket' : 'Tambah Paket' }}</h1><p class="text-secondary mb-0">Atur nama paket, kategori, harga per pax, dan minimal pemesanan.</p></div>
        <form method="post" action="{{ $menu->exists ? route('admin.menus.update', $menu) : route('admin.menus.store') }}" enctype="multipart/form-data" class="soft rounded p-3 row g-3">
            @csrf @if($menu->exists) @method('put') @endif
            <div class="col-md-6"><label class="form-label">Nama Paket</label><input name="name" class="form-control" value="{{ old('name', $menu->name) }}" required></div>
            <div class="col-md-6"><label class="form-label">Kategori</label><select name="category_id" class="form-select">@foreach($categories as $category)<option value="{{ $category->id }}" @selected(old('category_id', $menu->category_id)==$category->id)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-md-4"><label class="form-label">Harga per Porsi/Pax</label><input name="price" type="number" class="form-control" value="{{ old('price', $menu->price) }}" required></div>
            <div class="col-md-4"><label class="form-label">Minimal Pemesanan</label><input name="minimum_order" type="number" min="1" class="form-control" value="{{ old('minimum_order', $menu->minimum_order ?? 1) }}" required></div>
            <div class="col-md-4"><label class="form-label">Flash Sale</label><input name="flash_sale_price" type="number" class="form-control" value="{{ old('flash_sale_price', $menu->flash_sale_price) }}"></div>
            <div class="col-md-6"><label class="form-label">Mulai Flash Sale</label><input name="flash_sale_starts_at" type="time" class="form-control" value="{{ old('flash_sale_starts_at', $menu->flash_sale_starts_at ? substr($menu->flash_sale_starts_at,0,5) : '') }}"></div>
            <div class="col-md-6"><label class="form-label">Selesai Flash Sale</label><input name="flash_sale_ends_at" type="time" class="form-control" value="{{ old('flash_sale_ends_at', $menu->flash_sale_ends_at ? substr($menu->flash_sale_ends_at,0,5) : '') }}"></div>
            <div class="col-md-7">
                <label class="form-label">Upload Gambar Paket</label>
                <input name="image_file" type="file" accept="image/png,image/jpeg,image/webp" class="form-control">
                <div class="form-text">Format JPG, PNG, atau WebP. Maksimal 2MB.</div>
            </div>
            <div class="col-md-5">
                <label class="form-label">URL Gambar Alternatif</label>
                <input name="image_url" class="form-control" value="{{ old('image_url', str_starts_with((string) $menu->image_url, '/storage/') ? '' : $menu->image_url) }}" placeholder="Opsional jika tidak upload">
            </div>
            @if($menu->image_url)
                <div class="col-12">
                    <label class="form-label">Preview Saat Ini</label>
                    <img src="{{ $menu->image_url }}" class="rounded border" style="width:180px;height:120px;object-fit:cover" alt="{{ $menu->name }}">
                </div>
            @endif
            <div class="col-12"><label class="form-label">Deskripsi</label><textarea name="description" class="form-control" rows="3">{{ old('description', $menu->description) }}</textarea></div>
            <div class="col-12"><label class="form-label">Label dipisah koma</label><input name="labels_text" class="form-control" value="{{ old('labels_text', implode(', ', $menu->labels ?? [])) }}"></div>
            <div class="col-12 d-flex gap-3"><label><input type="checkbox" name="is_popular" value="1" @checked(old('is_popular', $menu->is_popular))> Terlaris</label><label><input type="checkbox" name="is_promo" value="1" @checked(old('is_promo', $menu->is_promo))> Promo</label><label><input type="checkbox" name="is_active" value="1" @checked(old('is_active', $menu->exists ? $menu->is_active : true))> Aktif</label></div>
            <div class="col-12 d-flex gap-2"><button class="btn btn-tomato">Simpan Paket</button><a href="{{ route('admin.menus.index') }}" class="btn btn-outline-secondary">Batal</a></div>
        </form>
    </main>
</x-layouts.app>
