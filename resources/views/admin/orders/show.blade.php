@extends('layouts.admin')

@section('title', 'Order Detail - Admin Sardio Optik')

@section('breadcrumb')
    <a href="{{ route('admin.orders.index') }}">Orders</a>
    <span> / </span>
    <span class="is-current">{{ $order->order_code }}</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Order Detail — {{ $order->order_code }}</h1>
            <p>Dibuat {{ $order->created_at->translatedFormat('d F Y, H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="admin-card mb-3">
                <div class="admin-card__head"><h2>Customer Information</h2></div>
                <div class="admin-card__body">
                    <p class="mb-2"><strong>Nama:</strong> {{ $order->customer_name }}</p>
                    <p class="mb-0"><strong>WhatsApp:</strong> {{ $order->whatsapp_number }}</p>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head"><h2>Product List</h2></div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Produk</th><th>Qty</th><th>Harga</th><th>Subtotal</th></tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                                <tr>
                                    <td>
                                        {{ $item->product_name }}
                                        @if($item->hasPrescription())
                                            <div class="small text-muted-cell mt-1" style="font-size:12px;">
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
        </div>

        <div class="col-lg-5">
            <div class="admin-card mb-3">
                <div class="admin-card__head"><h2>Financial Summary</h2></div>
                <div class="admin-card__body">
                    <div class="d-flex justify-content-between fw-semibold fs-5">
                        <span>Total</span>
                        <span>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head"><h2>Update Status</h2></div>
                <div class="admin-card__body">
                    <form action="{{ route('admin.orders.updateStatus', $order) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="admin-field">
                            <select name="status" class="admin-select">
                                @foreach(['pending', 'diproses', 'selesai', 'dibatalkan'] as $status)
                                    <option value="{{ $status }}" @selected($order->status === $status)>{{ ucfirst($status) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button class="admin-btn admin-btn--primary" style="width:100%; justify-content:center;">Simpan Status</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
