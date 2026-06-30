<x-layouts.app title="Keranjang Paket Catering">
    <x-breadcrumbs><li class="breadcrumb-item active">Keranjang</li></x-breadcrumbs>
    <main class="container py-4" x-data="cartPage()">
        <div class="page-title">
            <div class="eyebrow">Langkah 2</div>
            <h1 class="fw-bold">Cek paket dan jumlah pax</h1>
            <p class="text-secondary">Pastikan jumlah pax sudah sesuai kebutuhan acara sebelum checkout.</p>
        </div>
        <div class="row g-4 mt-1">
            <div class="col-lg-8">
                <div class="soft rounded p-3">
                    @forelse($items as $key => $item)
                        <div class="d-flex flex-column flex-md-row gap-3 py-3 border-bottom">
                            <img src="{{ $item['menu']->image_url }}" class="rounded" style="width:120px;height:92px;object-fit:cover" alt="{{ $item['name'] }}">
                            <div class="flex-grow-1">
                                <h2 class="h6 fw-bold">{{ $item['name'] }}</h2>
                                <p class="small text-secondary mb-1">Minimal pemesanan {{ $item['menu']->minimum_order }} pax</p>
                                <p class="small text-secondary mb-1">{{ $item['note'] ?: 'Tanpa catatan khusus' }}</p>
                                <strong>Rp{{ number_format($item['price'],0,',','.') }}/pax</strong>
                            </div>
                            <div>
                                <label class="form-label small mb-1">Jumlah pax</label>
                                <input class="form-control" style="width:110px;height:42px" type="number" min="{{ $item['menu']->minimum_order }}" value="{{ $item['quantity'] }}" x-on:change="update('{{ $key }}', $event.target.value)">
                            </div>
                        </div>
                    @empty
                        <p class="mb-0">Keranjang masih kosong. Pilih paket catering terlebih dahulu.</p>
                    @endforelse
                </div>
            </div>
            <div class="col-lg-4">
                <div class="soft rounded p-3 position-sticky" style="top:92px">
                    <h2 class="h5 fw-bold">Ringkasan</h2>
                    <div class="d-flex justify-content-between"><span>Subtotal paket</span><strong x-text="money(subtotal)">Rp{{ number_format($subtotal,0,',','.') }}</strong></div>
                    <div class="small text-secondary mt-2">Ongkir, voucher, tanggal acara, dan alamat penerima diisi pada langkah checkout.</div>
                    <a href="{{ route('checkout.create') }}" class="btn btn-tomato w-100 mt-3 @if($items->isEmpty()) disabled @endif">Lanjut Checkout</a>
                    <a href="{{ route('menus.index') }}" class="btn btn-outline-secondary w-100 mt-2">Tambah Paket Lain</a>
                </div>
            </div>
        </div>
    </main>
    <script>
        function cartPage(){return{subtotal:{{ $subtotal }},money(v){return 'Rp'+Number(v).toLocaleString('id-ID')},update(key,quantity){fetch(`/keranjang/${key}`,{method:'PATCH',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({quantity})}).then(r=>r.json()).then(d=>this.subtotal=d.subtotal)}}}
    </script>
</x-layouts.app>
