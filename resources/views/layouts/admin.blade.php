<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin - Sardio Optik')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/style.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="d-flex">
        <aside class="admin-sidebar bg-dark text-white vh-100 p-3" style="width:220px; position:fixed;">
            <h5 class="mb-4">Sardio Optik<br><small class="text-white-50">Admin Dashboard</small></h5>
            <nav class="nav flex-column gap-1">
                <a class="nav-link text-white {{ request()->routeIs('admin.dashboard') ? 'fw-bold' : '' }}" href="{{ route('admin.dashboard') }}">Dashboard</a>
                <a class="nav-link text-white {{ request()->routeIs('admin.orders.*') ? 'fw-bold' : '' }}" href="{{ route('admin.orders.index') }}">Orders</a>
                <a class="nav-link text-white {{ request()->routeIs('admin.products.*') ? 'fw-bold' : '' }}" href="{{ route('admin.products.index') }}">Products</a>
                <a class="nav-link text-white {{ request()->routeIs('admin.eye-exam-bookings.*') ? 'fw-bold' : '' }}" href="{{ route('admin.eye-exam-bookings.index') }}">Eye Exam Bookings</a>
                <form method="POST" action="{{ route('admin.logout') }}" class="mt-4">
                    @csrf
                    <button class="btn btn-outline-light btn-sm w-100">Logout</button>
                </form>
            </nav>
        </aside>

        <div style="margin-left:220px; width:100%;" class="p-4">
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

            @yield('content')
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
