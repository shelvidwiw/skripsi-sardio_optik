/**
 * Quick View modal — SHARED di seluruh halaman (Home > Top Selling, Product Catalog, dst).
 * Modal HTML-nya cuma ada SATU instance di layout (resources/views/partials/quick-view-modal.blade.php),
 * jadi script ini otomatis nyambung ke tombol Quick View di halaman manapun tanpa perlu setup ulang.
 *
 * Elemen pemicu (trigger) cukup punya class "so-quickview-btn" dan berada di dalam sebuah elemen
 * yang punya data-name, data-price, data-images, dst (lihat data-* di so-catalog-card / so-product-card).
 */
document.addEventListener('DOMContentLoaded', function () {
    var backdrop = document.getElementById('soQuickViewBackdrop');
    if (!backdrop) return;

    var closeBtn = document.getElementById('soQuickViewClose');
    var imgEl = document.getElementById('soQuickViewImage');
    var thumbsEl = document.getElementById('soGalleryThumbs');
    var prevBtn = document.getElementById('soGalleryPrev');
    var nextBtn = document.getElementById('soGalleryNext');

    var nameEl = document.getElementById('soQuickViewName');
    var priceEl = document.getElementById('soQuickViewPrice');
    var descEl = document.getElementById('soQuickViewDescription');
    var linkEl = document.getElementById('soQuickViewLink');
    var buyNowEl = document.getElementById('soBuyNowBtn');

    var qtyInput = document.getElementById('soQtyInput');
    var qtyMinus = document.getElementById('soQtyMinus');
    var qtyPlus = document.getElementById('soQtyPlus');

    var addToCartBtn = document.getElementById('soAddToCartBtn');
    var addForm = document.getElementById('soQuickAddForm');
    var addQuantityField = document.getElementById('soQuickAddQuantity');
    var addBuyNowField = document.getElementById('soQuickAddBuyNow');

    var colorSwatches = document.querySelectorAll('.so-color-swatch');
    var colorNameEl = document.getElementById('soQuickViewColorName');

    var rxNoteEl = document.getElementById('soQuickViewRxNote');
    var cartRowEl = document.getElementById('soQuickViewCartRow');

    var currentImages = [];
    var currentIndex = 0;
    var currentStock = 1;
    var currentNeedsPrescription = false;

    function renderMainImage() {
        if (!currentImages.length) return;
        imgEl.src = currentImages[currentIndex];

        thumbsEl.querySelectorAll('img').forEach(function (thumb, i) {
            thumb.classList.toggle('is-active', i === currentIndex);
        });

        var showNav = currentImages.length > 1;
        prevBtn.style.display = showNav ? 'flex' : 'none';
        nextBtn.style.display = showNav ? 'flex' : 'none';
    }

    function buildThumbs() {
        thumbsEl.innerHTML = '';
        if (currentImages.length <= 1) return;

        currentImages.forEach(function (src, i) {
            var thumb = document.createElement('img');
            thumb.src = src;
            thumb.alt = 'thumbnail';
            if (i === currentIndex) thumb.classList.add('is-active');
            thumb.addEventListener('click', function () {
                currentIndex = i;
                renderMainImage();
            });
            thumbsEl.appendChild(thumb);
        });
    }

    function openQuickView(dataHolder) {
        var images = [];
        try {
            images = JSON.parse(dataHolder.dataset.images || '[]');
        } catch (e) {
            images = [];
        }
        if (!images.length) images = ['https://placehold.co/700x700/F5F7FC/0D4C92?text=No+Image'];

        currentImages = images;
        currentIndex = 0;
        currentStock = parseInt(dataHolder.dataset.stock || '1', 10) || 1;

        renderMainImage();
        buildThumbs();

        nameEl.textContent = dataHolder.dataset.name;
        priceEl.textContent = dataHolder.dataset.price;
        descEl.textContent = dataHolder.dataset.description || '';
        linkEl.href = dataHolder.dataset.url;
        addForm.action = dataHolder.dataset.addUrl;

        // Produk kategori Lensa/Soflens butuh input resep yang gak muat di quick view —
        // jadi quick add-to-cart disembunyikan, user diarahkan ke halaman detail produk
        // (Buy Now pun ikut diarahkan ke halaman detail, bukan langsung checkout).
        var needsPrescription = dataHolder.dataset.needsPrescription === '1';
        currentNeedsPrescription = needsPrescription;
        if (rxNoteEl && cartRowEl) {
            rxNoteEl.style.display = needsPrescription ? '' : 'none';
            cartRowEl.style.display = needsPrescription ? 'none' : '';
        }
        buyNowEl.href = needsPrescription ? dataHolder.dataset.url : '#';

        qtyInput.value = 1;
        addQuantityField.value = 1;

        colorSwatches.forEach(function (sw, i) {
            sw.classList.toggle('is-active', i === 0);
        });
        if (colorNameEl && colorSwatches.length) {
            colorNameEl.textContent = colorSwatches[0].dataset.color.toUpperCase();
        }

        backdrop.classList.add('is-open');
        document.body.style.overflow = 'hidden';
    }

    function closeQuickView() {
        backdrop.classList.remove('is-open');
        document.body.style.overflow = '';
    }

    // Buka modal dari tombol Quick View manapun di halaman ini — baik di Product Catalog
    // (.so-catalog-card) maupun di Home > Top Selling Frames (.so-product-card).
    document.querySelectorAll('.so-quickview-btn').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            var dataHolder = btn.closest('[data-name]');
            if (dataHolder) openQuickView(dataHolder);
        });
    });

    closeBtn.addEventListener('click', closeQuickView);

    backdrop.addEventListener('click', function (e) {
        if (e.target === backdrop) closeQuickView();
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') closeQuickView();
    });

    // Gallery navigation
    if (prevBtn && nextBtn) {
        prevBtn.addEventListener('click', function () {
            if (!currentImages.length) return;
            currentIndex = (currentIndex - 1 + currentImages.length) % currentImages.length;
            renderMainImage();
        });
        nextBtn.addEventListener('click', function () {
            if (!currentImages.length) return;
            currentIndex = (currentIndex + 1) % currentImages.length;
            renderMainImage();
        });
    }

    // Quantity selector
    qtyMinus.addEventListener('click', function () {
        var value = parseInt(qtyInput.value, 10) || 1;
        if (value > 1) qtyInput.value = value - 1;
    });
    qtyPlus.addEventListener('click', function () {
        var value = parseInt(qtyInput.value, 10) || 1;
        if (value < currentStock) qtyInput.value = value + 1;
    });

    // Frame color swatches — visual only (Iterasi 1: belum ada kolom variant warna di database)
    colorSwatches.forEach(function (sw) {
        sw.addEventListener('click', function () {
            colorSwatches.forEach(function (s) { s.classList.remove('is-active'); });
            sw.classList.add('is-active');
            if (colorNameEl) colorNameEl.textContent = sw.dataset.color.toUpperCase();
        });
    });

    // Add to Cart — submit POST asli ke backend, produk beneran masuk session cart
    // (Iterasi 1 requirement), lalu redirect ke halaman Cart.
    addToCartBtn.addEventListener('click', function () {
        addQuantityField.value = qtyInput.value;
        addBuyNowField.value = '0';
        addForm.submit();
    });

    // Buy Now — untuk produk yang TIDAK butuh resep: submit form yang sama dengan flag
    // buy_now=1, backend akan redirect langsung ke halaman Checkout (bukan ke Cart).
    // Untuk produk yang butuh resep (Lensa/Soflens), href sudah diarahkan ke halaman detail
    // produk (lihat openQuickView), jadi biarkan link itu jalan normal (jangan preventDefault).
    buyNowEl.addEventListener('click', function (e) {
        if (currentNeedsPrescription) return; // biarkan navigasi <a> normal ke halaman detail

        e.preventDefault();
        addQuantityField.value = qtyInput.value;
        addBuyNowField.value = '1';
        addForm.submit();
    });
});
