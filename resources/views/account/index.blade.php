@extends('layouts.app')

@section('title', 'Akun Saya - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/auth.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-account-page">
    <div class="so-container">

        <div class="so-account-head">
            <div>
                <h1>Akun Saya</h1>
                <p>Kelola profil dan pantau riwayat pesanan Anda.</p>
            </div>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="so-btn so-btn--outline">Keluar</button>
            </form>
        </div>

        <div class="so-account-card">
            <h5>{{ $user->name }}</h5>
            <p class="text-muted mb-0">{{ $user->email }}</p>
        </div>

        <div class="so-account-card">
            <h5 class="mb-3">Riwayat Pesanan</h5>

            @if($orders->isEmpty())
                <div class="so-account-orders-empty">
                    Anda belum memiliki pesanan. <a href="{{ route('products.index') }}">Mulai belanja &rarr;</a>
                </div>
            @else
                @foreach($orders as $order)
                    <div class="so-order-row">
                        <div>
                            <div class="so-order-row__code">{{ $order->order_code }}</div>
                            <div class="so-order-row__meta">
                                {{ $order->created_at->translatedFormat('d M Y, H:i') }} &middot; {{ $order->items->count() }} item
                            </div>
                        </div>
                        <span class="so-order-status so-order-status--{{ $order->status }}">{{ $order->status }}</span>
                        <div class="so-order-row__total">Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}</div>
                    </div>
                @endforeach
            @endif
        </div>

    </div>
</div>
@endsection
