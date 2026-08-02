@extends('layouts.admin')

@section('title', 'Order Detail - Admin Sardio Optik')

@section('content')
    <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">&larr; Kembali</a>
    <h3 class="mt-2 mb-4">Order Detail - {{ $order->order_code }}</h3>

    <div class="row g-4">
        <div class="col-md-7">
            <div class="card p-4 mb-4">
                <h5 class="mb-3">Customer Information</h5>
                <p class="mb-1"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                <p class="mb-0"><strong>WhatsApp:</strong> {{ $order->whatsapp_number }}</p>
            </div>

            <div class="card p-4">
                <h5 class="mb-3">Product List</h5>
                <table class="table">
                    <thead>
                        <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            <tr>
                                <td>
                                    {{ $item->product_name }}
                                    @if($item->hasPrescription())
                                        <div class="small text-muted mt-1">
                                            @if($item->prescription_file)
                                                📄 <a href="{{ asset('storage/'.$item->prescription_file) }}" target="_blank">Lihat file resep</a>
                                            @else
                                                📄 OD {{ $item->sph_right ?? '-' }} / OS {{ $item->sph_left ?? '-' }}
                                                @if($item->cyl_right !== null || $item->cyl_left !== null)
                                                    &middot; Cyl {{ $item->cyl_right ?? '-' }} / {{ $item->cyl_left ?? '-' }}
                                                @endif
                                                @if($item->axis_right !== null || $item->axis_left !== null)
                                                    &middot; Axis {{ $item->axis_right ?? '-' }} / {{ $item->axis_left ?? '-' }}
                                                @endif
                                            @endif
                                            @if($item->prescription_note)
                                                <br>Catatan: {{ $item->prescription_note }}
                                            @endif
                                        </div>
                                    @endif
                                </td>
                                <td>{{ $item->quantity }}</td>
                                <td>Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card p-4 mb-4">
                <h5 class="mb-3">Financial Summary</h5>
                <div class="d-flex justify-content-between fw-semibold fs-5">
                    <span>Total</span>
                    <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                </div>
            </div>

            <div class="card p-4">
                <h5 class="mb-3">Update Status</h5>
                <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                    @csrf
                    @method('PATCH')
                    <select name="status" class="form-select mb-3">
                        @foreach(['pending', 'diproses', 'selesai', 'dibatalkan'] as $status)
                            <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-primary w-100">Simpan Status</button>
                </form>
            </div>
        </div>
    </div>
@endsection
