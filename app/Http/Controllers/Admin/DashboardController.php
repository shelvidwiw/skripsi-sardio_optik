<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EyeExamBooking;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pending = Order::where('status', 'pending')->count();
        $diproses = Order::where('status', 'diproses')->count();
        $selesai = Order::where('status', 'selesai')->count();

        $recentOrders = Order::latest()->take(5)->get();
        $recentBookings = EyeExamBooking::latest()->take(5)->get();

        $lowStockProducts = Product::where('stock', '<=', 5)->orderBy('stock')->take(5)->get();

        // Tren jumlah order 7 hari terakhir, buat grafik dashboard.
        $trend = collect(range(6, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo);

            return [
                'label' => $date->translatedFormat('d M'),
                'count' => Order::whereDate('created_at', $date->toDateString())->count(),
            ];
        });

        return view('admin.dashboard', compact(
            'totalOrders', 'pending', 'diproses', 'selesai',
            'recentOrders', 'recentBookings', 'lowStockProducts', 'trend'
        ));
    }
}
