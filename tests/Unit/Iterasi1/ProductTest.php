<?php

namespace Tests\Unit\Iterasi1;

use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji logic murni di dalam class Product (model),
 * bukan HTTP request/route. Tidak ada pemanggilan route/controller di sini.
 */
class ProductTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function prescription_type_mengembalikan_full_untuk_kategori_lensa(): void
    {
        $product = Product::factory()->make(['category' => Product::CATEGORY_LENSA]);

        $this->assertSame('full', $product->prescriptionType());
    }

    #[Test]
    public function prescription_type_mengembalikan_sphere_only_untuk_kategori_soflens(): void
    {
        $product = Product::factory()->make(['category' => Product::CATEGORY_SOFLENS]);

        $this->assertSame('sphere_only', $product->prescriptionType());
    }

    #[Test]
    public function prescription_type_null_untuk_kategori_frame_dan_cairan_soflens(): void
    {
        $frame = Product::factory()->make(['category' => Product::CATEGORY_FRAME]);
        $cairan = Product::factory()->make(['category' => Product::CATEGORY_CAIRAN_SOFLENS]);

        $this->assertNull($frame->prescriptionType());
        $this->assertNull($cairan->prescriptionType());
    }

    #[Test]
    public function prescription_type_null_untuk_kategori_yang_tidak_dikenal(): void
    {
        $product = Product::factory()->make(['category' => 'Kategori Aneh']);

        $this->assertNull($product->prescriptionType());
    }

    #[Test]
    public function needs_prescription_true_untuk_lensa_dan_soflens(): void
    {
        $lensa = Product::factory()->make(['category' => Product::CATEGORY_LENSA]);
        $soflens = Product::factory()->make(['category' => Product::CATEGORY_SOFLENS]);

        $this->assertTrue($lensa->needsPrescription());
        $this->assertTrue($soflens->needsPrescription());
    }

    #[Test]
    public function needs_prescription_false_untuk_frame_dan_cairan_soflens(): void
    {
        $frame = Product::factory()->make(['category' => Product::CATEGORY_FRAME]);
        $cairan = Product::factory()->make(['category' => Product::CATEGORY_CAIRAN_SOFLENS]);

        $this->assertFalse($frame->needsPrescription());
        $this->assertFalse($cairan->needsPrescription());
    }

    #[Test]
    public function route_key_name_menggunakan_slug_bukan_id(): void
    {
        $product = new Product();

        $this->assertSame('slug', $product->getRouteKeyName());
    }

    #[Test]
    public function main_image_mengembalikan_gambar_pertama_jika_ada(): void
    {
        $product = Product::factory()->make([
            'images' => ['products/foto-1.jpg', 'products/foto-2.jpg'],
        ]);

        $this->assertSame('products/foto-1.jpg', $product->main_image);
    }

    #[Test]
    public function main_image_null_jika_tidak_ada_gambar(): void
    {
        $productKosong = Product::factory()->make(['images' => []]);
        $productNull = Product::factory()->make(['images' => null]);

        $this->assertNull($productKosong->main_image);
        $this->assertNull($productNull->main_image);
    }

    #[Test]
    public function images_di_cast_sebagai_array(): void
    {
        $product = Product::factory()->create([
            'images' => ['a.jpg', 'b.jpg'],
        ]);

        $fresh = Product::find($product->id);

        $this->assertIsArray($fresh->images);
        $this->assertSame(['a.jpg', 'b.jpg'], $fresh->images);
    }
}
