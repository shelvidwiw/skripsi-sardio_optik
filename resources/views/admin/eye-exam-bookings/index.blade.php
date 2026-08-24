@extends('layouts.admin')

@section('title', 'Eye Exam Bookings - Admin Sardio Optik')

@section('breadcrumb')
    <span class="is-current">Eye Exam Bookings</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Eye Exam Bookings</h1>
            <p>Kelola jadwal pemeriksaan mata yang dibooking pelanggan.</p>
        </div>
        <form method="GET" class="admin-search-form">
            <input type="text" name="search" value="{{ request('search') }}" class="admin-input" placeholder="Cari nama / kode booking">
            <button class="admin-btn admin-btn--outline">Cari</button>
        </form>
    </div>

    <div class="admin-card">
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                    <tr>
                        <th>Kode Booking</th>
                        <th>Nama</th>
                        <th>WhatsApp</th>
                        <th>Jadwal</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bookings as $booking)
                        <tr>
                            <td>{{ $booking->booking_code }}</td>
                            <td>{{ $booking->name }}</td>
                            <td>{{ $booking->whatsapp_number }}</td>
                            <td>{{ $booking->preferred_date->translatedFormat('d M Y') }}, {{ $booking->preferred_time }}</td>
                            <td>@include('admin.partials.status-badge', ['status' => $booking->status])</td>
                            <td><a href="{{ route('admin.eye-exam-bookings.show', $booking) }}" class="admin-btn admin-btn--outline admin-btn--sm">Detail</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="admin-table-empty">Belum ada booking pemeriksaan mata.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
@endsection
