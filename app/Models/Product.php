<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'slug', 'category', 'brand', 'description', 'price', 'stock', 'images',
    ];

    protected $casts = [
        'images' => 'array',
        'price' => 'decimal:2',
    ];

    // Kategori resmi yang dipakai di seluruh sistem (dropdown admin, filter, & logic resep).
    public const CATEGORY_FRAME = 'Frame';
    public const CATEGORY_LENSA = 'Lensa';
    public const CATEGORY_SOFLENS = 'Soflens';
    public const CATEGORY_CAIRAN_SOFLENS = 'Cairan Soflens';

    public const CATEGORIES = [
        self::CATEGORY_FRAME,
        self::CATEGORY_LENSA,
        self::CATEGORY_SOFLENS,
        self::CATEGORY_CAIRAN_SOFLENS,
    ];

    // Tipe resep per kategori:
    // - 'full'         => Lensa: butuh sph + cylinder (kanan & kiri), atau upload resep
    // - 'sphere_only'  => Soflens: cukup sph (minus) kanan & kiri saja, atau upload resep
    // - null           => Frame & Cairan Soflens: tidak butuh resep sama sekali
    public function prescriptionType(): ?string
    {
        return match ($this->category) {
            self::CATEGORY_LENSA => 'full',
            self::CATEGORY_SOFLENS => 'sphere_only',
            default => null,
        };
    }

    public function needsPrescription(): bool
    {
        return $this->prescriptionType() !== null;
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function getMainImageAttribute(): ?string
    {
        return $this->images[0] ?? null;
    }
}
