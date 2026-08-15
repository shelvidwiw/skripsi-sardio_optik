<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CartService
{
    public function all(): array
    {
        return session('cart', []);
    }

    public function subtotal(array $cart): float
    {
        return collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    /**
     * Gabungkan produk ke cart di session.
     * Produk tanpa resep digabung by product_id (quantity ditambah).
     * Produk dengan resep selalu jadi baris baru (tiap resep berbeda = order
     * terpisah), supaya resep mata kanan/kiri orang A tidak ketimpa/tercampur
     * sama punya orang B.
     */
    public function add(Product $product, int $quantity, ?array $prescription, ?string $prescriptionType): array
    {
        $cart = $this->all();

        if (! $prescriptionType && isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
            $this->save($cart);

            return $cart;
        }

        $key = $prescriptionType ? $product->id.'-'.Str::random(8) : (string) $product->id;

        $cart[$key] = [
            'key' => $key,
            'product_id' => $product->id,
            'name' => $product->name,
            'price' => $product->price,
            'image' => $product->main_image,
            'quantity' => $quantity,
            'prescription' => $prescription,
        ];

        $this->save($cart);

        return $cart;
    }

    public function updateQuantity(string $key, int $quantity): array
    {
        $cart = $this->all();

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = $quantity;
            $this->save($cart);
        }

        return $cart;
    }

    public function remove(string $key): array
    {
        $cart = $this->all();

        if (isset($cart[$key]['prescription']['file'])) {
            Storage::disk('public')->delete($cart[$key]['prescription']['file']);
        }

        unset($cart[$key]);

        $this->save($cart);

        return $cart;
    }

    public function save(array $cart): void
    {
        session(['cart' => $cart]);
    }
}