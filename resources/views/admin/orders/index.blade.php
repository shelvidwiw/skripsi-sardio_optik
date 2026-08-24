@extends('layouts.admin')

@section('title', 'Order Management - Admin Sardio Optik')

@section('breadcrumb')
    <span class="is-current">Orders</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Order Management</h1>
            <p>Kelola semua pesanan pelanggan di sini.</p>
        </div>
        <form method="GET" class="admin-search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="admin-input" placeholder="Cari nama / kode order">
            <button class="admin-btn admin-btn--outline">Cari</button>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>ID Order</th>
                        <th>Customer</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Tanggal</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                            <td>@include('admin.partials.status-badge', ['status' => $order->status])</td>
                            <td class="text-muted-cell">{{ $order->created_at->format('d M Y') }}</td>
                            <td><a href="{{ route('admin.orders.show', $order) }}" class="admin-btn admin-btn--outline admin-btn--sm">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-table-empty">Belum ada order.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $orders->links() }}
    </div>
@endsection
