<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddToCartRequest;
use App\Models\Product;
use App\Services\CartService;
use App\Services\PrescriptionBuilder;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function __construct(
        private readonly CartService $cartService,
        private readonly PrescriptionBuilder $prescriptionBuilder,
    ) {
    }

    public function index()
    {
        $cart = $this->cartService->all();
        $subtotal = $this->cartService->subtotal($cart);

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(AddToCartRequest $request, Product $product)
    {
        $validated = $request->validated();
        $prescriptionType = $request->prescriptionType();
        $quantity = (int) ($validated['quantity'] ?? 1);

        $prescription = $prescriptionType
            ? $this->prescriptionBuilder->build($request, $validated, $prescriptionType)
            : null;

        $this->cartService->add($product, $quantity, $prescription, $prescriptionType);

        if ($request->boolean('buy_now')) {
            return redirect()->route('checkout.index');
        }

        return redirect()->route('cart.index')->with('success', 'Produk ditambahkan ke keranjang.');
    }

    public function update(Request $request, string $key)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $this->cartService->updateQuantity($key, (int) $request->input('quantity'));

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $this->cartService->remove($key);

        return redirect()->route('cart.index');
    }
}