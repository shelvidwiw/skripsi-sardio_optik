<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::query();

        // Search (nama, kategori, brand)
        if ($request->filled('q')) {
            $keyword = $request->input('q');
            $query->where(function ($sub) use ($keyword) {
                $sub->where('name', 'like', "%{$keyword}%")
                    ->orWhere('category', 'like', "%{$keyword}%")
                    ->orWhere('brand', 'like', "%{$keyword}%");
            });
        }

        // Filter Category (bisa pilih lebih dari satu)
        if ($request->filled('category')) {
            $query->whereIn('category', (array) $request->input('category'));
        }

        // Filter Brand (bisa pilih lebih dari satu)
        if ($request->filled('brand')) {
            $query->whereIn('brand', (array) $request->input('brand'));
        }

        // Batas atas slider mengikuti harga produk termahal saat ini, dibulatkan ke atas
        $priceCeiling = (int) (Product::max('price') ?: 1000000);
        $priceCeiling = max(100000, (int) (ceil($priceCeiling / 100000) * 100000));

        $maxPrice = $request->filled('max_price') ? (int) $request->input('max_price') : $priceCeiling;
        $query->where('price', '<=', $maxPrice);

        // Sort
        switch ($request->input('sort')) {
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name_asc':
                $query->orderBy('name', 'asc');
                break;
            default:
                $query->latest();
                break;
        }

        $products = $query->paginate(12)->withQueryString();

        // Opsi filter diambil langsung dari data yang ada di database (bukan hardcode)
        $categories = Product::whereNotNull('category')->where('category', '!=', '')
            ->distinct()->orderBy('category')->pluck('category');

        $brands = Product::whereNotNull('brand')->where('brand', '!=', '')
            ->distinct()->orderBy('brand')->pluck('brand');

        return view('products.index', compact('products', 'categories', 'brands', 'priceCeiling', 'maxPrice'));
    }

    public function show(Product $product)
    {
        return view('products.show', compact('product'));
    }
}
