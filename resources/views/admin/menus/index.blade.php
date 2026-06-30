<x-layouts.app title="Admin Paket">
    <main class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-3"><div><div class="eyebrow">Admin</div><h1 class="fw-bold mb-0">Manajemen Paket</h1></div><a class="btn btn-tomato" href="{{ route('admin.menus.create') }}">Tambah Paket</a></div>
        <div class="soft rounded p-3 table-responsive">
            <table class="table align-middle"><thead><tr><th>Paket</th><th>Kategori</th><th>Harga</th><th>Minimal</th><th>Label</th><th></th></tr></thead><tbody>
                @foreach($menus as $menu)<tr><td><strong>{{ $menu->name }}</strong></td><td>{{ $menu->category->name }}</td><td>Rp{{ number_format($menu->price,0,',','.') }}</td><td>{{ $menu->minimum_order }} pax</td><td>{{ implode(', ', $menu->labels ?? []) }}</td><td class="text-end"><a class="btn btn-sm btn-outline-secondary" href="{{ route('admin.menus.edit', $menu) }}">Edit</a><form method="post" action="{{ route('admin.menus.destroy', $menu) }}" class="d-inline">@csrf @method('delete')<button class="btn btn-sm btn-outline-danger">Hapus</button></form></td></tr>@endforeach
            </tbody></table>
            {{ $menus->links() }}
        </div>
    </main>
</x-layouts.app>
