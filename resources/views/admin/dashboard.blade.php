@extends('layouts.admin')

@section('title', 'Dashboard - Admin Sardio Optik')

@section('content')
    <h3 class="mb-4">Dashboard Overview</h3>

    <div class="row g-3">
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Total Order</div>
                <div class="fs-3 fw-bold">{{ $totalOrders }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Pending</div>
                <div class="fs-3 fw-bold text-warning">{{ $pending }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Diproses</div>
                <div class="fs-3 fw-bold text-info">{{ $diproses }}</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card p-3">
                <div class="text-muted small">Selesai</div>
                <div class="fs-3 fw-bold text-success">{{ $selesai }}</div>
            </div>
        </div>
    </div>

    <div class="mt-4">
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary">Kelola Order</a>
    </div>
@endsection
