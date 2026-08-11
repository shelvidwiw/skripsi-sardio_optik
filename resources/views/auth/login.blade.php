@extends('layouts.app')

@section('title', 'Masuk - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-auth-page">
    <div class="so-container" style="display:flex; justify-content:center;">
        <div class="so-auth-card">
            <div class="so-auth-card__head">
                <h1>Masuk ke Akun</h1>
                <p>Kelola pesanan Anda dengan aman.</p>
            </div>

            <form method="POST" action="{{ route('login.submit') }}">
                @csrf

                <label class="so-field-label">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" class="so-field-input" placeholder="nama@email.com" required autofocus>

                <label class="so-field-label">Password</label>
                <input type="password" name="password" class="so-field-input" placeholder="Password" required>

                <div class="so-auth-check">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Ingat saya</label>
                </div>

                <button type="submit" class="so-btn so-btn--primary so-btn--block">Masuk</button>
            </form>

            <div class="so-auth-card__footer">
                Belum punya akun? <a href="{{ route('register') }}">Daftar sekarang</a>
            </div>
        </div>
    </div>
</div>
@endsection
