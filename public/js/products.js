/**
 * Logic khusus halaman Product Catalog: auto-submit filter form (real-time),
 * live label Price Range, dan search-bar client-side buat filter daftar checkbox.
 * (Quick View modal-nya sekarang di public/js/quick-view.js — shared, dipakai di semua halaman)
 */
document.addEventListener('DOMContentLoaded', function () {
    var filterForm = document.getElementById('soFilterForm');
    if (!filterForm) return;

    // Checkboxes (category/brand) & sort dropdown -> submit immediately on change
    filterForm.querySelectorAll('.so-auto-submit').forEach(function (el) {
        el.addEventListener('change', function () {
            filterForm.submit();
        });
    });

    // Price range slider -> live-update the label while dragging,
    // submit once the user releases the handle (real-time feel without
    // firing a page reload on every pixel of movement).
    var priceRange = document.getElementById('soPriceRange');
    var priceValueLabel = document.getElementById('soPriceRangeValue');
    if (priceRange && priceValueLabel) {
        priceRange.addEventListener('input', function () {
            var formatted = 'Rp ' + Number(priceRange.value).toLocaleString('id-ID');
            priceValueLabel.textContent = formatted;
        });
        priceRange.addEventListener('change', function () {
            filterForm.submit();
        });
    }

    // Search-bar di atas tiap grup filter (Category / Brand) -> live filter
    // daftar checkbox di client, TIDAK submit form (murni bantu cari opsi).
    document.querySelectorAll('.so-filter-search__input').forEach(function (input) {
        input.addEventListener('input', function () {
            var targetList = document.getElementById(input.dataset.filterList);
            if (!targetList) return;
            var keyword = input.value.trim().toLowerCase();

            targetList.querySelectorAll('.so-checkbox').forEach(function (row) {
                var text = row.textContent.trim().toLowerCase();
                row.style.display = text.includes(keyword) ? 'flex' : 'none';
            });
        });
    });
});
