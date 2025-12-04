<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;   // ← WAJIB ADA

class WhatsAppController extends Controller
{
    public function sendReceipt($phone, $items, $total, $paymentMethod = 'Cash')
    {
        // 🔥 Fix nomor WA → ubah 08xxxx jadi 628xxxx
        $phone = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($phone, '0')) {
            $phone = '62' . substr($phone, 1);
        }

        // 🔥 Format item list rapi
        $list = "";
        foreach ($items as $item) {
            $list .= "- {$item['name']} x{$item['qty']} = Rp " . number_format($item['subtotal'], 0, ',', '.') . "\n";
        }

        // 🔥 Hapus indentasi agar WA tidak error
        $message =
"*📄 STRUK BELANJA*\n" .
"📅 *Tanggal:* " . now()->format('d/m/Y H:i') . "\n\n" .
"*🛒 Daftar Barang:*\n" .
$list . "\n" .
"*💵 Total:* Rp " . number_format($total,0,',','.') . "\n" .
"*💳 Pembayaran:* {$paymentMethod}\n\n" .
"Terima kasih telah berbelanja 🙏";

        // 🔥 Kirim ke Fonnte
        $response = Http::withHeaders([
            'Authorization' => env('FONNTE_TOKEN'),   // Token WAJIB di .env
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
        ]);

        // 🔥 Jika Fonnte error
        if (!$response->json() || ($response->json()['status'] ?? false) != true) {
            throw new \Exception("Gagal mengirim WhatsApp: " . json_encode($response->json()));
        }

        return $response->json();
    }
}
