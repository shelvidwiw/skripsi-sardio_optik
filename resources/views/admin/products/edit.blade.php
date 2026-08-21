@extends('layouts.admin')

@section('title', 'Edit Product - Admin Sardio Optik')

@section('breadcrumb')
    <a href="{{ route('admin.products.index') }}">Products</a>
    <span> / </span>
    <span class="is-current">Edit {{ $product->name }}</span>
@endsection

@section('content')
    <div class="admin-page-head">
        <div>
            <h1>Edit Product</h1>
            <p>Perbarui detail produk "{{ $product->name }}".</p>
        </div>
    </div>

    <div class="admin-card" style="max-width:680px; margin:0 auto;">
        <div class="admin-card__body">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PATCH')
                @include('admin.products._form')
                <button type="submit" class="admin-btn admin-btn--primary" style="width:100%; justify-content:center; padding:11px;">Update Product</button>
            </form>
        </div>
    </div>
@endsection
