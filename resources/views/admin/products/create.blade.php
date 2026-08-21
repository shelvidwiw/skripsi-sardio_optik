@extends('layouts.admin')

@section('title', 'Add Product - Admin Sardio Optik')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Products</a>
    <span> / </span>
    <span class="is-current">Tambah Produk</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Add New Product</h1>
            <p>Isi detail produk yang akan ditampilkan di toko.</p>
        </div>
    </div>

    <div class="admin-card" style="max-width:680px; margin:0 auto;">
        <div class="admin-card__body">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @include('admin.products._form')
                <button type="submit" class="admin-btn admin-btn--primary" style="width:100%; justify-content:center; padding:11px;">Save Product</button>
            </form>
        </div>
    </div>
@endsection
