<?php

namespace App\Services;

use App\Models\Order;

class WhatsAppMessageBuilder
{
    public function url(Order $order, $subtotal, $tax): string
    {
        $adminNumber = env('ADMIN_WHATSAPP_NUMBER', '6281234567890');

        return 'https://wa.me/'.$adminNumber.'?text='.rawurlencode($this->message($order, $subtotal, $tax));
    }

    /**
     * Susun teks pesan WhatsApp berisi ringkasan pesanan untuk admin.
     */
    public function message(Order $order, $subtotal, $tax): string
    {
        $lines = [
            'Halo Sardio Optik, saya ingin konfirmasi pesanan.',
            "Kode Pesanan: {$order->order_code}",
            "Nama: {$order->customer_name}",
        ];

        if ($order->street_address) {
            $lines[] = "Alamat: {$order->street_address}, {$order->city} {$order->postal_code}";
        }

        $lines[] = 'Subtotal: Rp '.number_format((float) $subtotal, 0, ',', '.');
        $lines[] = 'PPN (11%): Rp '.number_format((float) $tax, 0, ',', '.');
        $lines[] = 'Total: Rp '.number_format((float) $order->total_amount, 0, ',', '.');
        $lines[] = 'Detail:';
        $lines[] = rtrim($this->itemsSummary($order));

        return implode("\n", $lines);
    }

    /**
     * Susun baris ringkasan tiap item pesanan (nama, quantity, penanda resep).
     */
    private function itemsSummary(Order $order): string
    {
        $summary = '';

        foreach ($order->items as $item) {
            $summary .= "- {$item->product_name} x{$item->quantity}";
            $summary .= $item->hasPrescription() ? " (ada resep, cek detail order)\n" : "\n";
        }

        return $summary;
    }
}