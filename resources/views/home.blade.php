<x-layouts.app title="E-Catering - Paket Catering Acara dan Langganan">
    <section class="hero">
        <div class="container">
            <div class="col-lg-7">
                <span class="badge badge-hot mb-3">Pesan H-1 sebelum pukul 20:00</span>
                <h1 class="display-4 fw-bold">Paket catering untuk acara, kantor, dan langganan harian.</h1>
                <p class="lead mt-3">Pilih paket, tentukan jumlah pax, isi tanggal acara dan alamat penerima, lalu pantau status pesanan sampai selesai.</p>
                <div class="d-flex flex-wrap gap-2 mt-4">
                    <a href="{{ route('menus.index') }}" class="btn btn-tomato btn-lg">Pilih Paket</a>
                    <a href="#cara-pesan" class="btn btn-light btn-lg">Cara Pesan</a>
                </div>
                <div class="mt-4 soft text-dark d-inline-flex gap-3 align-items-center px-3 py-2 rounded" x-data="countdown('{{ $cutoffAt->toIso8601String() }}')" x-init="start()">
                    <strong>Cut-off berikutnya</strong>
                    <span x-text="time"></span>
                </div>
            </div>
        </div>
    </section>

    <section id="cara-pesan" class="container py-5">
        <div class="page-title mb-4">
            <div class="eyebrow">Alur Customer</div>
            <h2 class="fw-bold">Pesan catering dalam 4 langkah</h2>
        </div>
        <div class="row g-3">
            @foreach([['1','Pilih paket','Pernikahan, harian, mingguan, atau bulanan.'],['2','Isi jumlah pax','Jumlah otomatis mengikuti minimal pemesanan paket.'],['3','Atur acara','Masukkan tanggal acara dan alamat penerima.'],['4','Pantau status','Admin memperbarui status pesanan dari dashboard.']] as $step)
                <div class="col-md-6 col-lg-3">
                    <div class="soft rounded p-3 h-100">
                        <div class="step-dot mb-3">{{ $step[0] }}</div>
                        <h3 class="h6 fw-bold">{{ $step[1] }}</h3>
                        <p class="small text-secondary mb-0">{{ $step[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <section class="container py-4">
        <div class="d-flex justify-content-between align-items-end mb-3">
            <div class="page-title">
                <div class="eyebrow">Kategori</div>
                <h2 class="fw-bold">Pilih sesuai kebutuhan acara</h2>
                <p class="text-secondary mb-0">Setiap kategori berisi paket dengan harga dan minimal pax berbeda.</p>
            </div>
            <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary d-none d-md-inline-flex">Lihat Semua</a>
        </div>
        <div class="row g-3">
            @foreach($categories as $category)
                <div class="col-6 col-lg-3">
                    <a class="soft hover-lift d-block p-3 rounded text-decoration-none text-reset h-100" href="{{ route('menus.index', ['category' => $category->slug]) }}">
                        <div class="eyebrow mb-2">Paket</div>
                        <h3 class="h6 fw-bold">{{ $category->name }}</h3>
                        <p class="small text-secondary mb-0">{{ $category->menu_items_count }} pilihan paket</p>
                    </a>
                </div>
            @endforeach
        </div>
    </section>

    <section id="rekomendasi" class="container py-5">
        <div class="page-title mb-3">
            <div class="eyebrow">Rekomendasi</div>
            <h2 class="fw-bold">Paket populer</h2>
        </div>
        <div class="row g-4">
            @foreach($popularMenus as $menu)
                <div class="col-md-6 col-lg-4">@include('menus.card', ['menu' => $menu])</div>
            @endforeach
        </div>
    </section>

    <section class="container py-4 pb-5">
        <div class="row g-4 align-items-stretch">
            <div class="col-lg-6">
                <div class="soft rounded p-4 h-100">
                    <div class="eyebrow">Promo</div>
                    <h2 class="fw-bold">Paket promo aktif</h2>
                    @foreach($flashSales as $menu)
                        <div class="d-flex gap-3 border-top py-3">
                            <img src="{{ $menu->image_url }}" class="rounded" style="width:92px;height:72px;object-fit:cover" alt="{{ $menu->name }}">
                            <div>
                                <strong>{{ $menu->name }}</strong>
                                <div class="text-danger fw-bold">Rp{{ number_format($menu->current_price,0,',','.') }}/pax</div>
                                <small>Min. {{ $menu->minimum_order }} pax</small>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            <div class="col-lg-6">
                <div class="soft rounded p-4 h-100">
                    <div class="eyebrow">Langganan</div>
                    <h2 class="fw-bold">Catering rutin</h2>
                    @foreach($plans as $plan)
                        <div class="border-top py-3"><strong>{{ $plan->name }}</strong><p class="mb-1 small text-secondary">{{ $plan->description }}</p><span class="badge text-bg-success">Diskon {{ $plan->discount_percent }}%</span> <span>Rp{{ number_format($plan->price,0,',','.') }}</span></div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <script>
        function countdown(target) { return { time: '', start() { setInterval(() => { const diff = new Date(target) - new Date(); const h = Math.max(0, Math.floor(diff / 36e5)); const m = Math.max(0, Math.floor(diff % 36e5 / 6e4)); const s = Math.max(0, Math.floor(diff % 6e4 / 1000)); this.time = `${h}j ${m}m ${s}d`; }, 1000); } } }
    </script>
</x-layouts.app>
