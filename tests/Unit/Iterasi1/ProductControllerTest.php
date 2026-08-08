<?php

namespace Tests\Unit\Iterasi1;

use App\Http\Controllers\ProductController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji class ProductController secara terisolasi.
 * Method controller (index & show) dipanggil LANGSUNG dengan membuat
 * object Request/Product sendiri, TIDAK lewat route/HTTP seperti
 * $this->get('/products') pada feature test.
 */
class ProductControllerTest extends TestCase
{
    use RefreshDatabase;

    // ================= index() — daftar produk =================

    #[Test]
    public function index_mengembalikan_view_products_index(): void
    {
        $controller = new ProductController();

        $view = $controller->index(Request::create('/products', 'GET'));

        $this->assertSame('products.index', $view->getName());
    }

    #[Test]
    public function index_menampilkan_semua_produk_saat_tidak_ada_filter(): void
    {
        Product::factory()->count(3)->create();

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET'));

        $this->assertCount(3, $view->getData()['products']);
    }

    #[Test]
    public function index_mencari_produk_berdasarkan_keyword_nama(): void
    {
        Product::factory()->create(['name' => 'Kacamata Aviator Klasik']);
        Product::factory()->create(['name' => 'Softlens Bening']);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['q' => 'Aviator']));

        $products = $view->getData()['products'];

        $this->assertCount(1, $products);
        $this->assertSame('Kacamata Aviator Klasik', $products->first()->name);
    }

    #[Test]
    public function index_mencari_produk_berdasarkan_keyword_kategori_atau_brand(): void
    {
        Product::factory()->create(['name' => 'Produk A', 'category' => Product::CATEGORY_FRAME, 'brand' => 'Rayban']);
        Product::factory()->create(['name' => 'Produk B', 'category' => Product::CATEGORY_SOFLENS, 'brand' => 'X2']);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['q' => 'Rayban']));

        $products = $view->getData()['products'];

        $this->assertCount(1, $products);
        $this->assertSame('Produk A', $products->first()->name);
    }

    #[Test]
    public function index_memfilter_produk_berdasarkan_kategori(): void
    {
        Product::factory()->create(['category' => Product::CATEGORY_FRAME]);
        Product::factory()->create(['category' => Product::CATEGORY_SOFLENS]);
        Product::factory()->create(['category' => Product::CATEGORY_LENSA]);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', [
            'category' => [Product::CATEGORY_FRAME, Product::CATEGORY_LENSA],
        ]));

        $products = $view->getData()['products'];

        $this->assertCount(2, $products);
        $this->assertTrue($products->pluck('category')->every(
            fn ($category) => in_array($category, [Product::CATEGORY_FRAME, Product::CATEGORY_LENSA])
        ));
    }

    #[Test]
    public function index_memfilter_produk_berdasarkan_brand(): void
    {
        Product::factory()->create(['brand' => 'Rayban']);
        Product::factory()->create(['brand' => 'Oakley']);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['brand' => ['Rayban']]));

        $products = $view->getData()['products'];

        $this->assertCount(1, $products);
        $this->assertSame('Rayban', $products->first()->brand);
    }

    #[Test]
    public function index_memfilter_produk_berdasarkan_harga_maksimal(): void
    {
        Product::factory()->create(['price' => 100000]);
        Product::factory()->create(['price' => 500000]);
        Product::factory()->create(['price' => 900000]);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['max_price' => 500000]));

        $products = $view->getData()['products'];

        $this->assertCount(2, $products);
        $this->assertTrue($products->every(fn ($p) => (float) $p->price <= 500000));
    }

    #[Test]
    public function index_mengurutkan_produk_harga_termurah_lebih_dulu(): void
    {
        Product::factory()->create(['name' => 'Mahal', 'price' => 900000]);
        Product::factory()->create(['name' => 'Murah', 'price' => 100000]);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['sort' => 'price_asc']));

        $products = $view->getData()['products'];

        $this->assertSame('Murah', $products->first()->name);
    }

    #[Test]
    public function index_mengurutkan_produk_harga_termahal_lebih_dulu(): void
    {
        Product::factory()->create(['name' => 'Mahal', 'price' => 900000]);
        Product::factory()->create(['name' => 'Murah', 'price' => 100000]);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['sort' => 'price_desc']));

        $products = $view->getData()['products'];

        $this->assertSame('Mahal', $products->first()->name);
    }

    #[Test]
    public function index_mengurutkan_produk_berdasarkan_nama_a_ke_z(): void
    {
        Product::factory()->create(['name' => 'Zebra']);
        Product::factory()->create(['name' => 'Aviator']);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET', ['sort' => 'name_asc']));

        $products = $view->getData()['products'];

        $this->assertSame('Aviator', $products->first()->name);
    }

    #[Test]
    public function index_default_mengurutkan_dari_produk_terbaru(): void
    {
        $lama = Product::factory()->create(['created_at' => now()->subDays(3)]);
        $baru = Product::factory()->create(['created_at' => now()]);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET'));

        $products = $view->getData()['products'];

        $this->assertSame($baru->id, $products->first()->id);
    }

    #[Test]
    public function index_mengirim_daftar_kategori_dan_brand_yang_unik_ke_view(): void
    {
        Product::factory()->create(['category' => Product::CATEGORY_FRAME, 'brand' => 'Rayban']);
        Product::factory()->create(['category' => Product::CATEGORY_FRAME, 'brand' => 'Rayban']);
        Product::factory()->create(['category' => Product::CATEGORY_SOFLENS, 'brand' => 'X2']);

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET'));
        $data = $view->getData();

        $this->assertCount(2, $data['categories']);
        $this->assertCount(2, $data['brands']);
    }

    #[Test]
    public function index_membatasi_maksimal_12_produk_per_halaman(): void
    {
        Product::factory()->count(15)->create();

        $controller = new ProductController();
        $view = $controller->index(Request::create('/products', 'GET'));

        $products = $view->getData()['products'];

        $this->assertCount(12, $products->items());
        $this->assertSame(15, $products->total());
    }

    // ================= show() — detail produk =================

    #[Test]
    public function show_mengembalikan_view_products_show(): void
    {
        $product = Product::factory()->create();
        $controller = new ProductController();

        $view = $controller->show($product);

        $this->assertSame('products.show', $view->getName());
    }

    #[Test]
    public function show_mengirim_produk_yang_sesuai_ke_view(): void
    {
        $product = Product::factory()->create(['name' => 'Kacamata Wayfarer']);
        $controller = new ProductController();

        $view = $controller->show($product);

        $this->assertSame('Kacamata Wayfarer', $view->getData()['product']->name);
        $this->assertTrue($product->is($view->getData()['product']));
    }
}
