<div class="card soft hover-lift h-100 border-0">
    <img src="{{ $menu->image_url }}" class="menu-img" alt="{{ $menu->name }}">
    <div class="card-body d-flex flex-column">
        <div class="d-flex justify-content-between gap-2 align-items-start">
            <div>
                <div class="small text-secondary">{{ $menu->category->name }}</div>
                <h3 class="h5 fw-bold">{{ $menu->name }}</h3>
            </div>
            <span class="badge text-bg-light">Rating {{ $menu->rating_average }}</span>
        </div>
        <p class="small text-secondary">{{ $menu->description }}</p>
        <div class="d-flex flex-wrap gap-1 mb-3">
            @foreach($menu->labels ?? [] as $label)<span class="badge badge-hot">{{ $label }}</span>@endforeach
            <span class="badge text-bg-success">Min. {{ $menu->minimum_order }} pax</span>
        </div>
        <form class="mt-auto" x-data="{ qty: {{ $menu->minimum_order }}, note: '' }" x-on:submit.prevent="fetch('{{ route('cart.store') }}',{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name=csrf-token]').content},body:JSON.stringify({menu_item_id:{{ $menu->id }},quantity:qty,note})}).then(()=>alert('Paket ditambahkan ke keranjang'))">
            <div class="d-flex align-items-end justify-content-between gap-2">
                <div>
                    <div class="small text-secondary">Harga per pax</div>
                    <strong class="text-danger fs-5">Rp{{ number_format($menu->current_price,0,',','.') }}</strong>
                </div>
                <div>
                    <label class="form-label small mb-1">Jumlah pax</label>
                    <input class="form-control form-control-sm" style="width:96px" type="number" min="{{ $menu->minimum_order }}" x-model="qty">
                </div>
            </div>
            <textarea class="form-control form-control-sm my-2" rows="2" placeholder="Catatan acara, alergi, request menu" x-model="note"></textarea>
            <button class="btn btn-tomato w-100">Tambah ke Keranjang</button>
        </form>
    </div>
</div>
