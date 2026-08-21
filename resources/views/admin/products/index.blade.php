@extends('layouts.admin')

@section('title', 'Products - Admin Sardio Optik')

@section('breadcrumb')
    <span class="is-current">Products</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Product Inventory</h1>
            <p>Kelola semua produk yang dijual di toko.</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="admin-btn admin-btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Add New Product
        </a>
    </div>

    <form method="GET" class="admin-search-form mb-3" style="max-width:320px;">
        <input type="text" name="search" value="{{ request('search') }}" class="admin-input" placeholder="Cari produk...">
        <button class="admin-btn admin-btn--outline">Cari</button>
    </form>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Gambar</th>
                        <th>Nama</th>
                        <th>Kategori</th>
                        <th>Brand</th>
                        <th>Harga</th>
                        <th>Stok</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>
                                @if($product->main_image)
                                    <img src="{{ asset('storage/'.$product->main_image) }}" style="width:44px;height:44px;object-fit:cover;border-radius:8px;border:1px solid var(--so-border);">
                                @else
                                    <div style="width:44px;height:44px;border-radius:8px;background:var(--so-bg-secondary);border:1px solid var(--so-border);display:flex;align-items:center;justify-content:center;color:var(--so-text-muted);font-size:9px;">No Img</div>
                                @endif
                            </td>
                            <td>{{ $product->name }}</td>
                            <td class="text-muted-cell">{{ $product->category ?? '-' }}</td>
                            <td class="text-muted-cell">{{ $product->brand ?? '-' }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>
                                <span class="admin-badge admin-badge--{{ $product->stock == 0 ? 'dibatalkan' : ($product->stock <= 5 ? 'pending' : 'selesai') }}">
                                    {{ $product->stock }} unit
                                </span>
                            </td>
                            <td>
                                <div class="d-flex gap-2">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="admin-btn admin-btn--outline admin-btn--sm">Edit</a>
                                    <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="admin-btn admin-btn--danger-outline admin-btn--sm">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="admin-table-empty">Belum ada produk.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection
