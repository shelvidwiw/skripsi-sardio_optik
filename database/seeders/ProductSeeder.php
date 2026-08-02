<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['name' => 'AeroTitanium Precision 502', 'category' => Product::CATEGORY_FRAME, 'brand' => 'Sardio Signature', 'price' => 2450000, 'stock' => 12,
                'description' => 'Frame titanium ringan dengan desain presisi untuk kenyamanan sehari-hari.'],
            ['name' => 'Lumiwash Aviator X', 'category' => Product::CATEGORY_FRAME, 'brand' => 'Ray-Ban', 'price' => 1850000, 'stock' => 8,
                'description' => 'Frame aviator klasik dengan lensa anti glare.'],
            ['name' => 'Prismatic Round Half', 'category' => Product::CATEGORY_FRAME, 'brand' => 'Oakley', 'price' => 1320000, 'stock' => 15,
                'description' => 'Frame round half dengan aksen warna modern.'],
            ['name' => 'Lensa Minus Anti Radiasi', 'category' => Product::CATEGORY_LENSA, 'brand' => 'Sardio Signature', 'price' => 350000, 'stock' => 30,
                'description' => 'Lensa kacamata dengan lapisan anti radiasi, sesuai resep (minus/silinder) atau upload resep dokter.'],
            ['name' => 'Lensa Blue Light Filter', 'category' => Product::CATEGORY_LENSA, 'brand' => 'Sardio Signature', 'price' => 425000, 'stock' => 25,
                'description' => 'Lensa dengan filter blue light untuk pengguna gadget aktif, sesuai resep.'],
            ['name' => 'Soflens Bening Harian', 'category' => Product::CATEGORY_SOFLENS, 'brand' => 'Acuvue', 'price' => 185000, 'stock' => 40,
                'description' => 'Soflens harian bening, sesuai ukuran minus mata kanan & kiri.'],
            ['name' => 'Soflens Warna Bulanan', 'category' => Product::CATEGORY_SOFLENS, 'brand' => 'X2 Softlens', 'price' => 220000, 'stock' => 35,
                'description' => 'Soflens warna pemakaian bulanan, sesuai ukuran minus mata kanan & kiri.'],
            ['name' => 'Cairan Pembersih Soflens 360ml', 'category' => Product::CATEGORY_CAIRAN_SOFLENS, 'brand' => 'Renu', 'price' => 95000, 'stock' => 50,
                'description' => 'Cairan multifungsi untuk membersihkan dan merendam soflens.'],
        ];

        foreach ($products as $product) {
            Product::updateOrCreate(
                ['slug' => Str::slug($product['name'])],
                [
                    'name' => $product['name'],
                    'category' => $product['category'],
                    'brand' => $product['brand'],
                    'description' => $product['description'],
                    'price' => $product['price'],
                    'stock' => $product['stock'],
                    'images' => [],
                ]
            );
        }
    }
}
