<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'order_code', 'customer_name', 'whatsapp_number', 'street_address', 'city',
        'postal_code', 'total_amount', 'tax_amount', 'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public static function generateOrderCode(): string
    {
        return 'ORD-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
    }
}
