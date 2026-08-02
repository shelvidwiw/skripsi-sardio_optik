@extends('layouts.admin')

@section('title', 'Eye Exam Bookings - Admin Sardio Optik')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="mb-0">Booking Pemeriksaan Mata</h3>
        <form method="GET" class="d-flex gap-2">
            <input type="text" name="search" value="{{ request('search') }}" class="form-control form-control-sm" placeholder="Cari nama / kode booking">
            <button class="btn btn-sm btn-outline-secondary">Cari</button>
        </form>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="card">
        <table class="table mb-0 align-middle">
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
                        <td>{{ $booking->preferred_date->format('d M Y') }} &middot; {{ $booking->preferred_time }}</td>
                        <td>
                            @php
                                $badge = [
                                    'pending' => 'warning',
                                    'dikonfirmasi' => 'info',
                                    'selesai' => 'success',
                                    'dibatalkan' => 'danger',
                                ][$booking->status] ?? 'secondary';
                            @endphp
                            <span class="badge bg-{{ $badge }}">{{ ucfirst($booking->status) }}</span>
                        </td>
                        <td>
                            <form action="{{ route('admin.eye-exam-bookings.updateStatus', $booking) }}" method="POST" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="status" class="form-select form-select-sm" style="width:150px;">
                                    @foreach(['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'] as $status)
                                        <option value="{{ $status }}" @selected($booking->status === $status)>{{ ucfirst($status) }}</option>
                                    @endforeach
                                </select>
                                <button class="btn btn-sm btn-outline-primary">Simpan</button>
                            </form>
                            @if($booking->note)
                                <div class="small text-muted mt-1">Catatan: {{ $booking->note }}</div>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-center text-muted py-4">Belum ada booking pemeriksaan mata.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $bookings->links() }}
    </div>
@endsection
