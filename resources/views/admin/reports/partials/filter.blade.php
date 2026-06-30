<form method="get" class="soft rounded p-3 row g-2 align-items-end mb-3">
    <div class="col-md-3">
        <label class="form-label">Dari tanggal</label>
        <input type="date" name="from" value="{{ $filters['from'] }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Sampai tanggal</label>
        <input type="date" name="to" value="{{ $filters['to'] }}" class="form-control">
    </div>
    <div class="col-md-3">
        <label class="form-label">Status</label>
        <select name="status" class="form-select">
            <option value="">Semua status</option>
            @foreach(['waiting_payment','paid','processing','cooking','ready_to_ship','on_delivery','delivered','cancelled'] as $status)
                <option value="{{ $status }}" @selected($filters['status'] === $status)>{{ __('orders.status.'.$status) }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3 d-flex gap-2">
        <button class="btn btn-tomato flex-fill">Terapkan</button>
        <a href="{{ url()->current() }}" class="btn btn-outline-secondary">Reset</a>
    </div>
</form>
