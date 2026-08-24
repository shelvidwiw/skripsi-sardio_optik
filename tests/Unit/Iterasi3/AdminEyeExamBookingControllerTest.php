<?php

namespace Tests\Unit\Iterasi3;

use App\Http\Controllers\Admin\EyeExamBookingController;
use App\Models\EyeExamBooking;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

/**
 * UNIT TEST — menguji class Admin\EyeExamBookingController secara terisolasi.
 * Method controller dipanggil LANGSUNG dengan membuat object Request/
 * EyeExamBooking sendiri, TIDAK lewat route/HTTP seperti
 * $this->get('/admin/eye-exam-bookings') pada feature test.
 *
 * Bagian dari US-008 (Kelola Booking Pemeriksaan Mata oleh Admin): admin
 * bisa melihat, mencari, dan mengubah status booking pemeriksaan mata.
 */
class AdminEyeExamBookingControllerTest extends TestCase
{
    use RefreshDatabase;

    // ================= index() =================

    #[Test]
    public function index_mengembalikan_view_admin_eye_exam_bookings_index(): void
    {
        $controller = new EyeExamBookingController();

        $view = $controller->index(Request::create('/admin/eye-exam-bookings', 'GET'));

        $this->assertSame('admin.eye-exam-bookings.index', $view->getName());
    }

    #[Test]
    public function index_menampilkan_semua_booking_tanpa_filter(): void
    {
        $this->createBooking(['booking_code' => 'EXM-001', 'name' => 'Budi']);
        $this->createBooking(['booking_code' => 'EXM-002', 'name' => 'Siti']);

        $controller = new EyeExamBookingController();
        $view = $controller->index(Request::create('/admin/eye-exam-bookings', 'GET'));

        $this->assertCount(2, $view->getData()['bookings']);
    }

    #[Test]
    public function index_mencari_booking_berdasarkan_nama(): void
    {
        $this->createBooking(['booking_code' => 'EXM-001', 'name' => 'Budi Santoso']);
        $this->createBooking(['booking_code' => 'EXM-002', 'name' => 'Siti Aminah']);

        $controller = new EyeExamBookingController();
        $view = $controller->index(Request::create('/admin/eye-exam-bookings', 'GET', ['search' => 'Budi']));

        $bookings = $view->getData()['bookings'];

        $this->assertCount(1, $bookings);
        $this->assertSame('Budi Santoso', $bookings->first()->name);
    }

    #[Test]
    public function index_mencari_booking_berdasarkan_kode_booking(): void
    {
        $this->createBooking(['booking_code' => 'EXM-UNIK-01', 'name' => 'Budi']);
        $this->createBooking(['booking_code' => 'EXM-LAIN-02', 'name' => 'Siti']);

        $controller = new EyeExamBookingController();
        $view = $controller->index(Request::create('/admin/eye-exam-bookings', 'GET', ['search' => 'UNIK']));

        $bookings = $view->getData()['bookings'];

        $this->assertCount(1, $bookings);
        $this->assertSame('EXM-UNIK-01', $bookings->first()->booking_code);
    }

    #[Test]
    public function index_membatasi_maksimal_15_booking_per_halaman(): void
    {
        for ($i = 1; $i <= 20; $i++) {
            $this->createBooking(['booking_code' => "EXM-{$i}"]);
        }

        $controller = new EyeExamBookingController();
        $view = $controller->index(Request::create('/admin/eye-exam-bookings', 'GET'));

        $bookings = $view->getData()['bookings'];

        $this->assertCount(15, $bookings->items());
        $this->assertSame(20, $bookings->total());
    }

    // ================= show() =================

    #[Test]
    public function show_mengembalikan_view_admin_eye_exam_bookings_show_dengan_data_booking(): void
    {
        $booking = $this->createBooking(['booking_code' => 'EXM-SHOW-01']);

        $controller = new EyeExamBookingController();
        $view = $controller->show($booking);

        $this->assertSame('admin.eye-exam-bookings.show', $view->getName());
        $this->assertTrue($booking->is($view->getData()['booking']));
    }

    // ================= updateStatus() =================

    #[Test]
    public function update_status_gagal_validasi_jika_status_tidak_dikenal(): void
    {
        $booking = $this->createBooking();

        $controller = new EyeExamBookingController();
        $request = Request::create("/admin/eye-exam-bookings/{$booking->id}/status", 'PATCH', ['status' => 'status-ngasal']);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->updateStatus($request, $booking);
    }

    #[Test]
    public function update_status_gagal_validasi_jika_status_kosong(): void
    {
        $booking = $this->createBooking();

        $controller = new EyeExamBookingController();
        $request = Request::create("/admin/eye-exam-bookings/{$booking->id}/status", 'PATCH', []);

        $this->expectException(\Illuminate\Validation\ValidationException::class);

        $controller->updateStatus($request, $booking);
    }

    #[Test]
    public function update_status_berhasil_mengubah_status_booking(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);

        $controller = new EyeExamBookingController();
        $request = Request::create("/admin/eye-exam-bookings/{$booking->id}/status", 'PATCH', ['status' => 'dikonfirmasi']);

        $controller->updateStatus($request, $booking);

        $this->assertSame('dikonfirmasi', $booking->fresh()->status);
    }

    #[Test]
    public function update_status_menerima_semua_status_yang_valid(): void
    {
        $booking = $this->createBooking(['status' => 'pending']);
        $controller = new EyeExamBookingController();

        foreach (['pending', 'dikonfirmasi', 'selesai', 'dibatalkan'] as $status) {
            $controller->updateStatus(
                Request::create("/admin/eye-exam-bookings/{$booking->id}/status", 'PATCH', ['status' => $status]),
                $booking
            );

            $this->assertSame($status, $booking->fresh()->status);
        }
    }

    #[Test]
    public function update_status_redirect_kembali_dengan_pesan_sukses(): void
    {
        $booking = $this->createBooking();

        $controller = new EyeExamBookingController();
        $request = Request::create("/admin/eye-exam-bookings/{$booking->id}/status", 'PATCH', ['status' => 'selesai']);

        $response = $controller->updateStatus($request, $booking);

        $this->assertSame('Status booking diperbarui.', $response->getSession()->get('success'));
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
