@extends('layouts.admin')

@section('title', 'Products - Admin Sardio Optik')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Product Inventory</h3>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary btn-sm">+ Add New Product</a>
    </div>

    <form method="GET" class="mb-3 d-flex gap-2" style="max-width:320px;">
        <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari produk...">
        <button class="btn btn-sm btn-outline-secondary">Cari</button>
    </form>

    <div class="card">
        <table class="table mb-0 align-middle">
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
                                <img src="{{ asset('storage/'.$product->main_image) }}" style="width:48px;height:48px;object-fit:cover;" class="rounded border">
                            @else
                                <div class="bg-light border rounded d-flex align-items-center justify-content-center text-muted" style="width:48px;height:48px;font-size:10px;">No Img</div>
                            @endif
                        </td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category ?? '-' }}</td>
                        <td>{{ $product->brand ?? '-' }}</td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td>{{ $product->stock }}</td>
                        <td class="d-flex gap-2">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" onsubmit="return confirm('Hapus produk ini?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-center text-muted py-4">Belum ada produk.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
@endsection
