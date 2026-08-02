@extends('layouts.app')

@section('title', 'Pemeriksaan Mata - Sardio Optik')

@section('content')
<div class="container py-5">
    <div class="row g-5">
        <div class="col-lg-6">
            <span class="text-uppercase small fw-semibold text-primary">Layanan Klinis</span>
            <h2 class="mb-3">Pemeriksaan Mata</h2>
            <p class="text-muted">
                Sebelum membeli lensa atau soflens, disarankan melakukan pemeriksaan mata terlebih dahulu
                supaya ukuran minus, cylinder, dan axis yang Anda masukkan saat belanja benar-benar akurat.
                Booking jadwal pemeriksaan di bawah ini, tim kami akan konfirmasi lewat WhatsApp.
            </p>

            <ul class="list-unstyled mt-4">
                <li class="mb-3 d-flex gap-2">
                    <span>👁️</span>
                    <span>Pemeriksaan refraksi (minus, plus, silinder) oleh optometris berpengalaman.</span>
                </li>
                <li class="mb-3 d-flex gap-2">
                    <span>📋</span>
                    <span>Hasil pemeriksaan bisa langsung dipakai untuk isi resep saat beli Lensa atau Soflens di toko.</span>
                </li>
                <li class="mb-3 d-flex gap-2">
                    <span>⏱️</span>
                    <span>Durasi pemeriksaan sekitar 20–30 menit.</span>
                </li>
            </ul>

            <div class="card p-3 bg-light border mt-4">
                <h6 class="mb-2">Lokasi</h6>
                <p class="mb-0 small text-muted">123 Clinical Way, Optics District, NY 10001</p>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card p-4">
                <h5 class="mb-3">Booking Jadwal Pemeriksaan</h5>

                @if(session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <form action="{{ route('eye-exam.store') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ old('name') }}" class="form-control @error('name') is-invalid @enderror" required>
                        @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nomor WhatsApp</label>
                        <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" placeholder="08xxxxxxxxxx" class="form-control @error('whatsapp_number') is-invalid @enderror" required>
                        @error('whatsapp_number') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="row g-2">
                        <div class="col-6 mb-3">
                            <label class="form-label">Tanggal</label>
                            <input type="date" name="preferred_date" value="{{ old('preferred_date') }}" min="{{ now()->format('Y-m-d') }}" class="form-control @error('preferred_date') is-invalid @enderror" required>
                            @error('preferred_date') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                        <div class="col-6 mb-3">
                            <label class="form-label">Jam</label>
                            <select name="preferred_time" class="form-select @error('preferred_time') is-invalid @enderror" required>
                                <option value="">-- Pilih Jam --</option>
                                @foreach(['09:00 - 10:00','10:00 - 11:00','11:00 - 12:00','13:00 - 14:00','14:00 - 15:00','15:00 - 16:00','16:00 - 17:00'] as $slot)
                                    <option value="{{ $slot }}" @selected(old('preferred_time') === $slot)>{{ $slot }}</option>
                                @endforeach
                            </select>
                            @error('preferred_time') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Catatan (opsional)</label>
                        <textarea name="note" rows="3" class="form-control @error('note') is-invalid @enderror" placeholder="mis. keluhan mata, riwayat kacamata sebelumnya">{{ old('note') }}</textarea>
                        @error('note') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Booking Sekarang</button>
                    <p class="text-muted small mt-2 mb-0">Setelah menekan tombol ini, Anda akan diarahkan ke WhatsApp Admin untuk konfirmasi jadwal.</p>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
