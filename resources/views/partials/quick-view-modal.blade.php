{{-- ===================== QUICK VIEW MODAL (shared, dipakai di Home & Product Catalog) ===================== --}}
<div class="so-modal-backdrop" id="soQuickViewBackdrop">
    <div class="so-modal" role="dialog" aria-modal="true" aria-label="Quick View">
        <button type="button" class="so-modal__close" id="soQuickViewClose" aria-label="Close">&times;</button>

        <div class="so-modal__body">
            {{-- LEFT: GALLERY --}}
            <div class="so-modal__gallery">
                <div class="so-modal__main-image">
                    <button type="button" class="so-gallery-nav so-gallery-nav--prev" id="soGalleryPrev" aria-label="Previous image">&lsaquo;</button>
                    <img id="soQuickViewImage" src="" alt="">
                    <button type="button" class="so-gallery-nav so-gallery-nav--next" id="soGalleryNext" aria-label="Next image">&rsaquo;</button>
                </div>
                <div class="so-modal__thumbs" id="soGalleryThumbs"></div>
            </div>

            {{-- RIGHT: INFO --}}
            <div class="so-modal__info">
                <span class="so-modal__badge">NEW ARRIVAL</span>
                <h4 id="soQuickViewName"></h4>

                <div class="so-modal__rating">
                    <span class="so-modal__stars">&#9733;&#9733;&#9733;&#9733;&#9734;</span>
                    <span class="so-modal__rating-text">(4.5/5 &bull; 128 reviews)</span>
                </div>

                <div class="so-modal__price" id="soQuickViewPrice"></div>

                <p id="soQuickViewDescription" class="so-modal__desc"></p>

                <div class="so-modal__colors" id="soQuickViewColorsBlock">
                    <div class="so-modal__colors-label">FRAME COLOR: <span id="soQuickViewColorName">MATTE ONYX</span></div>
                    <div class="so-color-swatches">
                        <button type="button" class="so-color-swatch is-active" data-color="Matte Onyx" style="background:#12151c;"></button>
                        <button type="button" class="so-color-swatch" data-color="Deep Navy" style="background:#1c2a4a;"></button>
                        <button type="button" class="so-color-swatch" data-color="Steel Silver" style="background:#aab3c5;"></button>
                    </div>
                </div>

                <hr class="so-modal__divider">

                {{-- Lensa & Soflens butuh input resep (minus/cylinder atau upload), jadi Quick Add
                     tidak dipakai untuk kategori ini — user diarahkan isi resep di halaman produk. --}}
                <p id="soQuickViewRxNote" class="text-muted small mb-2" style="display:none;">
                    Produk ini butuh data resep (minus/cylinder atau upload resep dokter). Silakan isi di halaman detail produk.
                </p>

                <div class="so-modal__actions-row" id="soQuickViewCartRow">
                    <div class="so-qty-selector">
                        <button type="button" id="soQtyMinus" aria-label="Kurangi jumlah">&minus;</button>
                        <input type="number" id="soQtyInput" value="1" min="1" readonly>
                        <button type="button" id="soQtyPlus" aria-label="Tambah jumlah">&plus;</button>
                    </div>
                    <button type="button" id="soAddToCartBtn" class="so-btn so-btn--primary so-btn--icon">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="9" cy="21" r="1"/><circle cx="20" cy="21" r="1"/><path d="M1 1h4l2.7 13.4a2 2 0 0 0 2 1.6h9.7a2 2 0 0 0 2-1.6L23 6H6"/></svg>
                        Add to Cart
                    </button>
                </div>

                <div class="so-modal__actions-row so-modal__actions-row--secondary">
                    <a href="#" id="soBuyNowBtn" class="so-btn so-btn--dark">Buy Now</a>
                    <a href="#" id="soQuickViewLink" class="so-btn so-btn--outline">View Details &rarr;</a>
                </div>

                {{-- Real add-to-cart submission (Iterasi 1: benar-benar tersimpan ke session cart) --}}
                <form id="soQuickAddForm" method="POST" action="">
                    @csrf
                    <input type="hidden" name="quantity" id="soQuickAddQuantity" value="1">
                    <input type="hidden" name="buy_now" id="soQuickAddBuyNow" value="0">
                </form>
            </div>
        </div>
    </div>
</div>