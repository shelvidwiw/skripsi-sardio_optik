@extends('layouts.app')

@section('title', $product->name.' - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/product-detail.css') }}" rel="stylesheet">
@endpush

@section('content')
@php
    $rxType = $product->prescriptionType();
    $galleryImages = collect($product->images ?? [])->map(fn ($img) => asset('storage/'.$img))->values();
    if ($galleryImages->isEmpty()) {
        $galleryImages = collect(['https://images.unsplash.com/photo-1577803645773-f96470509666?w=800&q=80']);
    }
@endphp

<div class="so-detail-page">
    <div class="so-container">

        <nav class="so-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>&rsaquo;</span>
            <a href="{{ route('products.index', ['category' => [$product->category]]) }}">{{ $product->category }}</a>
            <span>&rsaquo;</span>
            <span class="so-breadcrumb__current">{{ $product->name }}</span>
        </nav>

        <div class="so-detail-layout">
            {{-- GALLERY --}}
            <div class="so-detail-gallery">
                <div class="so-detail-gallery__main">
                    <span class="so-detail-badge">BEST SELLER</span>
                    <img src="{{ $galleryImages->first() }}" alt="{{ $product->name }}" id="soDetailMainImage">
                </div>
                @if($galleryImages->count() > 1)
                    <div class="so-detail-gallery__thumbs">
                        @foreach($galleryImages as $img)
                            <img src="{{ $img }}" alt="" class="so-detail-thumb {{ $loop->first ? 'is-active' : '' }}" onclick="document.getElementById('soDetailMainImage').src=this.src; document.querySelectorAll('.so-detail-thumb').forEach(t=>t.classList.remove('is-active')); this.classList.add('is-active');">
                        @endforeach
                    </div>
                @endif
            </div>

            {{-- INFO --}}
            <div class="so-detail-info">
                <h1>{{ $product->name }}</h1>
                <div class="so-detail-price-row">
                    <span class="so-detail-price">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                    <span class="so-detail-rating">&#9733;&#9733;&#9733;&#9733;&#9734; (48 Reviews)</span>
                </div>

                <p class="so-detail-description">{{ $product->description }}</p>

                @if($product->category !== \App\Models\Product::CATEGORY_CAIRAN_SOFLENS)
                    <div class="so-detail-colors">
                        <div class="so-detail-colors__label">FRAME COLOR: <span>MATTE ONYX</span></div>
                        <div class="so-color-swatches">
                            <button type="button" class="so-color-swatch is-active" style="background:#12151c;"></button>
                            <button type="button" class="so-color-swatch" style="background:#1c2a4a;"></button>
                            <button type="button" class="so-color-swatch" style="background:#aab3c5;"></button>
                        </div>
                    </div>
                    <p class="so-detail-note">*Pilihan warna masih tampilan saja, belum tersimpan sebagai varian produk terpisah.</p>
                @endif

                <form action="{{ route('cart.add', $product) }}" method="POST" enctype="multipart/form-data" id="soDetailForm">
                    @csrf

                    <div class="so-detail-qty-row">
                        <label>Quantity</label>
                        <div class="so-qty-selector">
                            <button type="button" class="so-qty-minus" aria-label="Kurangi jumlah">&minus;</button>
                            <input type="number" name="quantity" id="soDetailQty" value="1" min="1" max="{{ $product->stock }}" class="so-qty-input" readonly>
                            <button type="button" class="so-qty-plus" aria-label="Tambah jumlah">&plus;</button>
                        </div>
                    </div>

                    @if($rxType)
                        <div class="so-detail-rx-card">
                            <h6>Resep {{ $rxType === 'full' ? 'Lensa' : 'Soflens' }} <span class="so-required">*</span></h6>

                            <div class="so-rx-method-toggle">
                                <label class="is-active">
                                    <input type="radio" name="prescription_method" id="rxManual" value="manual" checked>
                                    Isi Manual
                                </label>
                                <label>
                                    <input type="radio" name="prescription_method" id="rxUpload" value="upload">
                                    Upload Resep
                                </label>
                            </div>

                            <div id="rxManualFields" class="so-rx-fields">
                                <div class="so-field-row">
                                    <div>
                                        <label class="so-field-label">SPH Kanan (OD)</label>
                                        <input type="number" step="0.25" name="sph_right" class="so-field-input" placeholder="mis. -1.50">
                                    </div>
                                    <div>
                                        <label class="so-field-label">SPH Kiri (OS)</label>
                                        <input type="number" step="0.25" name="sph_left" class="so-field-input" placeholder="mis. -1.25">
                                    </div>
                                </div>

                                @if($rxType === 'full')
                                    <div class="so-field-row">
                                        <div>
                                            <label class="so-field-label">CYL Kanan</label>
                                            <input type="number" step="0.25" name="cyl_right" class="so-field-input" placeholder="opsional">
                                        </div>
                                        <div>
                                            <label class="so-field-label">CYL Kiri</label>
                                            <input type="number" step="0.25" name="cyl_left" class="so-field-input" placeholder="opsional">
                                        </div>
                                    </div>
                                    <div class="so-field-row">
                                        <div>
                                            <label class="so-field-label">Axis Kanan</label>
                                            <input type="number" min="0" max="180" name="axis_right" class="so-field-input" placeholder="0-180">
                                        </div>
                                        <div>
                                            <label class="so-field-label">Axis Kiri</label>
                                            <input type="number" min="0" max="180" name="axis_left" class="so-field-input" placeholder="0-180">
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div id="rxUploadField" class="so-rx-fields" style="display:none;">
                                <label class="so-field-label">Upload Prescription</label>
                                <input type="file" name="prescription_file" class="so-field-input" accept="image/*,.pdf">
                            </div>

                            <label class="so-field-label">Catatan (opsional)</label>
                            <textarea name="prescription_note" rows="2" class="so-field-input" placeholder="mis. nama pasien, kebutuhan khusus"></textarea>

                            @error('sph_right') <p class="so-field-error">{{ $message }}</p> @enderror
                            @error('sph_left') <p class="so-field-error">{{ $message }}</p> @enderror
                            @error('prescription_file') <p class="so-field-error">{{ $message }}</p> @enderror
                        </div>
                    @elseif($product->category === \App\Models\Product::CATEGORY_FRAME)
                        {{-- Prompt "beli dengan lensa" ini masih tampilan/saran saja --
                             belum ada logic bundling produk di Iterasi 1. --}}
                        <div class="so-detail-bundle-prompt">
                            <div>
                                <strong>Beli dengan lensa?</strong>
                                <span>Lihat pilihan lensa yang tersedia terpisah</span>
                            </div>
                            <a href="{{ route('products.index', ['category' => [\App\Models\Product::CATEGORY_LENSA]]) }}" class="so-btn so-btn--outline">Lihat Lensa</a>
                        </div>
                    @endif

                    @if($product->category !== \App\Models\Product::CATEGORY_CAIRAN_SOFLENS)
                        <a href="#" class="so-btn so-btn--secondary so-btn--block is-disabled" onclick="return false;" title="Segera hadir">Free Online Consultation</a>
                    @endif

                    <div class="so-detail-actions">
                        <button type="submit" name="buy_now" value="0" class="so-btn so-btn--outline so-btn--block" @if($product->stock < 1) disabled @endif>
                            Add to Cart
                        </button>
                        <button type="submit" name="buy_now" value="1" class="so-btn so-btn--primary so-btn--block" @if($product->stock < 1) disabled @endif>
                            Buy Now
                        </button>
                    </div>
                </form>

                <div class="so-detail-trust-row">
                    <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="m9 12 2 2 4-4"/></svg><span>2-Year Warranty</span></div>
                    <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><circle cx="12" cy="12" r="9"/><path d="M12 7v5l3 3"/></svg><span>Fast Glazing</span></div>
                    <div><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6"><rect x="3" y="8" width="18" height="13" rx="2"/><path d="M3 8l9-5 9 5"/></svg><span>Free Shipping</span></div>
                </div>
            </div>
        </div>

        {{-- TABS --}}
        <div class="so-detail-tabs">
            <button type="button" class="so-detail-tab is-active" data-tab="description">Description</button>
            <button type="button" class="so-detail-tab" data-tab="reviews">Reviews (48)</button>
            <button type="button" class="so-detail-tab" data-tab="shipping">Shipping &amp; Returns</button>
        </div>

        <div class="so-detail-tab-panel" id="tab-description">
            <div class="so-detail-tab-grid">
                <div>
                    <h5>Product Specification</h5>
                    <table class="so-spec-table">
                        <tr><td>Category</td><td>{{ $product->category }}</td></tr>
                        <tr><td>Brand</td><td>{{ $product->brand ?? '-' }}</td></tr>
                        <tr><td>Stock</td><td>{{ $product->stock }} unit</td></tr>
                    </table>
                    <p class="so-detail-note">*Spesifikasi teknis lain (material, berat, dsb) belum jadi kolom
                        tersendiri di database — silakan tambahkan di deskripsi produk untuk saat ini.</p>
                </div>
                <div>
                    <h5>Experience Sardio Clarity</h5>
                    <p>{{ $product->description ?: 'Belum ada deskripsi tambahan untuk produk ini.' }}</p>
                </div>
            </div>
        </div>

        <div class="so-detail-tab-panel" id="tab-reviews" style="display:none;">
            <p class="so-detail-note">Fitur ulasan/review belum tersedia di Iterasi 1.</p>
        </div>

        <div class="so-detail-tab-panel" id="tab-shipping" style="display:none;">
            <p class="so-detail-note">Informasi pengiriman &amp; pengembalian belum tersedia di Iterasi 1.</p>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    var qtyInput = document.getElementById('soDetailQty');
    var maxStock = {{ (int) $product->stock ?: 1 }};

    document.querySelector('.so-detail-qty-row .so-qty-minus').addEventListener('click', function () {
        var value = parseInt(qtyInput.value, 10) || 1;
        if (value > 1) qtyInput.value = value - 1;
    });
    document.querySelector('.so-detail-qty-row .so-qty-plus').addEventListener('click', function () {
        var value = parseInt(qtyInput.value, 10) || 1;
        if (value < maxStock) qtyInput.value = value + 1;
    });

    @if($rxType)
    var manualRadio = document.getElementById('rxManual');
    var uploadRadio = document.getElementById('rxUpload');
    var manualFields = document.getElementById('rxManualFields');
    var uploadField = document.getElementById('rxUploadField');

    function toggleRx() {
        var isUpload = uploadRadio.checked;
        manualFields.style.display = isUpload ? 'none' : '';
        uploadField.style.display = isUpload ? '' : 'none';
    }
    manualRadio.addEventListener('change', toggleRx);
    uploadRadio.addEventListener('change', toggleRx);
    @endif

    document.querySelectorAll('.so-detail-tab').forEach(function (tab) {
        tab.addEventListener('click', function () {
            document.querySelectorAll('.so-detail-tab').forEach(function (t) { t.classList.remove('is-active'); });
            document.querySelectorAll('.so-detail-tab-panel').forEach(function (p) { p.style.display = 'none'; });
            tab.classList.add('is-active');
            document.getElementById('tab-' + tab.dataset.tab).style.display = '';
        });
    });
});
</script>
@endpush