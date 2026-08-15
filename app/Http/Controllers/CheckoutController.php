<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCheckoutRequest;
use App\Services\CartService;
use App\Services\CheckoutService;
use App\Services\WhatsAppMessageBuilder;
use Illuminate\Http\Request;

class CheckoutController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly CheckoutService $checkoutService,
        private readonly WhatsAppMessageBuilder $whatsAppMessageBuilder,
    ) {
    }

    public function index(Request $request)
    {
        $sessionCart = $this->cartService->all();

        if (empty($sessionCart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Item yang di-checkout adalah yang dicentang di halaman Cart. Kalau tidak ada info
        // pilihan (mis. akses langsung), fallback ke semua item supaya tetap kompatibel.
        $cart = $this->checkoutService->resolveSelectedCart($sessionCart, $request->input('selected_items'));

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Pilih dulu produk yang ingin di-checkout.');
        }

        [$subtotal, $tax, $grandTotal] = $this->checkoutService->calculateTotals($cart);

        return view('checkout.index', compact('cart', 'subtotal', 'tax', 'grandTotal'));
    }

    public function store(StoreCheckoutRequest $request)
    {
        $sessionCart = $this->cartService->all();

        if (empty($sessionCart)) {
            return redirect()->route('cart.index')->with('error', 'Keranjang Anda kosong.');
        }

        // Hanya proses item yang tadi dicentang & dibawa dari halaman Cart (hidden inputs).
        // Item lain yang TIDAK dicentang harus tetap ada di cart untuk di-checkout nanti.
        $selectedKeys = $request->input('selected_items', array_keys($sessionCart));
        $cart = $this->checkoutService->resolveSelectedCart($sessionCart, $selectedKeys);

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Pilih dulu produk yang ingin di-checkout.');
        }

        [$subtotal, $tax, $grandTotal] = $this->checkoutService->calculateTotals($cart);

        $order = $this->checkoutService->createOrderWithItems($request, $cart, $tax, $grandTotal);

        // Cuma buang item yang barusan di-checkout dari session cart; item yang tidak
        // dicentang tetap tersimpan supaya bisa di-checkout lain waktu.
        $this->checkoutService->removeCheckedOutItems($sessionCart, $selectedKeys);

        return redirect()->away($this->whatsAppMessageBuilder->url($order, $subtotal, $tax));
    }
}