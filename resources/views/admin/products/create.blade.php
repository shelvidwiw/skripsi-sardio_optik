@extends('layouts.admin')

@section('title', 'Add Product - Admin Sardio Optik')

@section('content')
    <h3 class="mb-4">Add New Product</h3>

    <div class="card p-4" style="max-width:640px;">
        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('admin.products._form')
            <button type="submit" class="btn btn-primary w-100 mt-2">Save Product</button>
        </form>
    </div>
@endsection
