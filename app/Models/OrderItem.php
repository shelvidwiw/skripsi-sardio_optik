<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id', 'product_id', 'product_name', 'price', 'quantity', 'subtotal',
        'sph_right', 'sph_left', 'cyl_right', 'cyl_left', 'axis_right', 'axis_left',
        'prescription_file', 'prescription_note',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function hasPrescription(): bool
    {
        return $this->prescription_file
            || $this->sph_right !== null
            || $this->sph_left !== null;
    }
}
