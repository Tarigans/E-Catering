<x-layouts.app title="Paket Catering">
    <x-breadcrumbs><li class="breadcrumb-item active">Paket Catering</li></x-breadcrumbs>
    <main class="container py-4">
        <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 align-items-lg-end mb-3">
            <div class="page-title">
                <div class="eyebrow">Katalog Paket</div>
                <h1 class="fw-bold">Pilih paket catering</h1>
                <p class="text-secondary mb-0">Harga dihitung per pax. Jumlah pesanan mengikuti minimal pemesanan setiap paket.</p>
            </div>
            <a href="{{ route('cart.show') }}" class="btn btn-outline-secondary">Lihat Keranjang</a>
        </div>
        <form class="soft rounded p-3 mb-4 row g-2 align-items-end">
            <div class="col-md-4"><label class="form-label">Cari paket</label><input name="q" value="{{ request('q') }}" class="form-control" placeholder="Contoh: pernikahan, bulanan"></div>
            <div class="col-md-3"><label class="form-label">Kategori</label><select name="category" class="form-select"><option value="">Semua kategori</option>@foreach($categories as $category)<option value="{{ $category->slug }}" @selected(request('category')===$category->slug)>{{ $category->name }}</option>@endforeach</select></div>
            <div class="col-md-2"><label class="form-label">Harga min</label><input name="min" value="{{ request('min') }}" class="form-control" placeholder="0"></div>
            <div class="col-md-2"><label class="form-label">Harga max</label><input name="max" value="{{ request('max') }}" class="form-control" placeholder="100000"></div>
            <div class="col-md-1"><button class="btn btn-tomato w-100">Cari</button></div>
            <div class="col-12 d-flex gap-3 small pt-1"><label><input type="checkbox" name="popular" value="1" @checked(request('popular'))> Populer</label><label><input type="checkbox" name="promo" value="1" @checked(request('promo'))> Promo</label></div>
        </form>
        <div class="row g-4">@foreach($menus as $menu)<div class="col-md-6 col-lg-4">@include('menus.card', ['menu' => $menu])</div>@endforeach</div>
        <div class="mt-4">{{ $menus->links() }}</div>
    </main>
</x-layouts.app>
