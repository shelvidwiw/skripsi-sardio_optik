<?php $__env->startSection('title', 'Sardio Optik - Precision Vision, Defined by Sardio'); ?>

<?php $__env->startPush('styles'); ?>
    
    <link href="<?php echo e(asset('css/products.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>


<section class="so-hero">
    <div class="so-hero__bg" style="background-image:url('https://images.unsplash.com/photo-1508296695146-257a814070b4?w=1600&q=80');"></div>
    <div class="so-hero__overlay"></div>
    <div class="so-container so-hero__content">
        <div class="so-hero__card">
            <h1>Precision Vision, Defined by Sardio.</h1>
            <p>Experience clinical accuracy with our new Seasonal Collection. Get up to 30% off on premium prescription lenses this month.</p>
            <div class="so-hero__actions">
                <a href="<?php echo e(route('products.index')); ?>" class="so-btn so-btn--primary">Shop Collection</a>
                <a href="#" class="so-btn so-btn--secondary is-disabled" onclick="return false;" title="Segera hadir">Book Eye Test</a>
            </div>
        </div>
    </div>
</section>


<section class="so-section">
    <div class="so-container">
        <h2 class="so-section__title">Our Specialized Services</h2>
        <p class="so-section__subtitle">Comprehensive optical solutions tailored to your lifestyle.</p>

        <div class="so-services">
            <a href="<?php echo e(route('products.index', ['category' => [\App\Models\Product::CATEGORY_FRAME]])); ?>" class="so-service-card">
                <div class="so-service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                </div>
                <div class="so-service-card__title">Frame</div>
            </a>
            <a href="<?php echo e(route('products.index', ['category' => [\App\Models\Product::CATEGORY_SOFLENS]])); ?>" class="so-service-card">
                <div class="so-service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="3" width="16" height="18" rx="2"/><circle cx="12" cy="10" r="2.5"/><path d="M8 17h8"/></svg>
                </div>
                <div class="so-service-card__title">Soflens</div>
            </a>
            <a href="<?php echo e(route('products.index', ['category' => [\App\Models\Product::CATEGORY_LENSA]])); ?>" class="so-service-card">
                <div class="so-service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="4"/><path d="M12 2v3M12 19v3M4.2 4.2l2.1 2.1M17.7 17.7l2.1 2.1M2 12h3M19 12h3M4.2 19.8l2.1-2.1M17.7 6.3l2.1-2.1"/></svg>
                </div>
                <div class="so-service-card__title">Lensa</div>
            </a>
            <div class="so-service-card">
                <div class="so-service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="7" width="18" height="13" rx="2"/><path d="M8 7V5a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2M3 12h18"/></svg>
                </div>
                <div class="so-service-card__title">Pemeriksaan Mata</div>
            </div>
            <a href="<?php echo e(route('products.index', ['category' => [\App\Models\Product::CATEGORY_CAIRAN_SOFLENS]])); ?>" class="so-service-card">
                <div class="so-service-card__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="9"/><path d="M8 14s1.5 2 4 2 4-2 4-2M9 9h.01M15 9h.01"/></svg>
                </div>
                <div class="so-service-card__title">Cairan Soflens</div>
            </a>
        </div>
    </div>
</section>


<section class="so-section so-section--muted">
    <div class="so-container">
        <h2 class="so-section__title so-section__title--center">Top Selling Frames</h2>

        <div class="so-products-grid">
            <?php
                $soFallbackPhotosHome = [
                    'https://images.unsplash.com/photo-1577803645773-f96470509666?w=600&q=80',
                    'https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=600&q=80',
                    'https://images.unsplash.com/photo-1508296695146-257a814070b4?w=600&q=80',
                    'https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=600&q=80',
                    'https://images.unsplash.com/photo-1517841905240-472988babdf9?w=600&q=80',
                    'https://images.unsplash.com/photo-1600185365483-26d7a4cc7519?w=600&q=80',
                ];
            ?>
            <?php $__empty_1 = true; $__currentLoopData = $featuredProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <?php
                    $soFallbackImageHome = $soFallbackPhotosHome[$product->id % count($soFallbackPhotosHome)];

                    $galleryImagesHome = collect($product->images ?? [])
                        ->map(fn ($img) => asset('storage/'.$img))
                        ->values();

                    if ($galleryImagesHome->isEmpty()) {
                        $galleryImagesHome = collect([$soFallbackImageHome]);
                    }
                ?>
                <div class="so-product-card"
                     data-name="<?php echo e($product->name); ?>"
                     data-category="<?php echo e($product->category ?? 'Sardio Optik'); ?>"
                     data-price="Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?>"
                     data-description="<?php echo e(\Illuminate\Support\Str::limit($product->description ?? '', 220)); ?>"
                     data-images="<?php echo e($galleryImagesHome->toJson()); ?>"
                     data-stock="<?php echo e($product->stock); ?>"
                     data-url="<?php echo e(route('products.show', $product)); ?>"
                     data-add-url="<?php echo e(route('cart.add', $product)); ?>"
                     data-needs-prescription="<?php echo e($product->needsPrescription() ? '1' : '0'); ?>">

                    <div class="so-product-card__image">
                        <?php if($product->main_image): ?>
                            <img src="<?php echo e(asset('storage/'.$product->main_image)); ?>" alt="<?php echo e($product->name); ?>">
                        <?php else: ?>
                            <img src="<?php echo e($soFallbackImageHome); ?>" alt="<?php echo e($product->name); ?>">
                        <?php endif; ?>

                        
                        <div class="so-product-card__overlay">
                            <button type="button" class="so-quickview-btn so-quickview-btn--center" aria-label="Quick View" title="Quick View">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-7 11-7 11 7 11 7-4 7-11 7-11-7-11-7Z"/><circle cx="12" cy="12" r="3"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="so-product-card__body">
                        <div class="so-product-card__top">
                            <h6><?php echo e($product->name); ?></h6>
                            <span class="so-product-card__price">Rp <?php echo e(number_format($product->price, 0, ',', '.')); ?></span>
                        </div>
                        <p class="so-product-card__category"><?php echo e($product->category ?? 'Kacamata'); ?></p>
                        <a href="<?php echo e(route('products.show', $product)); ?>" class="so-btn so-btn--outline so-btn--block">View Details</a>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p class="text-muted">Belum ada produk.</p>
            <?php endif; ?>
        </div>
    </div>
</section>


<section class="so-section">
    <div class="so-container so-style">
        <div class="so-style__text">
            <h2>Find Your Perfect Style</h2>
            <p>Eyewear is more than vision—it's an expression of your identity. Our AI-driven style guide helps you match frames with your face shape, lifestyle, and aesthetic preferences.</p>
            <div class="so-style__pills">
                <span class="so-pill">Square Face</span>
                <span class="so-pill">Oval Face</span>
                <span class="so-pill">Heart Shape</span>
            </div>
            <a href="#" class="so-btn so-btn--primary is-disabled" onclick="return false;" title="Segera hadir">Start Style Quiz &rarr;</a>
        </div>
        <div class="so-style__gallery">
            <img src="https://images.unsplash.com/photo-1517841905240-472988babdf9?w=500&q=80" alt="Style">
            <img src="https://images.unsplash.com/photo-1591076482161-42ce6da69f67?w=500&q=80" alt="Frame">
            <img src="https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=500&q=80" alt="Style">
            <img src="https://images.unsplash.com/photo-1556306535-0f09a537f0a3?w=500&q=80" alt="App">
        </div>
    </div>
</section>


<section class="so-trust">
    <div class="so-container">
        <div class="so-trust__grid">
            <div class="so-trust__item">
                <div class="so-trust__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 4 5v6c0 5 3.5 8.7 8 11 4.5-2.3 8-6 8-11V5l-8-3Z"/><path d="m9 12 2 2 4-4"/></svg>
                </div>
                <h5>Clinical Precision</h5>
                <p>Every lens is measured to the millimeter using state-of-the-art optical sensors for 100% accuracy.</p>
            </div>
            <div class="so-trust__item">
                <div class="so-trust__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 4 5v6c0 5 3.5 8.7 8 11 4.5-2.3 8-6 8-11V5l-8-3Z"/><path d="M12 8v5M12 16h.01"/></svg>
                </div>
                <h5>Certified Opticians</h5>
                <p>Our team consists of board-certified specialists with over 15 years of clinical experience in eye health.</p>
            </div>
            <div class="so-trust__item">
                <div class="so-trust__icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 2 4 5v6c0 5 3.5 8.7 8 11 4.5-2.3 8-6 8-11V5l-8-3Z"/></svg>
                </div>
                <h5>Secure Consultation</h5>
                <p>Your vision records are encrypted and stored following the highest healthcare data standards.</p>
            </div>
        </div>

        <div class="so-cta">
            <div>
                <h4>Ready for a digital eye consultation?</h4>
                <p>Book a session with our specialists from the comfort of your home.</p>
            </div>
            <a href="#" class="so-btn so-btn--light is-disabled" onclick="return false;" title="Segera hadir">Book Free Consultation</a>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>
<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sardio-optik-bismillah\resources\views/home.blade.php ENDPATH**/ ?>