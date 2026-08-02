@php $product = $product ?? null; @endphp

<div class="mb-3">
    <label class="form-label">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="form-control" required>
</div>

<div class="mb-3">
    <label class="form-label">Category</label>
    <select name="category" class="form-select">
        <option value="">-- Pilih Kategori --</option>
        @foreach(\App\Models\Product::CATEGORIES as $cat)
            <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>
    <div class="form-text">
        Lensa &amp; Soflens otomatis menampilkan form resep (minus/cylinder atau upload resep) di halaman produk.
        Frame &amp; Cairan Soflens tidak butuh resep.
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Brand</label>
    <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}" class="form-control" placeholder="mis. Sardio Signature, Ray-Ban, Oakley">
</div>

<div class="row">
    <div class="col-6 mb-3">
        <label class="form-label">Price (Rp)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="form-control" required>
    </div>
    <div class="col-6 mb-3">
        <label class="form-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="form-control" required>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Description</label>
    <textarea name="description" rows="3" class="form-control">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label class="form-label">Product Images</label>
    <input type="file" name="images[]" class="form-control" multiple accept="image/*">
    <div class="form-text">Bisa pilih beberapa gambar sekaligus. @if($product) Upload baru akan mengganti gambar lama. @endif</div>

    @if($product && $product->images)
        <div class="d-flex gap-2 mt-2">
            @foreach($product->images as $img)
                <img src="{{ asset('storage/'.$img) }}" style="width:60px;height:60px;object-fit:cover;" class="rounded border">
            @endforeach
        </div>
    @endif
</div>
