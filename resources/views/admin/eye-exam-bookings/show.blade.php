@extends('layouts.admin')

@section('title', 'Detail Booking - Admin Sardio Optik')

@section('breadcrumb')
    <a href="{{ route('admin.eye-exam-bookings.index') }}">Eye Exam Bookings</a>
    <span> / </span>
    <span class="is-current">{{ $booking->booking_code }}</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Detail Booking — {{ $booking->booking_code }}</h1>
            <p>Informasi lengkap jadwal pemeriksaan mata pelanggan.</p>
        </div>
        <a href="{{ route('admin.eye-exam-bookings.index') }}" class="admin-btn admin-btn--outline">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="m12 19-7-7 7-7"/><path d="M19 12H5"/></svg>
            Kembali
        </a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="admin-card mb-3">
                <div class="admin-card__head"><h2>Informasi Pelanggan</h2></div>
                <div class="admin-card__body">
                    <p class="mb-2"><strong>Nama:</strong> {{ $booking->name }}</p>
                    <p class="mb-2"><strong>WhatsApp:</strong> {{ $booking->whatsapp_number }}</p>
                    <p class="mb-0">
                        <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $booking->whatsapp_number) }}" target="_blank" class="admin-btn admin-btn--outline admin-btn--sm">
                            Hubungi via WhatsApp
                        </a>
                    </p>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head"><h2>Catatan Pelanggan</h2></div>
                <div class="admin-card__body">
                    <p class="mb-0" style="color: {{ $booking->note ? 'var(--so-text)' : 'var(--so-text-muted)' }}">
                        {{ $booking->note ?: 'Tidak ada catatan tambahan dari pelanggan.' }}
                    </p>
                </div>
            </div>
        </div>

        <div class="col-lg-5">
            <div class="admin-card mb-3">
                <div class="admin-card__head"><h2>Jadwal</h2></div>
                <div class="admin-card__body">
                    <p class="mb-2"><strong>Tanggal:</strong> {{ $booking->preferred_date->translatedFormat('d F Y') }}</p>
                    <p class="mb-0"><strong>Jam:</strong> {{ $booking->preferred_time }}</p>
                </div>
            </div>

            <div class="admin-card">
                <div class="admin-card__head"><h2>Update Status</h2></div>
                <div class="admin-card__body">
                    <form action="{{ route('admin.eye-exam-bookings.updateStatus', $booking) }}" method="POST">
                        @csrf
                        @method('PATCH')
                        <div class="admin-field">
                            <select name="status" class="admin-select">
                                @foreach(['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'] as $status)
                                    <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
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
