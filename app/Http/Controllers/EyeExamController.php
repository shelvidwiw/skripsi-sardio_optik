<?php

namespace App\Http\Controllers;

use App\Models\EyeExamBooking;
use Illuminate\Http\Request;

class EyeExamController extends Controller
{
    public function index()
    {
        return view('eye-exam.index');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'preferred_date' => 'required|date|after_or_equal:today',
            'preferred_time' => 'required|string|max:50',
            'note' => 'nullable|string|max:500',
        ]);

        $booking = EyeExamBooking::create([
            'booking_code' => EyeExamBooking::generateBookingCode(),
            'name' => $validated['name'],
            'whatsapp_number' => $validated['whatsapp_number'],
            'preferred_date' => $validated['preferred_date'],
            'preferred_time' => $validated['preferred_time'],
            'note' => $validated['note'] ?? null,
            'status' => 'pending',
        ]);

        // Sama seperti alur checkout produk: konfirmasi jadwal lewat WhatsApp ke admin.
        $adminNumber = env('ADMIN_WHATSAPP_NUMBER', '6281234567890');

        $message = "Halo Sardio Optik, saya ingin booking pemeriksaan mata.\n";
        $message .= "Kode Booking: {$booking->booking_code}\n";
        $message .= "Nama: {$booking->name}\n";
        $message .= 'Tanggal: ' . $booking->preferred_date->format('d-m-Y') . "\n";
        $message .= "Jam: {$booking->preferred_time}\n";
        if ($booking->note) {
            $message .= "Catatan: {$booking->note}\n";
        }

        $waUrl = 'https://wa.me/' . $adminNumber . '?text=' . rawurlencode($message);

        return redirect()->away($waUrl);
    }
}
