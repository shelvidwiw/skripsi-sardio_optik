<?php

namespace Tests\Unit\Iterasi1;

use App\Http\Controllers\HomeController;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji class HomeController secara terisolasi.
 * Method controller dipanggil LANGSUNG (new HomeController -> index()),
 * TIDAK lewat route/HTTP request seperti pada feature test ($this->get('/')).
 */
class HomeControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_mengembalikan_view_home(): void
    {
        $controller = new HomeController();

        $view = $controller->index();

        $this->assertSame('home', $view->getName());
    }

    #[Test]
    public function index_mengirim_variabel_featured_products_ke_view(): void
    {
        Product::factory()->count(2)->create();

        $controller = new HomeController();

        $view = $controller->index();

        $this->assertArrayHasKey('featuredProducts', $view->getData());
    }

    #[Test]
    public function index_membatasi_featured_products_maksimal_4(): void
    {
        Product::factory()->count(6)->create();

        $controller = new HomeController();

        $view = $controller->index();
        $featuredProducts = $view->getData()['featuredProducts'];

        $this->assertCount(4, $featuredProducts);
    }

    #[Test]
    public function index_mengurutkan_featured_products_dari_yang_terbaru(): void
    {
        $lama = Product::factory()->create(['created_at' => now()->subDays(5)]);
        $baru = Product::factory()->create(['created_at' => now()]);

        $controller = new HomeController();

        $view = $controller->index();
        $featuredProducts = $view->getData()['featuredProducts'];

        $this->assertSame($baru->id, $featuredProducts->first()->id);
        $this->assertSame($lama->id, $featuredProducts->last()->id);
    }

    #[Test]
    public function index_tetap_mengembalikan_view_walau_belum_ada_produk(): void
    {
        $controller = new HomeController();

        $view = $controller->index();
        $featuredProducts = $view->getData()['featuredProducts'];

        $this->assertSame('home', $view->getName());
        $this->assertCount(0, $featuredProducts);
    }
}
