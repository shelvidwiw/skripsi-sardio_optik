<?php

namespace App\Http\Requests;

use App\Models\Product;
use Illuminate\Foundation\Http\FormRequest;

class AddToCartRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Rules validasi ditambah produk ke cart.
     * Resep hanya wajib untuk kategori Lensa (full) & Soflens (sphere_only).
     * Frame & Cairan Soflens tidak butuh field ini sama sekali.
     */
    public function rules(): array
    {
        $rules = [
            'quantity' => 'nullable|integer|min:1',
        ];

        $prescriptionType = $this->prescriptionType();

        if (! $prescriptionType) {
            return $rules;
        }

        $rules['prescription_method'] = 'required|in:manual,upload';
        $rules['prescription_file'] = 'required_if:prescription_method,upload|nullable|file|mimes:jpg,jpeg,png,pdf|max:4096';
        $rules['sph_right'] = 'required_if:prescription_method,manual|nullable|numeric|between:-30,30';
        $rules['sph_left'] = 'required_if:prescription_method,manual|nullable|numeric|between:-30,30';
        $rules['prescription_note'] = 'nullable|string|max:500';

        if ($prescriptionType === 'full') {
            $rules['cyl_right'] = 'nullable|numeric|between:-10,10';
            $rules['cyl_left'] = 'nullable|numeric|between:-10,10';
            $rules['axis_right'] = 'nullable|integer|between:0,180';
            $rules['axis_left'] = 'nullable|integer|between:0,180';
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'prescription_file.required_if' => 'Silakan upload foto resep Anda.',
            'sph_right.required_if' => 'Ukuran minus mata kanan wajib diisi.',
            'sph_left.required_if' => 'Ukuran minus mata kiri wajib diisi.',
        ];
    }

    /**
     * Tipe resep ditentukan oleh produk yang di-bind lewat route model binding,
     * jadi rules() & controller bisa sama-sama pakai method ini.
     */
    public function prescriptionType(): ?string
    {
        /** @var Product $product */
        $product = $this->route('product');

        return $product?->prescriptionType();
    }
}