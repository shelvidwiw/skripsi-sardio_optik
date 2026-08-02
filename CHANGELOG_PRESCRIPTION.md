# Update: Kategori Produk + Sistem Resep + Pemeriksaan Mata

## Yang berubah

### 1. Kategori produk resmi (dropdown, bukan teks bebas lagi)
`Frame`, `Lensa`, `Soflens`, `Cairan Soflens` — didefinisikan di `App\Models\Product::CATEGORIES`.

### 2. Logic resep otomatis per kategori
- **Frame** & **Cairan Soflens** → tidak ada form resep sama sekali.
- **Lensa** → wajib isi resep: minus (sph) + cylinder + axis kanan/kiri, ATAU upload foto resep.
- **Soflens** → wajib isi resep: minus (sph) kanan/kiri saja, ATAU upload foto resep.

Ini otomatis nyala di halaman detail produk (`/products/{slug}`) begitu kategori produk di-set ke Lensa/Soflens dari admin.

### 3. Quick View disesuaikan
Karena form resep terlalu kompleks untuk dimasukin ke modal kecil, Quick View untuk produk Lensa/Soflens
otomatis SEMBUNYIIN tombol "Add to Cart" & qty selector, diganti catatan yang arahin ke halaman detail
produk buat isi resep di sana. Frame & Cairan Soflens tetap bisa quick-add seperti biasa.

### 4. Cart & Checkout
- Tiap resep berbeda = baris cart terpisah (jadi 1 customer bisa beli 2 Lensa dengan resep beda tanpa ketimpa).
- Ringkasan resep muncul di halaman Cart & Checkout.
- Data resep ikut kebawa sampai ke `order_items` pas checkout, dan muncul di detail order admin.
- Pesan konfirmasi WhatsApp otomatis nandain item mana yang ada resepnya.

### 5. Halaman baru: Pemeriksaan Mata (`/pemeriksaan-mata`)
Bukan produk, jadi bukan bagian dari cart — halaman tersendiri buat booking jadwal periksa mata
(nama, WhatsApp, tanggal, jam, catatan). Setelah submit, diarahkan ke WhatsApp admin buat konfirmasi
(pola yang sama kayak checkout produk). Link "Consultation" di navbar sekarang aktif dan ngarah ke sini.

### 6. Admin
- Form tambah/edit produk: kategori sekarang dropdown (bukan input teks bebas), biar konsisten sama logic resep.
- Menu baru "Eye Exam Bookings" di sidebar admin — lihat & update status booking pemeriksaan mata.
- Detail order sekarang nampilin data resep per item (manual atau link ke file yang diupload).

## Migration baru yang perlu dijalankan
```
php artisan migrate
```
Ini akan nambahin kolom resep di `order_items` dan bikin tabel baru `eye_exam_bookings`.

## Testing yang sudah dilakukan
Sudah dites end-to-end (bukan cuma baca kode) pakai PHP built-in server + SQLite:
- Tambah Frame ke cart tanpa resep → berhasil
- Tambah Lensa tanpa resep → DITOLAK, redirect balik ke halaman produk dengan error
- Tambah Lensa dengan resep manual (sph/cyl/axis) → berhasil
- Tambah Soflens dengan resep manual (sph saja) → berhasil
- Tambah Lensa dengan upload file resep asli → berhasil, tersimpan di storage
- Checkout: data resep dari cart session ikut kebawa utuh ke `order_items`
- Booking Pemeriksaan Mata → tersimpan ke DB, redirect ke WhatsApp
- Login admin → lihat detail order dengan data resep, lihat daftar booking pemeriksaan mata
- Dropdown kategori admin menampilkan 4 pilihan resmi

## Kalau mau nambah kategori produk baru
Edit `App\Models\Product::CATEGORIES` dan `prescriptionType()` di `app/Models/Product.php`.
