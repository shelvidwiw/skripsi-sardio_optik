@extends('layouts.app')

@section('title', 'Product Catalog - Sardio Optik')

@push('styles')
    <link href="{{ asset('css/products.css') }}" rel="stylesheet">
@endpush

@section('content')
<div class="so-product-page">
    <div class="so-container so-container--wide">

        {{-- BREADCRUMB --}}
        <nav class="so-breadcrumb">
            <a href="{{ route('home') }}">Home</a>
            <span>&rsaquo;</span>
            <span class="so-breadcrumb__current">Product Catalog</span>
        </nav>

        <form method="GET" action="{{ route('products.index') }}" id="soFilterForm">
        <div class="so-product-layout">

            {{-- ===================== SIDEBAR FILTER (real, connect ke database) ===================== --}}
            <aside class="so-filter-card">
                <div class="so-filter-card__head">
                    <h6>Filters</h6>
                    <a href="{{ route('products.index') }}" class="so-filter-reset">Reset</a>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Category</div>
                    <div class="so-filter-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" class="so-filter-search__input" data-filter-list="soCategoryList" placeholder="Cari category...">
                    </div>
                    <div class="so-filter-options" id="soCategoryList">
                        @forelse($categories as $cat)
                            <label class="so-checkbox">
                                <input type="checkbox" name="category[]" value="{{ $cat }}"
                                    {{ in_array($cat, (array) request('category', [])) ? 'checked' : '' }}
                                    class="so-auto-submit">
                                {{ $cat }}
                            </label>
                        @empty
                            <p class="so-filter-empty">Belum ada kategori.</p>
                        @endforelse
                    </div>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Brand</div>
                    <div class="so-filter-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" class="so-filter-search__input" data-filter-list="soBrandList" placeholder="Cari brand...">
                    </div>
                    <div class="so-filter-options" id="soBrandList">
                        @forelse($brands as $brand)
                            <label class="so-checkbox">
                                <input type="checkbox" name="brand[]" value="{{ $brand }}"
                                    {{ in_array($brand, (array) request('brand', [])) ? 'checked' : '' }}
                                    class="so-auto-submit">
                                {{ $brand }}
                            </label>
                        @empty
                            <p class="so-filter-empty">Belum ada brand. Isi kolom Brand saat menambah produk di Admin.</p>
                        @endforelse
                    </div>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Price Range</div>
                    <input type="range" name="max_price" min="0" max="{{ $priceCeiling }}" step="10000"
                           value="{{ $maxPrice }}" class="so-range" id="soPriceRange">
                    <div class="so-range__labels">
                        <span>Rp 0</span>
                        <span id="soPriceRangeValue">Rp {{ number_format($maxPrice, 0, ',', '.') }}</span>
                    </div>
                </div>
            </aside>

            {{-- ===================== CONTENT ===================== --}}
            <div class="so-product-content">

                {{-- TOP BAR --}}
                <div class="so-topbar">
                    <div class="so-search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Search for frames, lenses, or brands...">
                        <button type="submit" class="so-search-box__btn">Search</button>
                    </div>
                    <div class="so-sortbox">
                        <label for="soSort">Sort by:</label>
                        <select name="sort" id="soSort" class="so-auto-submit">
                            <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Newest Arrivals</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Name: A-Z</option>
                        </select>
                    </div>
                </div>

                {{-- PRODUCT GRID --}}
                @php
                    // Foto kacamata studio sungguhan untuk produk yang belum diupload gambarnya
                    // lewat Admin > Products, supaya tampilannya tetap semirip referensi (bukan
                    // kotak placeholder teks polos). Ganti array ini kapan saja / hapus begitu
                    // semua produk sudah punya foto asli.
                    $soFallbackPhotos = [
                        'https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80',
                        'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80',
                        'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&q=80',
                        'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=600&q=80',
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&q=80',
                        'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600&q=80',
                    ];
                @endphp
                <div class="so-product-catalog-grid">
                    @forelse($products as $product)
                        @php
                            $soFallbackImage = $soFallbackPhotos[$product->id % count($soFallbackPhotos)];

                            $galleryImages = collect($product->images ?? [])
                                ->map(fn ($img) => asset('storage/'.$img))
                                ->values();

                            if ($galleryImages->isEmpty()) {
                                $galleryImages = collect([$soFallbackImage]);
                            }
                        @endphp
                        <div class="so-catalog-card"
                             data-id="{{ $product->id }}"
                             data-name="{{ $product->name }}"
                             data-category="{{ $product->category ?? 'Sardio Optik' }}"
                             data-price="Rp {{ number_format($product->price, 0, ',', '.') }}"
                             data-description="{{ \Illuminate\Support\Str::limit($product->description ?? '', 220) }}"
                             data-images="{{ $galleryImages->toJson() }}"
                             data-stock="{{ $product->stock }}"
                             data-url="{{ route('products.show', $product) }}"
                             data-add-url="{{ route('cart.add', $product) }}"
                     data-needs-prescription="{{ $product->needsPrescription() ? '1' : '0' }}">

                            <div class="so-catalog-card__image">
                                <a href="{{ route('products.show', $product) }}" class="so-catalog-card__image-link">
                                    @if($product->main_image)
                                        <img src="{{ asset('storage/'.$product->main_image) }}" alt="{{ $product->name }}">
                                    @else
                                        <img src="{{ $soFallbackImage }}" alt="{{ $product->name }}">
                                    @endif
                                </a>
                                <button type="button" class="so-quickview-btn" aria-label="Quick View" title="Quick View">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>

                            <div class="so-catalog-card__body">
                                <div class="so-catalog-card__brand">{{ strtoupper($product->category ?? 'Sardio Optik') }}</div>
                                <a href="{{ route('products.show', $product) }}" class="so-catalog-card__name">{{ $product->name }}</a>
                                <div class="so-catalog-card__price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">Belum ada produk.</p>
                    @endforelse
                </div>

                {{-- PAGINATION --}}
                @if($products->hasPages())
                    <div class="so-pagination">
                        {{ $products->onEachSide(1)->links('vendor.pagination.sardio') }}
                    </div>
                @endif

            </div>
        </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
    <script src="{{ asset('js/products.js') }}"></script>
@endpush
