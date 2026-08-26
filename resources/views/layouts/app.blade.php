<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sardio Optik')</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    {{-- Bootstrap tetap dipakai untuk halaman internal (produk, cart, checkout, admin) --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    @stack('styles')
</head>
<body>
    <header class="so-navbar">
        <div class="so-navbar__inner">
            <a href="{{ route('home') }}" class="so-navbar__brand">Sardio Optik</a>

            <nav class="so-navbar__menu">
                <a href="{{ route('home') }}" class="so-navbar__link {{ request()->routeIs('home') ? 'is-active' : '' }}">Home</a>
                <a href="{{ route('products.index') }}" class="so-navbar__link {{ request()->routeIs('products.*') ? 'is-active' : '' }}">Product</a>
                <a href="{{ route('store.index') }}" class="so-navbar__link {{ request()->routeIs('store.index') ? 'is-active' : '' }}">Store</a>
                <a href="{{ route('eye-exam.index') }}" class="so-navbar__link {{ request()->routeIs('eye-exam.*') ? 'is-active' : '' }}">Consultation</a>
            </nav>

            <div class="so-navbar__icons">
                <div class="so-navbar-search" id="soNavbarSearch">
                    <button type="button" class="so-icon-btn" id="soSearchToggle" title="Cari Produk" aria-label="Search" aria-expanded="false">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                    </button>
                    <form action="{{ route('products.index') }}" method="GET" class="so-navbar-search__form" id="soNavbarSearchForm">
                        <input type="text" name="q" class="so-navbar-search__input" placeholder="Cari frame, lensa, atau brand..." autocomplete="off" aria-label="Cari produk">
                        <button type="submit" class="so-navbar-search__submit" aria-label="Cari">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        </button>
                    </form>
                </div>
                <a href="{{ route('track-order.index') }}" class="so-icon-btn" title="Lacak Pesanan" aria-label="Lacak Pesanan">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="7" width="14" height="10" rx="1"/><path d="M15 10h3l4 4v3h-7z"/><circle cx="6" cy="19" r="1.6"/><circle cx="18" cy="19" r="1.6"/></svg>
                </a>
                <a href="{{ route('cart.index') }}" class="so-icon-btn so-icon-btn--cart" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="so-icon-btn__badge">{{ $cartCount }}</span>
                    @endif
                </a>
                @auth
                    <a href="{{ route('account.index') }}" class="so-icon-btn" title="Akun Saya" aria-label="Akun Saya">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                    </a>
                @else
                    <a href="{{ route('login') }}" class="so-icon-btn" title="Masuk / Daftar" aria-label="Masuk / Daftar">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                    </a>
                @endauth
            </div>
        </div>
    </header>

    @if($errors->any())
        <div class="so-container so-flash">
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    @endif

    {{-- Data flash success/error dikirim ke toast.js lewat tag ini, bukan alert statis lagi --}}
    @if(session('success') || session('error'))
        <script id="soFlashData" type="application/json">
            {!! json_encode(['success' => session('success'), 'error' => session('error')]) !!}
        </script>
    @endif

    {{-- Toast container: notifikasi muncul dari kiri bawah, otomatis hilang --}}
    <div id="soToastContainer" class="so-toast-container"></div>

    <main>
        @yield('content')
    </main>

    @include('partials.quick-view-modal')

    <footer class="so-footer">
        <div class="so-footer__inner so-footer__inner--simple">
            <div class="so-footer__col">
                <div class="so-footer__brand">Sardio Optik</div>
                <p>Defining the standard of excellence in optical care and premium eyewear design.</p>
            </div>
            <div class="so-footer__col">
                <h6>Kontak</h6>
                <ul>
                    <li><a href="tel:0821432555491">0821-4325-55491</a></li>
                    <li><a href="https://wa.me/6281432555491" target="_blank" rel="noopener">Chat WhatsApp</a></li>
                </ul>
            </div>
            <div class="so-footer__col">
                <h6>Lokasi Cabang</h6>
                <ul class="so-footer__address">
                    <li>Cabang 1: Jalan Mayor Jenderal Donald Isaac Panjaitan 221, Penanggungan, Jawa Timur 65113</li>
                    <li>Cabang 2: Jalan Raya Kepuh 13, Bandungrejosari, Jawa Timur</li>
                </ul>
            </div>
            <div class="so-footer__col">
                <h6>Layanan</h6>
                <ul>
                    <li><a href="{{ route('products.index') }}">Katalog Produk</a></li>
                    <li><a href="{{ route('eye-exam.index') }}">Pemeriksaan Mata</a></li>
                    <li><a href="{{ route('track-order.index') }}">Lacak Pesanan</a></li>
                </ul>
            </div>
        </div>
        <div class="so-footer__bottom">
            &copy; {{ date('Y') }} Sardio Optik. All rights reserved. Precision in every lens.
        </div>
    </footer>

    {{-- Tombol WhatsApp mengambang, tampil di semua halaman (ada di layout utama) --}}
    <a href="https://wa.me/6281432555491?text=Halo%20Sardio%20Optik%2C%20saya%20ingin%20bertanya"
       class="so-wa-float" target="_blank" rel="noopener" aria-label="Chat via WhatsApp" title="Chat via WhatsApp">
        <svg viewBox="0 0 32 32" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
            <path d="M16.001 3C9.107 3 3.5 8.607 3.5 15.5c0 2.36.652 4.566 1.786 6.452L3 29l7.24-2.24A12.44 12.44 0 0 0 16.001 28C22.895 28 28.5 22.393 28.5 15.5S22.895 3 16.001 3Zm0 22.7c-1.98 0-3.83-.55-5.41-1.5l-.388-.23-4.297 1.33 1.36-4.19-.252-.402A10.16 10.16 0 0 1 5.8 15.5c0-5.63 4.573-10.2 10.2-10.2 5.628 0 10.2 4.573 10.2 10.2 0 5.628-4.572 10.2-10.199 10.2Zm5.593-7.646c-.306-.153-1.812-.894-2.093-.996-.28-.102-.485-.153-.688.153-.204.306-.79.996-.968 1.2-.178.204-.357.23-.663.077-.306-.153-1.293-.477-2.463-1.52-.91-.812-1.524-1.815-1.703-2.12-.178-.306-.019-.472.134-.624.137-.137.306-.357.459-.535.153-.179.204-.306.306-.51.102-.204.05-.383-.026-.536-.077-.153-.688-1.659-.943-2.272-.248-.596-.5-.516-.688-.526-.178-.008-.383-.01-.587-.01-.204 0-.536.077-.816.383-.28.306-1.07 1.046-1.07 2.552 0 1.506 1.096 2.96 1.249 3.164.153.204 2.157 3.294 5.226 4.62.73.315 1.3.503 1.744.644.733.233 1.4.2 1.927.121.588-.088 1.812-.74 2.067-1.454.255-.715.255-1.327.179-1.454-.077-.128-.28-.204-.587-.357Z"/>
        </svg>
    </a>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/quick-view.js') }}"></script>
    <script src="{{ asset('js/toast.js') }}"></script>
    <script src="{{ asset('js/navbar-search.js') }}"></script>
    @stack('scripts')
</body>
</html>