<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutService
{
    const TAX_RATE = 0.11; // PPN 11%

    public function __construct(private readonly CartService $cartService)
    {
    }

    /**
     * Pilih item cart yang mau di-checkout. Kalau tidak ada key terpilih,
     * pakai semua isi cart (kompatibel dengan akses langsung ke halaman checkout).
     */
    public function resolveSelectedCart(array $sessionCart, ?array $selectedKeys): array
    {
        return $selectedKeys ? collect($sessionCart)->only($selectedKeys)->all() : $sessionCart;
    }

    /**
     * Hitung subtotal, PPN (11%), dan grand total dari isi cart.
     */
    public function calculateTotals(array $cart): array
    {
        $subtotal = $this->cartService->subtotal($cart);
        $tax = round($subtotal * self::TAX_RATE);
        $grandTotal = $subtotal + $tax; // Shipping: gratis (Iterasi 1 belum menghitung ongkir riil)

        return [$subtotal, $tax, $grandTotal];
    }

    /**
     * Buat Order beserta seluruh OrderItem-nya dalam satu transaksi DB.
     */
    public function createOrderWithItems(Request $request, array $cart, float $tax, float $grandTotal): Order
    {
        return DB::transaction(function () use ($request, $cart, $tax, $grandTotal) {
            $order = Order::create([
                'user_id' => auth()->id(),
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
                OrderItem::create($this->buildOrderItemAttributes($order, $item));
            }

            return $order;
        });
    }

    /**
     * Buang item yang barusan di-checkout dari session cart, sisakan yang belum dipilih.
     */
    public function removeCheckedOutItems(array $sessionCart, array $selectedKeys): void
    {
        $remainingCart = collect($sessionCart)->except($selectedKeys)->all();

        $this->cartService->save($remainingCart);
    }

    /**
     * Susun atribut OrderItem dari satu baris item cart (termasuk data resep, jika ada).
     */
    private function buildOrderItemAttributes(Order $order, array $item): array
    {
        $prescription = $item['prescription'] ?? [];

        return [
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
        ];
    }
}