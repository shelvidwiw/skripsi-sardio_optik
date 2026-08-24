<?php

namespace Tests\Unit\Iterasi3;

use App\Http\Controllers\Admin\OrderController;
use App\Models\Order;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji class Admin\OrderController secara terisolasi.
 * Method controller dipanggil LANGSUNG dengan membuat object Request/Order
 * sendiri, TIDAK lewat route/HTTP seperti $this->get('/admin/orders')
 * pada feature test.
 *
 * Bagian dari US-008 (Kelola Pesanan oleh Admin): admin bisa melihat,
 * mencari, dan mengubah status pesanan customer.
 */
class AdminOrderControllerTest extends TestCase
{
    use RefreshDatabase;

    // ================= index() =================

    #[Test]
    public function index_mengembalikan_view_admin_orders_index(): void
    {
        $controller = new OrderController();

        $view = $controller->index(Request::create('/admin/orders', 'GET'));

        $this->assertSame('admin.orders.index', $view->getName());
    }

    #[Test]
    public function index_menampilkan_semua_order_tanpa_filter(): void
    {
        $this->createOrder(['order_code' => 'ORD-001', 'customer_name' => 'Budi']);
        $this->createOrder(['order_code' => 'ORD-002', 'customer_name' => 'Siti']);

        $controller = new OrderController();
        $view = $controller->index(Request::create('/admin/orders', 'GET'));

        $this->assertCount(2, $view->getData()['orders']);
    }

    #[Test]
    public function index_mencari_order_berdasarkan_nama_customer(): void
    {
        $this->createOrder(['order_code' => 'ORD-001', 'customer_name' => 'Budi Santoso']);
        $this->createOrder(['order_code' => 'ORD-002', 'customer_name' => 'Siti Aminah']);

        $controller = new OrderController();
        $view = $controller->index(Request::create('/admin/orders', 'GET', ['search' => 'Budi']));

        $orders = $view->getData()['orders'];

        $this->assertCount(1, $orders);
        $this->assertSame('Budi Santoso', $orders->first()->customer_name);
    }

    #[Test]
    public function index_mencari_order_berdasarkan_kode_order(): void
    {
        $this->createOrder(['order_code' => 'ORD-UNIK-01', 'customer_name' => 'Budi']);
        $this->createOrder(['order_code' => 'ORD-LAIN-02', 'customer_name' => 'Siti']);

        $controller = new OrderController();
        $view = $controller->index(Request::create('/admin/orders', 'GET', ['search' => 'UNIK']));

        $orders = $view->getData()['orders'];

        $this->assertCount(1, $orders);
        $this->assertSame('ORD-UNIK-01', $orders->first()->order_code);
    }

    #[Test]
    public function index_membatasi_maksimal_15_order_per_halaman(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->createOrder(['order_code' => "ORD-{$i}"]);
        }

        $controller = new OrderController();
        $view = $controller->index(Request::create('/admin/orders', 'GET'));

        $orders = $view->getData()['orders'];

        $this->assertCount(15, $orders->items());
        $this->assertSame(20, $orders->total());
    }

    // ================= show() =================

    #[Test]
    public function show_mengembalikan_view_admin_orders_show_dengan_data_order(): void
    {
        $order = $this->createOrder(['order_code' => 'ORD-SHOW-01']);

        $controller = new OrderController();
        $view = $controller->show($order);

        $this->assertSame('admin.orders.show', $view->getName());
        $this->assertTrue($order->is($view->getData()['order']));
    }

    #[Test]
    public function show_menyertakan_items_pada_order(): void
    {
        $order = $this->createOrder(['order_code' => 'ORD-SHOW-02']);
        $order->items()->create([
            'product_id' => null,
            'product_name' => 'Frame A',
            'price' => 100000,
            'quantity' => 1,
            'subtotal' => 100000,
        ]);

        $controller = new OrderController();
        $view = $controller->show($order);

        $this->assertTrue($view->getData()['order']->relationLoaded('items'));
        $this->assertCount(1, $view->getData()['order']->items);
    }

    // ================= updateStatus() =================

    #[Test]
    public function update_status_gagal_validasi_jika_status_tidak_dikenal(): void
    {
        $order = $this->createOrder();

        $controller = new OrderController();
        $request = Request::create("/admin/orders/{$order->id}/status", 'PATCH', ['status' => 'status-ngasal']);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->updateStatus($request, $order);
    }

    #[Test]
    public function update_status_gagal_validasi_jika_status_kosong(): void
    {
        $order = $this->createOrder();

        $controller = new OrderController();
        $request = Request::create("/admin/orders/{$order->id}/status", 'PATCH', []);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->updateStatus($request, $order);
    }

    #[Test]
    public function update_status_berhasil_mengubah_status_order(): void
    {
        $order = $this->createOrder(['status' => 'pending']);

        $controller = new OrderController();
        $request = Request::create("/admin/orders/{$order->id}/status", 'PATCH', ['status' => 'diproses']);

        $controller->updateStatus($request, $order);

        $this->assertSame('diproses', $order->fresh()->status);
    }

    #[Test]
    public function update_status_menerima_semua_status_yang_valid(): void
    {
        $order = $this->createOrder(['status' => 'pending']);
        $controller = new OrderController();

        foreach (['pending', 'diproses', 'selesai', 'dibatalkan'] as $status) {
            $controller->updateStatus(
                Request::create("/admin/orders/{$order->id}/status", 'PATCH', ['status' => $status]),
                $order
            );

            $this->assertSame($status, $order->fresh()->status);
        }
    }

    #[Test]
    public function update_status_redirect_kembali_dengan_pesan_sukses(): void
    {
        $order = $this->createOrder();

        $controller = new OrderController();
        $request = Request::create("/admin/orders/{$order->id}/status", 'PATCH', ['status' => 'selesai']);

        $response = $controller->updateStatus($request, $order);

        $this->assertSame('Status pesanan diperbarui.', $response->getSession()->get('success'));
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
}
