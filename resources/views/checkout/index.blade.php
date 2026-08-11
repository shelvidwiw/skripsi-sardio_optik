@extends('layouts.app')

@section('title', 'Checkout - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/checkout.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-checkout-page">
    <div class="so-container">

        <div class="so-checkout-topbar">
            <a href="{{ route('products.index') }}" class="so-checkout-back">&larr; Back to Products</a>
            <span class="so-checkout-divider">|</span>
            <span class="so-checkout-secure-badge">Secure Checkout</span>
            <span class="so-checkout-ssl">256-bit SSL Encryption</span>
        </div>

        <div class="so-checkout-layout">
            <div class="so-checkout-form-card">
                <div class="so-checkout-step">
                    <span class="so-checkout-step__number">1</span>
                    <h4>Shipping Information</h4>
                </div>

                <form action="{{ route('checkout.store') }}" method="POST" id="soCheckoutForm">
                    @csrf
                    @foreach(array_keys($cart) as $itemKey)
                        <input type="hidden" name="selected_items[]" value="{{ $itemKey }}">
                    @endforeach

                    <label class="so-field-label">Full Name</label>
                    <input type="text" name="customer_name" value="{{ old('customer_name', auth()->user()->name ?? '') }}" class="so-field-input" placeholder="Johnathan Doe" required>

                    <label class="so-field-label">Street Address</label>
                    <input type="text" name="street_address" value="{{ old('street_address') }}" class="so-field-input" placeholder="123 Clarity Avenue">

                    <div class="so-field-row">
                        <div>
                            <label class="so-field-label">City</label>
                            <input type="text" name="city" value="{{ old('city') }}" class="so-field-input" placeholder="Jakarta">
                        </div>
                        <div>
                            <label class="so-field-label">Postal Code</label>
                            <input type="text" name="postal_code" value="{{ old('postal_code') }}" class="so-field-input" placeholder="10110">
                        </div>
                    </div>

                    <label class="so-field-label">Phone Number (WhatsApp)</label>
                    <input type="text" name="whatsapp_number" value="{{ old('whatsapp_number') }}" class="so-field-input" placeholder="+62 812 3456 7890" required>
                </form>
            </div>

            <div class="so-checkout-summary">
                <div class="so-checkout-summary__head">
                    <h5>Order Summary</h5>
                    <span>{{ collect($cart)->sum('quantity') }} items</span>
                </div>

                <div class="so-checkout-summary__items">
                    @foreach($cart as $item)
                        <div class="so-checkout-summary__item">
                            <div class="so-checkout-summary__item-image">
                                @if(!empty($item['image']))
                                    <img src="{{ asset('storage/'.$item['image']) }}" alt="{{ $item['name'] }}">
                                @else
                                    <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&q=80" alt="{{ $item['name'] }}">
                                @endif
                            </div>
                            <div class="so-checkout-summary__item-info">
                                <div class="so-checkout-summary__item-top">
                                    <span class="so-checkout-summary__item-name">{{ $item['name'] }}</span>
                                    <span class="so-checkout-summary__item-price">Rp {{ number_format($item['price'], 0, ',', '.') }}</span>
                                </div>
                                <span class="so-checkout-summary__item-qty">Qty: {{ $item['quantity'] }}</span>
                                @if(!empty($item['prescription']))
                                    @php $rx = $item['prescription']; @endphp
                                    <span class="so-checkout-summary__item-rx">
                                        @if($rx['method'] === 'upload')
                                            Resep: file terupload
                                        @else
                                            Resep: OD {{ $rx['sph_right'] ?? '-' }} / OS {{ $rx['sph_left'] ?? '-' }}
                                        @endif
                                    </span>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="so-checkout-promo">
                    <input type="text" placeholder="PROMO CODE" disabled>
                    <button type="button" class="is-disabled" onclick="return false;" title="Segera hadir">Apply</button>
                </div>

                <div class="so-checkout-summary__row">
                    <span>Subtotal</span>
                    <span>Rp {{ number_format($subtotal, 0, ',', '.') }}</span>
                </div>
                <div class="so-checkout-summary__row">
                    <span>PPN (11%)</span>
                    <span>Rp {{ number_format($tax, 0, ',', '.') }}</span>
                </div>
                <div class="so-checkout-summary__row">
                    <span>Shipping</span>
                    <span class="so-checkout-summary__free">Free</span>
                </div>

                <div class="so-checkout-summary__total">
                    <span>Grand Total</span>
                    <div>
                        <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
                        <small>SECURE TRANSACTION</small>
                    </div>
                </div>

                <button type="submit" form="soCheckoutForm" class="so-btn so-btn--primary so-btn--block so-checkout-wa-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Order via WhatsApp
                </button>

                <div class="so-checkout-call-bar">
                    Questions? Call +62 21 555 1234
                </div>
            </div>
        </div>
    </div>
</div>
@endsection