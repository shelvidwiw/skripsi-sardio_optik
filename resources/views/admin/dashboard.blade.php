@extends('layouts.admin')

@section('title', 'Dashboard - Admin Sardio Optik')

@section('breadcrumb')
    <span class="is-current">Dashboard</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Dashboard Overview</h1>
            <p>Ringkasan aktivitas toko Sardio Optik hari ini.</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--primary">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/></svg>
            Kelola Order
        </a>
    </div>

    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--blue">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
            </div>
            <div>
                <div class="stat-card__label">Total Order</div>
                <div class="stat-card__value">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--amber">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
            </div>
            <div>
                <div class="stat-card__label">Pending</div>
                <div class="stat-card__value">{{ $pending }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--cyan">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12a9 9 0 1 1-6.219-8.56"/><path d="M21 3v6h-6"/></svg>
            </div>
            <div>
                <div class="stat-card__label">Diproses</div>
                <div class="stat-card__value">{{ $diproses }}</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-card__icon stat-card__icon--green">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 6 9 17l-5-5"/></svg>
            </div>
            <div>
                <div class="stat-card__label">Selesai</div>
                <div class="stat-card__value">{{ $selesai }}</div>
            </div>
        </div>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="admin-card mb-3">
                <div class="admin-card__head">
                    <h2>Tren Order — 7 Hari Terakhir</h2>
                </div>
                <div class="admin-card__body">
                    <canvas id="orderTrendChart" height="110"></canvas>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head">
                    <h2>Order Terbaru</h2>
                    <a href="{{ route('admin.orders.index') }}" class="admin-btn admin-btn--outline admin-btn--sm">Lihat Semua</a>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead>
                            <tr><th>Kode Order</th><th>Customer</th><th>Total</th><th>Status</th></tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr onclick="window.location='{{ route('admin.orders.show', $order) }}'" style="cursor:pointer;">
                                    <td>{{ $order->order_code }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                                    <td>@include('admin.partials.status-badge', ['status' => $order->status])</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="admin-table-empty">Belum ada order.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="admin-card mb-3">
                <div class="admin-card__head">
                    <h2>Booking Pemeriksaan Mata Terbaru</h2>
                    <a href="{{ route('admin.eye-exam-bookings.index') }}" class="admin-btn admin-btn--outline admin-btn--sm">Lihat Semua</a>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Nama</th><th>Jadwal</th><th>Status</th></tr></thead>
                        <tbody>
                            @forelse($recentBookings as $booking)
                                <tr>
                                    <td>{{ $booking->name }}</td>
                                    <td class="text-muted-cell">{{ $booking->preferred_date->format('d M') }}</td>
                                    <td>@include('admin.partials.status-badge', ['status' => $booking->status])</td>
                                </tr>
                            @empty
                                <tr><td colspan="3" class="admin-table-empty">Belum ada booking.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head">
                    <h2>Stok Menipis</h2>
                    <a href="{{ route('admin.products.index') }}" class="admin-btn admin-btn--outline admin-btn--sm">Kelola Produk</a>
                </div>
                <div class="admin-table-wrap">
                    <table class="admin-table">
                        <thead><tr><th>Produk</th><th>Stok</th></tr></thead>
                        <tbody>
                            @forelse($lowStockProducts as $product)
                                <tr>
                                    <td>{{ $product->name }}</td>
                                    <td><span class="admin-badge admin-badge--{{ $product->stock == 0 ? 'dibatalkan' : 'pending' }}">{{ $product->stock }} unit</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="2" class="admin-table-empty">Stok aman semua 👍</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.4/dist/chart.umd.min.js"></script>
<script>
    const ctx = document.getElementById('orderTrendChart');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($trend->pluck('label')) !!},
            datasets: [{
                label: 'Jumlah Order',
                data: {!! json_encode($trend->pluck('count')) !!},
                borderColor: '#0D4C92',
                backgroundColor: 'rgba(13, 76, 146, 0.08)',
                tension: 0.35,
                fill: true,
                pointRadius: 3,
                pointBackgroundColor: '#0D4C92',
            }]
        },
        options: {
            plugins: { legend: { display: false } },
            scales: {
                y: { beginAtZero: true, ticks: { precision: 0 }, grid: { color: '#F0F2F7' } },
                x: { grid: { display: false } }
            }
        }
    });
</script>
@endpush
