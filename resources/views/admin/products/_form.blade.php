@php $product = $product ?? null; @endphp

<div class="admin-field">
    <label class="admin-label">Product Name</label>
    <input type="text" name="name" value="{{ old('name', $product->name ?? '') }}" class="admin-input" required>
</div>

<div class="admin-field">
    <label class="admin-label">Category</label>
    <select name="category" class="admin-select">
        <option value="">-- Pilih Kategori --</option>
        @foreach(\App\Models\Product::CATEGORIES as $cat)
            <option value="{{ $cat }}" @selected(old('category', $product->category ?? '') === $cat)>{{ $cat }}</option>
        @endforeach
    </select>
    <div class="admin-hint">
        Lensa &amp; Soflens otomatis menampilkan form resep (minus/cylinder atau upload resep) di halaman produk.
        Frame &amp; Cairan Soflens tidak butuh resep.
    </div>
</div>

<div class="admin-field">
    <label class="admin-label">Brand</label>
    <input type="text" name="brand" value="{{ old('brand', $product->brand ?? '') }}" class="admin-input" placeholder="mis. Sardio Signature, Ray-Ban, Oakley">
</div>

<div class="row">
    <div class="col-6 admin-field">
        <label class="admin-label">Price (Rp)</label>
        <input type="number" step="0.01" name="price" value="{{ old('price', $product->price ?? '') }}" class="admin-input" required>
    </div>
    <div class="col-6 admin-field">
        <label class="admin-label">Stock</label>
        <input type="number" name="stock" value="{{ old('stock', $product->stock ?? 0) }}" class="admin-input" required>
    </div>
</div>

<div class="admin-field">
    <label class="admin-label">Description</label>
    <textarea name="description" rows="3" class="admin-textarea">{{ old('description', $product->description ?? '') }}</textarea>
</div>

<div class="admin-field">
    <label class="admin-label">Product Images</label>
    <input type="file" name="images[]" class="admin-input" multiple accept="image/*">
    <div class="admin-hint">Bisa pilih beberapa gambar sekaligus. @if($product) Upload baru akan mengganti gambar lama. @endif</div>

    @if($product && $product->images)
        <div class="d-flex gap-2 mt-2">
            @foreach($product->images as $img)
                <img src="{{ asset('storage/'.$img) }}" style="width:56px;height:56px;object-fit:cover;border-radius:8px;border:1px solid var(--so-border);">
            @endforeach
        </div>
    @endif
</div>
