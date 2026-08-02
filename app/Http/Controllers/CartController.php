<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        $subtotal = collect($cart)->sum(fn ($item) => $item['price'] * $item['quantity']);

        return view('cart.index', compact('cart', 'subtotal'));
    }

    public function add(Request $request, Product $product)
    {
        $rules = [
            'quantity' => 'nullable|integer|min:1',
        ];

        $prescriptionType = $product->prescriptionType();

        // Resep hanya wajib untuk kategori Lensa (full) & Soflens (sphere_only).
        // Frame & Cairan Soflens tidak butuh field ini sama sekali.
        if ($prescriptionType) {
            $rules['prescription_method'] = 'required|in:manual,upload';
            $rules['prescription_file'] = 'required_if:prescription_method,upload|nullable|file|mimes:jpg,jpeg,png,pdf|max:4096';
            $rules['sph_right'] = 'required_if:prescription_method,manual|nullable|numeric|between:-30,30';
            $rules['sph_left'] = 'required_if:prescription_method,manual|nullable|numeric|between:-30,30';

            if ($prescriptionType === 'full') {
                $rules['cyl_right'] = 'nullable|numeric|between:-10,10';
                $rules['cyl_left'] = 'nullable|numeric|between:-10,10';
                $rules['axis_right'] = 'nullable|integer|between:0,180';
                $rules['axis_left'] = 'nullable|integer|between:0,180';
            }

            $rules['prescription_note'] = 'nullable|string|max:500';
        }

        $validated = $request->validate($rules, [
            'prescription_file.required_if' => 'Silakan upload foto resep Anda.',
            'sph_right.required_if' => 'Ukuran minus mata kanan wajib diisi.',
            'sph_left.required_if' => 'Ukuran minus mata kiri wajib diisi.',
        ]);

        $quantity = (int) ($validated['quantity'] ?? 1);

        $prescription = null;
        if ($prescriptionType) {
            $prescription = [
                'type' => $prescriptionType,
                'method' => $validated['prescription_method'],
                'sph_right' => $validated['prescription_method'] === 'manual' ? ($validated['sph_right'] ?? null) : null,
                'sph_left' => $validated['prescription_method'] === 'manual' ? ($validated['sph_left'] ?? null) : null,
                'cyl_right' => ($prescriptionType === 'full' && $validated['prescription_method'] === 'manual') ? ($validated['cyl_right'] ?? null) : null,
                'cyl_left' => ($prescriptionType === 'full' && $validated['prescription_method'] === 'manual') ? ($validated['cyl_left'] ?? null) : null,
                'axis_right' => ($prescriptionType === 'full' && $validated['prescription_method'] === 'manual') ? ($validated['axis_right'] ?? null) : null,
                'axis_left' => ($prescriptionType === 'full' && $validated['prescription_method'] === 'manual') ? ($validated['axis_left'] ?? null) : null,
                'file' => null,
                'note' => $validated['prescription_note'] ?? null,
            ];

            if ($validated['prescription_method'] === 'upload' && $request->hasFile('prescription_file')) {
                $prescription['file'] = $request->file('prescription_file')->store('prescriptions', 'public');
            }
        }

        $cart = session('cart', []);

        // Produk tanpa resep tetap digabung by product_id seperti sebelumnya.
        // Produk dengan resep selalu jadi baris baru (tiap resep berbeda = order terpisah),
        // supaya resep mata kanan/kiri orang A tidak ketimpa/tercampur sama punya orang B.
        if (! $prescriptionType && isset($cart[$product->id])) {
            $cart[$product->id]['quantity'] += $quantity;
        } else {
            $key = $prescriptionType ? $product->id . '-' . Str::random(8) : (string) $product->id;

            $cart[$key] = [
                'key' => $key,
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->main_image,
                'quantity' => $quantity,
                'prescription' => $prescription,
            ];
        }

        session(['cart' => $cart]);

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

        $cart = session('cart', []);

        if (isset($cart[$key])) {
            $cart[$key]['quantity'] = (int) $request->input('quantity');
            session(['cart' => $cart]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(string $key)
    {
        $cart = session('cart', []);

        if (isset($cart[$key]['prescription']['file'])) {
            Storage::disk('public')->delete($cart[$key]['prescription']['file']);
        }

        unset($cart[$key]);

        session(['cart' => $cart]);

        return redirect()->route('cart.index');
    }
}
