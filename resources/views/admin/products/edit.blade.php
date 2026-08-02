@extends('layouts.admin')

@section('title', 'Edit Product - Admin Sardio Optik')

@section('content')
    <h3 class="mb-4">Edit Product</h3>

    <div class="card p-4" style="max-width:640px;">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            @include('admin.products._form')
            <button type="submit" class="btn btn-primary w-100 mt-2">Update Product</button>
        </form>
    </div>
@endsection
