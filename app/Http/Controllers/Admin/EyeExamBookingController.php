<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EyeExamBooking;
use Illuminate\Http\Request;

class EyeExamBookingController extends Controller
{
    public function index(Request $request)
    {
        $query = EyeExamBooking::query()->latest();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('booking_code', 'like', "%{$search}%");
            });
        }

        $bookings = $query->paginate(15)->withQueryString();

        return view('admin.eye-exam-bookings.index', compact('bookings'));
    }

    public function updateStatus(Request $request, EyeExamBooking $eyeExamBooking)
    {
        $request->validate([
            'status' => 'required|in:pending,dikonfirmasi,selesai,dibatalkan',
        ]);

        $eyeExamBooking->update(['status' => $request->status]);

        return back()->with('success', 'Status booking diperbarui.');
    }
}
