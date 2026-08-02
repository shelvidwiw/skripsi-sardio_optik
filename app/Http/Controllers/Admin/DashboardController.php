<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index()
    {
        $totalOrders = Order::count();
        $pending = Order::where('status', 'pending')->count();
        $diproses = Order::where('status', 'diproses')->count();
        $selesai = Order::where('status', 'selesai')->count();

        return view('admin.dashboard', compact('totalOrders', 'pending', 'diproses', 'selesai'));
    }
}
