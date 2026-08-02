<?php $__env->startSection('title', 'Product Catalog - Sardio Optik'); ?>

<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(asset('css/products.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="so-product-page">
    <div class="so-container so-container--wide">

        
        <nav class="so-breadcrumb">
            <a href="<?php echo e(route('home')); ?>">Home</a>
            <span>&rsaquo;</span>
            <span class="so-breadcrumb__current">Product Catalog</span>
        </nav>

        <form method="GET" action="<?php echo e(route('products.index')); ?>" id="soFilterForm">
        <div class="so-product-layout">

            
            <aside class="so-filter-card">
                <div class="so-filter-card__head">
                    <h6>Filters</h6>
                    <a href="<?php echo e(route('products.index')); ?>" class="so-filter-reset">Reset</a>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Category</div>
                    <div class="so-filter-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" class="so-filter-search__input" data-filter-list="soCategoryList" placeholder="Cari category...">
                    </div>
                    <div class="so-filter-options" id="soCategoryList">
                        <?php $__empty_1 = true; $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <label class="so-checkbox">
                                <input type="checkbox" name="category[]" value="<?php echo e($cat); ?>"
                                    <?php echo e(in_array($cat, (array) request('category', [])) ? 'checked' : ''); ?>

                                    class="so-auto-submit">
                                <?php echo e($cat); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="so-filter-empty">Belum ada kategori.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Brand</div>
                    <div class="so-filter-search">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" class="so-filter-search__input" data-filter-list="soBrandList" placeholder="Cari brand...">
                    </div>
                    <div class="so-filter-options" id="soBrandList">
                        <?php $__empty_1 = true; $__currentLoopData = $brands; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $brand): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                            <label class="so-checkbox">
                                <input type="checkbox" name="brand[]" value="<?php echo e($brand); ?>"
                                    <?php echo e(in_array($brand, (array) request('brand', [])) ? 'checked' : ''); ?>

                                    class="so-auto-submit">
                                <?php echo e($brand); ?>

                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                            <p class="so-filter-empty">Belum ada brand. Isi kolom Brand saat menambah produk di Admin.</p>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="so-filter-group">
                    <div class="so-filter-group__title">Price Range</div>
                    <input type="range" name="max_price" min="0" max="<?php echo e($priceCeiling); ?>" step="10000"
                           value="<?php echo e($maxPrice); ?>" class="so-range" id="soPriceRange">
                    <div class="so-range__labels">
                        <span>Rp 0</span>
                        <span id="soPriceRangeValue">Rp <?php echo e(number_format($maxPrice, 0, ',', '.')); ?></span>
                    </div>
                </div>
            </aside>

            
            <div class="so-product-content">

                
                <div class="so-topbar">
                    <div class="so-search-box">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path d="m21 21-4.3-4.3"/></svg>
                        <input type="text" name="q" value="<?php echo e(request('q')); ?>" placeholder="Search for frames, lenses, or brands...">
                        <button type="submit" class="so-search-box__btn">Search</button>
                    </div>
                    <div class="so-sortbox">
                        <label for="soSort">Sort by:</label>
                        <select name="sort" id="soSort" class="so-auto-submit">
                            <option value="" <?php echo e(request('sort') == '' ? 'selected' : ''); ?>>Newest Arrivals</option>
                            <option value="price_asc" <?php echo e(request('sort') == 'price_asc' ? 'selected' : ''); ?>>Price: Low to High</option>
                            <option value="price_desc" <?php echo e(request('sort') == 'price_desc' ? 'selected' : ''); ?>>Price: High to Low</option>
                            <option value="name_asc" <?php echo e(request('sort') == 'name_asc' ? 'selected' : ''); ?>>Name: A-Z</option>
                        </select>
                    </div>
                </div>

                
                <?php
                    // Foto kacamata studio sungguhan untuk produk yang belum diupload gambarnya
                    // lewat Admin > Products, supaya tampilannya tetap semirip referensi (bukan
                    // kotak placeholder teks polos). Ganti array ini kapan saja / hapus begitu
                    // semua produk sudah punya foto asli.
                    $soFallbackPhotos = [
                        'https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80',
                        'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80',
                        'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&q=80',
                        'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=600&q=80',
                        'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&q=80',
                        'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600&q=80',
                    ];
                ?>
                <div class="so-product-catalog-grid">
                    <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <?php
                            $soFallbackImage = $soFallbackPhotos[$product->id % count($soFallbackPhotos)];

                            $galleryImages = collect($product->images ?? [])
                                ->map(fn ($img) => asset('storage/'.$img))
                                ->values();

                            if ($galleryImages->isEmpty()) {
                                $galleryImages = collect([$soFallbackImage]);
                            }
                        ?>
                        <div class="so-catalog-card"
                             data-id="<?php echo e($product->id); ?>"
                             data-name="<?php echo e($product->name); ?>"
                             data-category="<?php echo e($product->category ?? 'Sardio Optik'); ?>"
                             data-price="Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>"
                             data-description="<?php echo e(\Illuminate\Support\Str::limit($product->description ?? '', 220)); ?>"
                             data-images="<?php echo e($galleryImages->toJson()); ?>"
                             data-stock="<?php echo e($product->stock); ?>"
                             data-url="<?php echo e(route('products.show', $product)); ?>"
                             data-add-url="<?php echo e(route('cart.add', $product)); ?>"
                     data-needs-prescription="<?php echo e($product->needsPrescription() ? '1' : '0'); ?>">

                            <div class="so-catalog-card__image">
                                <a href="<?php echo e(route('products.show', $product)); ?>" class="so-catalog-card__image-link">
                                    <?php if($product->main_image): ?>
                                        <img src="<?php echo e(asset('storage/'.$product->main_image)); ?>" alt="<?php echo e($product->name); ?>">
                                    <?php else: ?>
                                        <img src="<?php echo e($soFallbackImage); ?>" alt="<?php echo e($product->name); ?>">
                                    <?php endif; ?>
                                </a>
                                <button type="button" class="so-quickview-btn" aria-label="Quick View" title="Quick View">
                                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                                </button>
                            </div>

                            <div class="so-catalog-card__body">
                                <div class="so-catalog-card__brand"><?php echo e(strtoupper($product->category ?? 'Sardio Optik')); ?></div>
                                <a href="<?php echo e(route('products.show', $product)); ?>" class="so-catalog-card__name"><?php echo e($product->name); ?></a>
                                <div class="so-catalog-card__price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></div>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <p class="text-muted">Belum ada produk.</p>
                    <?php endif; ?>
                </div>

                
                <?php if($products->hasPages()): ?>
                    <div class="so-pagination">
                        <?php echo e($products->onEachSide(1)->links('vendor.pagination.sardio')); ?>

                    </div>
                <?php endif; ?>

            </div>
        </div>
        </form>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
    <script src="<?php echo e(asset('js/products.js')); ?>"></script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sardio-optik-bismillah\resources\views/products/index.blade.php ENDPATH**/ ?>