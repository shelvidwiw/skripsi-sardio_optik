<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    const TAX_RATE = 0.11; // PPN 11%

    public function index(Request $request)
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Item yang di-checkout adalah yang dicentang di halaman Cart. Kalau tidak ada info
        // pilihan (mis. akses langsung), fallback ke semua item supaya tetap kompatibel.
        $selectedKeys = $request->input('selected_items');
        $cart = $selectedKeys ? collect($cart)->only($selectedKeys)->all() : $cart;

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Pilih dulu produk yang ingin di-checkout.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $tax = round($subtotal * self::TAX_RATE);
        $grandTotal = $subtotal + $tax; // Shipping: gratis (Iterasi 1 belum menghitung ongkir riil)

        return view('checkout.index', compact('cart', 'subtotal', 'tax', 'grandTotal'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'whatsapp_number' => 'required|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:10',
        ]);

        $sessionCart = session('cart', []);

        if (empty($sessionCart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hanya proses item yang tadi dicentang & dibawa dari halaman Cart (hidden inputs).
        // Item lain yang TIDAK dicentang harus tetap ada di cart untuk di-checkout nanti.
        $selectedKeys = $request->input('selected_items', array_keys($sessionCart));
        $cart = collect($sessionCart)->only($selectedKeys)->all();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Pilih dulu produk yang ingin di-checkout.');
        }

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
        $tax = round($subtotal * self::TAX_RATE);
        $grandTotal = $subtotal + $tax;

        $order = DB::transaction(function () use ($request, $cart, $subtotal, $tax, $grandTotal) {
            $order = Order::create([
                'order_code' => Order::generateOrderCode(),
                'customer_name' => $request->customer_name,
                'whatsapp_number' => $request->whatsapp_number,
                'street_address' => $request->street_address,
                'city' => $request->city,
                'postal_code' => $request->postal_code,
                'total_amount' => $grandTotal,
                'tax_amount' => $tax,
                'status' => 'pending',
            ]);

            foreach ($cart as $item) {
                $prescription = $item['prescription'] ?? null;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['name'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['price'] * $item['quantity'],
                    'sph_right' => $prescription['sph_right'] ?? null,
                    'sph_left' => $prescription['sph_left'] ?? null,
                    'cyl_right' => $prescription['cyl_right'] ?? null,
                    'cyl_left' => $prescription['cyl_left'] ?? null,
                    'axis_right' => $prescription['axis_right'] ?? null,
                    'axis_left' => $prescription['axis_left'] ?? null,
                    'prescription_file' => $prescription['file'] ?? null,
                    'prescription_note' => $prescription['note'] ?? null,
                ]);
            }

            return $order;
        });

        // empty the cart
        // Cuma buang item yang barusan di-checkout dari session cart; item yang tidak
        // dicentang tetap tersimpan supaya bisa di-checkout lain waktu.
        $remainingCart = collect($sessionCart)->except($selectedKeys)->all();
        session(['cart' => $remainingCart]);

        // build WhatsApp redirect to admin number, prefilled with order summary
        $adminNumber = env('ADMIN_WHATSAPP_NUMBER', '6281234567890');

        $message = "Halo Sardio Optik, saya ingin konfirmasi pesanan.\n";
        $message .= "Kode Pesanan: {$order->order_code}\n";
        $message .= "Nama: {$order->customer_name}\n";
        if ($order->street_address) {
            $message .= "Alamat: {$order->street_address}, {$order->city} {$order->postal_code}\n";
        }
        $message .= "Subtotal: Rp " . number_format((float) $subtotal, 0, ',', '.') . "\n";
        $message .= "PPN (11%): Rp " . number_format((float) $tax, 0, ',', '.') . "\n";
        $message .= "Total: Rp " . number_format((float) $order->total_amount, 0, ',', '.') . "\n";
        $message .= "Detail:\n";
        foreach ($order->items as $item) {
            $message .= "- {$item->product_name} x{$item->quantity}";
            $message .= $item->hasPrescription() ? " (ada resep, cek detail order)\n" : "\n";
        }

        $waUrl = 'https://wa.me/' . $adminNumber . '?text=' . rawurlencode($message);

        return redirect()->away($waUrl);
    }
}