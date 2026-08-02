<?php $__env->startSection('title', 'Checkout - Sardio Optik'); ?>

<?php $__env->startPush('styles'); ?>
    <link href="<?php echo e(asset('css/checkout.css')); ?>" rel="stylesheet">
<?php $__env->stopPush(); ?>

<?php $__env->startSection('content'); ?>
<div class="so-checkout-page">
    <div class="so-container">

        <div class="so-checkout-topbar">
            <a href="<?php echo e(route('products.index')); ?>" class="so-checkout-back">&larr; Back to Products</a>
            <span class="so-checkout-divider">|</span>
            <span class="so-checkout-secure-badge">Secure Checkout</span>
            <span class="so-checkout-ssl">256-bit SSL Encryption</span>
        </div>

        <div class="so-checkout-layout">
            <div class="so-checkout-form-card">
                <div class="so-checkout-step">
                    <span class="so-checkout-step__number">1</span>
                    <h4>Shipping Information</h4>
                </div>

                <form action="<?php echo e(route('checkout.store')); ?>" method="POST" id="soCheckoutForm">
                    <?php echo csrf_field(); ?>

                    <label class="so-field-label">Full Name</label>
                    <input type="text" name="customer_name" value="<?php echo e(old('customer_name')); ?>" class="so-field-input" placeholder="Johnathan Doe" required>

                    <label class="so-field-label">Street Address</label>
                    <input type="text" name="street_address" value="<?php echo e(old('street_address')); ?>" class="so-field-input" placeholder="123 Clarity Avenue">

                    <div class="so-field-row">
                        <div>
                            <label class="so-field-label">City</label>
                            <input type="text" name="city" value="<?php echo e(old('city')); ?>" class="so-field-input" placeholder="Jakarta">
                        </div>
                        <div>
                            <label class="so-field-label">Postal Code</label>
                            <input type="text" name="postal_code" value="<?php echo e(old('postal_code')); ?>" class="so-field-input" placeholder="10110">
                        </div>
                    </div>

                    <label class="so-field-label">Phone Number (WhatsApp)</label>
                    <input type="text" name="whatsapp_number" value="<?php echo e(old('whatsapp_number')); ?>" class="so-field-input" placeholder="+62 812 3456 7890" required>
                </form>
            </div>

            <div class="so-checkout-summary">
                <div class="so-checkout-summary__head">
                    <h5>Order Summary</h5>
                    <span><?php echo e(collect($cart)->sum('quantity')); ?> items</span>
                </div>

                <div class="so-checkout-summary__items">
                    <?php $__currentLoopData = $cart; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="so-checkout-summary__item">
                            <div class="so-checkout-summary__item-image">
                                <?php if(!empty($item['image'])): ?>
                                    <img src="<?php echo e(asset('storage/'.$item['image'])); ?>" alt="<?php echo e($item['name']); ?>">
                                <?php else: ?>
                                    <img src="https://images.unsplash.com/photo-1572635196237-14b3f281503f?w=200&q=80" alt="<?php echo e($item['name']); ?>">
                                <?php endif; ?>
                            </div>
                            <div class="so-checkout-summary__item-info">
                                <div class="so-checkout-summary__item-top">
                                    <span class="so-checkout-summary__item-name"><?php echo e($item['name']); ?></span>
                                    <span class="so-checkout-summary__item-price">Rp <?php echo e(number_format($item['price'], 0, ',', '.')); ?></span>
                                </div>
                                <span class="so-checkout-summary__item-qty">Qty: <?php echo e($item['quantity']); ?></span>
                                <?php if(!empty($item['prescription'])): ?>
                                    <?php $rx = $item['prescription']; ?>
                                    <span class="so-checkout-summary__item-rx">
                                        <?php if($rx['method'] === 'upload'): ?>
                                            Resep: file terupload
                                        <?php else: ?>
                                            Resep: OD <?php echo e($rx['sph_right'] ?? '-'); ?> / OS <?php echo e($rx['sph_left'] ?? '-'); ?>

                                        <?php endif; ?>
                                    </span>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>

                <div class="so-checkout-promo">
                    <input type="text" placeholder="PROMO CODE" disabled>
                    <button type="button" class="is-disabled" onclick="return false;" title="Segera hadir">Apply</button>
                </div>

                <div class="so-checkout-summary__row">
                    <span>Subtotal</span>
                    <span>Rp <?php echo e(number_format($subtotal, 0, ',', '.')); ?></span>
                </div>
                <div class="so-checkout-summary__row">
                    <span>PPN (11%)</span>
                    <span>Rp <?php echo e(number_format($tax, 0, ',', '.')); ?></span>
                </div>
                <div class="so-checkout-summary__row">
                    <span>Shipping</span>
                    <span class="so-checkout-summary__free">Free</span>
                </div>

                <div class="so-checkout-summary__total">
                    <span>Grand Total</span>
                    <div>
                        <strong>Rp <?php echo e(number_format($grandTotal, 0, ',', '.')); ?></strong>
                        <small>SECURE TRANSACTION</small>
                    </div>
                </div>

                <button type="submit" form="soCheckoutForm" class="so-btn so-btn--primary so-btn--block so-checkout-wa-btn">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    Order via WhatsApp
                </button>

                <div class="so-checkout-call-bar">
                    Questions? Call +62 21 555 1234
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH C:\laragon\www\sardio-optik-bismillah\resources\views/checkout/index.blade.php ENDPATH**/ ?>