@extends('layouts.app')

@section('title', 'Keranjang - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/cart.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-cart-page">
    <div class="so-container">
        <h2 class="so-cart-title">Keranjang Belanja</h2>
        <p class="so-cart-subtitle">
            @if(empty($cart))
                Keranjang Anda kosong.
            @else
                Anda memiliki {{ collect($cart)->sum('quantity') }} item dalam keranjang
            @endif
        </p>

        @if(empty($cart))
            <div class="so-cart-empty">
                <p>Belum ada produk di keranjang.</p>
                <a href="{{ route('products.index') }}" class="so-btn so-btn--primary">Mulai Belanja</a>
            </div>
        @else
            <div class="so-cart-layout">
                <div class="so-cart-items">
                    @foreach($cart as $key => $item)
                        <div class="so-cart-item">
                            <div class="so-cart-item__image">
                                @if(!empty($item['image']))
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1577803645773-f96470509666?w=300&q=80" alt="{{ $item['name'] }}">
                                @endif
                            </div>

                            <div class="so-cart-item__body">
                                <div class="so-cart-item__top">
                                    <div>
                                        <a href="#" class="so-cart-item__name">{{ $item['name'] }}</a>
                                        @if(!empty($item['prescription']))
                                            @php $rx = $item['prescription']; @endphp
                                            <div class="so-cart-item__variant">
                                                @if($rx['method'] === 'upload')
                                                    Resep: file terupload
                                                @else
                                                    Resep: OD {{ $rx['sph_right'] ?? '-' }} / OS {{ $rx['sph_left'] ?? '-' }}
                                                    @if($rx['type'] === 'full')
                                                        (Cyl {{ $rx['cyl_right'] ?? '-' }} / {{ $rx['cyl_left'] ?? '-' }})
                                                    @endif
                                                @endif
                                            </div>
                                        @endif
                                    </div>
                                    <form action="{{ route('cart.remove', $key) }}" method="POST" class="so-cart-item__remove-form">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="so-cart-item__remove" aria-label="Hapus produk" title="Hapus">
                                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M8 6V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2m3 0-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/></svg>
                                        </button>
                                    </form>
                                </div>

                                <div class="so-cart-item__bottom">
                                    <form action="{{ route('cart.update', $key) }}" method="POST" class="so-qty-form">
                                        @csrf
                                        @method('PATCH')
                                        <div class="so-qty-selector">
                                            <button type="button" class="so-qty-minus" aria-label="Kurangi jumlah">&minus;</button>
                                            <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" class="so-qty-input" readonly>
                                            <button type="button" class="so-qty-plus" aria-label="Tambah jumlah">&plus;</button>
                                        </div>
                                    </form>
                                    <span class="so-cart-item__price">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div>
                    <div class="so-cart-summary">
                        <h5>Ringkasan Pesanan</h5>

                        <div class="so-cart-summary__row">
                            <span>Subtotal</span>
                            <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                        </div>
                        <div class="so-cart-summary__row">
                            <span>Biaya Pengiriman</span>
                            <span class="so-cart-summary__note">Dihitung saat checkout</span>
                        </div>
                        <div class="so-cart-summary__row">
                            <span>Pajak (PPN 11%)</span>
                            <span>Rp {{ number_format(round($subtotal * 0.11), 0, ',', '.') }}</span>
                        </div>

                        <hr>

                        <div class="so-cart-summary__row so-cart-summary__row--total">
                            <span>Total Pesanan</span>
                            <span>Rp {{ number_format($subtotal + round($subtotal * 0.11), 0, ',', '.') }}</span>
                        </div>

                        <a href="{{ route('checkout.index') }}" class="so-btn so-btn--primary so-btn--block so-cart-checkout-btn">
                            CHECKOUT NOW
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M5 12h14M13 6l6 6-6 6"/></svg>
                        </a>

                        <div class="so-cart-trust">
                            <div class="so-cart-trust__item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 4 5v6c0 5 3.5 8.7 8 11 4.5-2.3 8-6 8-11V5l-8-3Z"/></svg>
                                <div>
                                    <strong>Secure Payment</strong>
                                    <span>Your data is always protected</span>
                                </div>
                            </div>
                            <div class="so-cart-trust__item">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="1" y="7" width="14" height="10" rx="1"/><path d="M15 10h3l4 4v3h-7z"/><circle cx="6" cy="19" r="1.6"/><circle cx="18" cy="19" r="1.6"/></svg>
                                <div>
                                    <strong>Express Delivery</strong>
                                    <span>Fast &amp; reliable shipping</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Upsell banner: tampilan saja, belum ada backend add-on/lapisan tambahan di Iterasi 1 --}}
            <div class="so-cart-upsell">
                <div class="so-cart-upsell__icon">&#10024;</div>
                <div class="so-cart-upsell__text">
                    <strong>Tambahkan Lapisan Anti-Reflektif</strong>
                    <span>Tingkatkan kejernihan penglihatan hanya Rp 250.000</span>
                </div>
                <button type="button" class="so-btn so-btn--secondary is-disabled" onclick="return false;" title="Segera hadir">TAMBAHKAN</button>
            </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.so-qty-form').forEach(function (form) {
        var input = form.querySelector('.so-qty-input');
        var minus = form.querySelector('.so-qty-minus');
        var plus = form.querySelector('.so-qty-plus');

        minus.addEventListener('click', function () {
            var value = parseInt(input.value, 10) || 1;
            if (value > 1) {
                input.value = value - 1;
                form.submit();
            }
        });
        plus.addEventListener('click', function () {
            var value = parseInt(input.value, 10) || 1;
            input.value = value + 1;
            form.submit();
        });
    });
});
</script>
@endpush
