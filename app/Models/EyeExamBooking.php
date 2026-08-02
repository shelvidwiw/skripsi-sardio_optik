<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EyeExamBooking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code', 'name', 'whatsapp_number', 'preferred_date', 'preferred_time', 'note', 'status',
    ];

    protected $casts = [
        'preferred_date' => 'date',
    ];

    public static function generateBookingCode(): string
    {
        return 'EXM-' . now()->format('Ymd') . '-' . strtoupper(uniqid());
    }
}
