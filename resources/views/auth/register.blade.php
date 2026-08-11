@extends('layouts.app')

@section('title', 'Buat Akun - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-auth-page">
    <div class="so-container" style="display:flex; justify-content:center;">
        <div class="so-auth-card">
            <div class="so-auth-card__head">
                <h1>Buat Akun</h1>
                <p>Daftar untuk mempermudah pemesanan &amp; melacak riwayat pesanan Anda.</p>
            </div>

            <form method="POST" action="{{ route('register.submit') }}">
                @csrf

                <label class="so-field-label">Nama Lengkap</label>
                <input type="text" name="name" value="{{ old('name') }}" class="so-field-input" placeholder="Johnathan Doe" required autofocus>

                <label class="so-field-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="so-field-input" placeholder="nama@email.com" required>

                <label class="so-field-label">Password</label>
                <input type="password" name="password" class="so-field-input" placeholder="Minimal 8 karakter" required>

                <label class="so-field-label">Konfirmasi Password</label>
                <input type="password" name="password_confirmation" class="so-field-input" placeholder="Ulangi password" required>

                <button type="submit" class="so-btn so-btn--primary so-btn--block">Daftar</button>
            </form>

            <div class="so-auth-card__footer">
                Sudah punya akun? <a href="{{ route('login') }}">Masuk di sini</a>
            </div>
        </div>
    </div>
</div>
@endsection
