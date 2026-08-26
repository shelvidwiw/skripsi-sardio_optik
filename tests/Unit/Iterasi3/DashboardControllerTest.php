<?php

namespace Tests\Unit\Iterasi3;

use App\Http\Controllers\Admin\DashboardController;
use App\Models\EyeExamBooking;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Contracts\View\View;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji class Admin\DashboardController secara terisolasi.
 * Method controller (index) dipanggil LANGSUNG (new DashboardController -> index()).
 *
 * Gabungan dari:
 * - US-005 (Dashboard Admin — ringkasan dasar): total order & breakdown status.
 * - US-009 (Dashboard Ringkasan & Tren Penjualan): order/booking terbaru,
 *   produk stok menipis, dan tren jumlah order 7 hari terakhir.
 *
 * NOTE: versi sebelumnya file ini pakai Mockery (mock Order::where()/count()
 * satu-satu). Sekarang DashboardController query ke 3 model sekaligus
 * (Order, EyeExamBooking, Product) + loop tanggal buat tren 7 hari — kalau
 * tetap dimock manual, testnya jadi sangat rapuh & susah dibaca. Makanya
 * ditulis ulang pakai RefreshDatabase + data asli, konsisten dengan gaya
 * test Iterasi1 & Iterasi2.
 */
class DashboardControllerTest extends TestCase
{
    use RefreshDatabase;

    #[Test]
    public function index_mengembalikan_view_admin_dashboard(): void
    {
        $controller = new DashboardController();

        $response = $controller->index();

        $this->assertInstanceOf(View::class, $response);
        $this->assertSame('admin.dashboard', $response->name());
    }

    #[Test]
    public function index_semua_statistik_bernilai_nol_saat_belum_ada_data(): void
    {
        $controller = new DashboardController();
        $data = $controller->index()->getData();

        $this->assertSame(0, $data['totalOrders']);
        $this->assertSame(0, $data['pending']);
        $this->assertSame(0, $data['diproses']);
        $this->assertSame(0, $data['selesai']);
        $this->assertCount(0, $data['recentOrders']);
        $this->assertCount(0, $data['recentBookings']);
        $this->assertCount(0, $data['lowStockProducts']);
    }

    #[Test]
    public function index_menghitung_total_order_dan_breakdown_status_dengan_benar(): void
    {
        $this->createOrder(['status' => 'pending']);
        $this->createOrder(['status' => 'pending']);
        $this->createOrder(['status' => 'diproses']);
        $this->createOrder(['status' => 'selesai']);
        $this->createOrder(['status' => 'dibatalkan']);

        $controller = new DashboardController();
        $data = $controller->index()->getData();

        // Total mencakup SEMUA status termasuk 'dibatalkan': 2+1+1+1 = 5.
        $this->assertSame(5, $data['totalOrders']);
        $this->assertSame(2, $data['pending']);
        $this->assertSame(1, $data['diproses']);
        $this->assertSame(1, $data['selesai']);
    }

    #[Test]
    public function index_mengirim_maksimal_5_order_terbaru_diurutkan_dari_yang_terbaru(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $order = $this->createOrder(['order_code' => "ORD-{$i}"]);
            $order->forceFill(['created_at' => now()->subDays(7 - $i)])->save();
        }

        $controller = new DashboardController();
        $recentOrders = $controller->index()->getData()['recentOrders'];

        $this->assertCount(5, $recentOrders);
        $this->assertSame('ORD-7', $recentOrders->first()->order_code);
    }

    #[Test]
    public function index_mengirim_maksimal_5_booking_terbaru_diurutkan_dari_yang_terbaru(): void
    {
        for ($i = 1; $i <= 7; $i++) {
            $booking = $this->createBooking(['booking_code' => "EXM-{$i}"]);
            $booking->forceFill(['created_at' => now()->subDays(7 - $i)])->save();
        }

        $controller = new DashboardController();
        $recentBookings = $controller->index()->getData()['recentBookings'];

        $this->assertCount(5, $recentBookings);
        $this->assertSame('EXM-7', $recentBookings->first()->booking_code);
    }

    #[Test]
    public function index_daftar_stok_menipis_hanya_berisi_produk_dengan_stok_5_ke_bawah(): void
    {
        Product::factory()->create(['name' => 'Stok Aman', 'stock' => 20]);
        Product::factory()->create(['name' => 'Stok Menipis A', 'stock' => 3]);
        Product::factory()->create(['name' => 'Stok Habis', 'stock' => 0]);

        $controller = new DashboardController();
        $lowStock = $controller->index()->getData()['lowStockProducts'];

        $this->assertCount(2, $lowStock);
        $this->assertTrue($lowStock->every(fn ($p) => $p->stock <= 5));
        $this->assertFalse($lowStock->pluck('name')->contains('Stok Aman'));
    }

    #[Test]
    public function index_daftar_stok_menipis_diurutkan_dari_yang_paling_sedikit(): void
    {
        Product::factory()->create(['name' => 'Sisa 5', 'stock' => 5]);
        Product::factory()->create(['name' => 'Sisa 0', 'stock' => 0]);
        Product::factory()->create(['name' => 'Sisa 2', 'stock' => 2]);

        $controller = new DashboardController();
        $lowStock = $controller->index()->getData()['lowStockProducts'];

        $this->assertSame(['Sisa 0', 'Sisa 2', 'Sisa 5'], $lowStock->pluck('name')->all());
    }

    #[Test]
    public function index_daftar_stok_menipis_maksimal_5_produk(): void
    {
        for ($i = 1; $i <= 8; $i++) {
            Product::factory()->create(['stock' => $i]);
        }

        $controller = new DashboardController();
        $lowStock = $controller->index()->getData()['lowStockProducts'];

        $this->assertCount(5, $lowStock);
    }

    #[Test]
    public function index_trend_berisi_data_7_hari_terakhir(): void
    {
        $controller = new DashboardController();
        $trend = $controller->index()->getData()['trend'];

        $this->assertCount(7, $trend);
        $this->assertSame(now()->translatedFormat('d M'), $trend->last()['label']);
        $this->assertSame(now()->subDays(6)->translatedFormat('d M'), $trend->first()['label']);
    }

    #[Test]
    public function index_trend_menghitung_jumlah_order_per_hari_dengan_benar(): void
    {
        // 2 order hari ini, 1 order kemarin, 0 order di hari lainnya.
        $orderHariIni1 = $this->createOrder(['order_code' => 'ORD-HARI-INI-1']);
        $orderHariIni2 = $this->createOrder(['order_code' => 'ORD-HARI-INI-2']);
        $orderKemarin = $this->createOrder(['order_code' => 'ORD-KEMARIN']);
        $orderKemarin->forceFill(['created_at' => now()->subDay()])->save();

        $controller = new DashboardController();
        $trend = $controller->index()->getData()['trend'];

        $this->assertSame(2, $trend->last()['count']);
        $this->assertSame(1, $trend->get(5)['count']);
    }

    #[Test]
    public function index_trend_bernilai_0_untuk_hari_tanpa_order(): void
    {
        $controller = new DashboardController();
        $trend = $controller->index()->getData()['trend'];

        $this->assertTrue($trend->every(fn ($day) => $day['count'] === 0));
    }

    /**
     * Helper: buat Order dummy langsung ke database (Order tidak punya factory).
     */
    private function createOrder(array $overrides = []): Order
    {
        return Order::create(array_merge([
            'order_code' => 'ORD-'.uniqid(),
            'customer_name' => 'Budi Santoso',
            'whatsapp_number' => '081234567890',
            'total_amount' => 100000,
            'tax_amount' => 11000,
            'status' => 'pending',
        ], $overrides));
    }

    /**
     * Helper: buat EyeExamBooking dummy langsung ke database
     * (EyeExamBooking tidak punya factory).
     */
    private function createBooking(array $overrides = []): EyeExamBooking
    {
        return EyeExamBooking::create(array_merge([
            'booking_code' => 'EXM-'.uniqid(),
            'name' => 'Budi Santoso',
            'whatsapp_number' => '081234567890',
            'preferred_date' => now()->addDay()->toDateString(),
            'preferred_time' => '10:00',
            'status' => 'pending',
        ], $overrides));
    }
}
