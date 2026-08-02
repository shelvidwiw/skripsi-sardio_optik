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
                <a href="#" class="so-navbar__link is-disabled" onclick="return false;" title="Segera hadir">Store</a>
                <a href="{{ route('eye-exam.index') }}" class="so-navbar__link {{ request()->routeIs('eye-exam.*') ? 'is-active' : '' }}">Consultation</a>
            </nav>

            <div class="so-navbar__icons">
                <button type="button" class="so-icon-btn is-disabled" onclick="return false;" title="Segera hadir" aria-label="Search">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                </button>
                <button type="button" class="so-icon-btn is-disabled" onclick="return false;" title="Segera hadir" aria-label="Track Order">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="1" y="7" width="14" height="10" rx="1"/><path d="M15 10h3l4 4v3h-7z"/><circle cx="6" cy="19" r="1.6"/><circle cx="18" cy="19" r="1.6"/></svg>
                </button>
                <a href="{{ route('cart.index') }}" class="so-icon-btn so-icon-btn--cart" aria-label="Cart">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                    @php $cartCount = collect(session('cart', []))->sum('quantity'); @endphp
                    @if($cartCount > 0)
                        <span class="so-icon-btn__badge">{{ $cartCount }}</span>
                    @endif
                </a>
                <button type="button" class="so-icon-btn is-disabled" onclick="return false;" title="Segera hadir" aria-label="User">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/></svg>
                </button>
            </div>
        </div>
    </header>

    @if(session('success') || session('error') || $errors->any())
        <div class="so-container so-flash">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <main>
        @yield('content')
    </main>

    @include('partials.quick-view-modal')

    <footer class="so-footer">
        <div class="so-footer__inner">
            <div class="so-footer__col">
                <div class="so-footer__brand">Sardio Optik</div>
                <p>Defining the standard of excellence in optical care and premium eyewear design since 2008.</p>
                <div class="so-footer__social">
                    <span class="so-footer__social-icon">f</span>
                    <span class="so-footer__social-icon">in</span>
                    <span class="so-footer__social-icon">ig</span>
                </div>
            </div>
            <div class="so-footer__col">
                <h6>Quick Links</h6>
                <ul>
                    <li><a href="#" onclick="return false;">Privacy Policy</a></li>
                    <li><a href="#" onclick="return false;">Terms of Service</a></li>
                    <li><a href="#" onclick="return false;">Shipping Info</a></li>
                    <li><a href="#" onclick="return false;">Returns</a></li>
                    <li><a href="#" onclick="return false;">Contact Us</a></li>
                </ul>
            </div>
            <div class="so-footer__col">
                <h6>Support</h6>
                <ul>
                    <li><a href="#" onclick="return false;">Help Center</a></li>
                    <li><a href="#" onclick="return false;">Frame Sizing Guide</a></li>
                    <li><a href="#" onclick="return false;">Lens Guide</a></li>
                    <li><a href="#" onclick="return false;">Insurance Partners</a></li>
                    <li><a href="#" onclick="return false;">Track Order</a></li>
                </ul>
            </div>
            <div class="so-footer__col">
                <h6>Newsletter</h6>
                <p>Subscribe for early access to new collections and vision health tips.</p>
                <form class="so-footer__newsletter" onsubmit="return false;">
                    <input type="email" placeholder="Email Address">
                    <button type="submit">Join</button>
                </form>
            </div>
        </div>
        <div class="so-footer__bottom">
            &copy; {{ date('Y') }} Sardio Optik. All rights reserved. Precision in every lens.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="{{ asset('js/quick-view.js') }}"></script>
    @stack('scripts')
</body>
</html>
