<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Http\Controllers\PesananController;
// use App\Http\Controllers\WhatsAppController; // <-- sudah tidak dipakai kalau pakai wa.me

class POSController extends Controller
{
    public function index()
    {
        $menus = Menu::where('status','tersedia')->orderBy('nama')->get();
        return view('kasir.dashboard.index', compact('menus'));
    }

    public function store(Request $request)
    {
        try {

            // STEP 1: Ambil JSON dari fetch()
            $data = $request->json()->all();

            // STEP 2: Merge supaya $request->items, $request->nomor_wa, dll bisa diakses normal
            $request->merge($data);

            // STEP 3: Simpan transaksi lewat PesananController (punya kamu sendiri)
            $pesananController = app()->make(PesananController::class);
            $pesanan = $pesananController->store($request, true);

            /*
            |--------------------------------------------------------------------------
            |  STEP 4: Siapkan link wa.me untuk kirim struk
            |--------------------------------------------------------------------------
            */
            $waLink = null;

            // Hanya buat link kalau nomor WA diisi
            if (!empty($request->nomor_wa)) {

                // 4a. Format nomor WA → 08xx jadi 62xx
                $nomor = preg_replace('/[^0-9]/', '', $request->nomor_wa); // buang spasi/titik
                if (substr($nomor, 0, 1) === "0") {
                    $nomor = "62" . substr($nomor, 1);
                }

                // 4b. Format list barang
                $itemsText = "";
                $total = 0;

                foreach ($request->items as $item) {
                    $subtotal = $item['qty'] * $item['harga'];
                    $total += $subtotal;
                    $itemsText .= "- {$item['nama']} x{$item['qty']} = Rp " . number_format($subtotal, 0, ',', '.') . "\n";
                }

                // 4c. Pesan struk (plain text dulu)
                $plainMessage =
"📄 *Struk Belanja*
Tanggal: " . date('d/m/Y H:i') . "

*Barang:*
{$itemsText}
*Total:* Rp " . number_format($total, 0, ',', '.') . "
*Pembayaran:* " . strtoupper($request->metode) . "

Terima kasih 🙏";

                // 4d. URL-encode pesan untuk dipakai di wa.me
                $encodedMessage = urlencode($plainMessage);

                // 4e. Buat link wa.me
                $waLink = "https://wa.me/{$nomor}?text={$encodedMessage}";
            }

            // STEP 5: kirim response ke frontend
            return response()->json([
                'success'   => true,
                'message'   => 'Transaksi berhasil disimpan',
                'wa_link'   => $waLink,  // <-- ini nanti dipakai di JS
                'struk_url' => null
            ]);

        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
                'line'    => $e->getLine(),
                'file'    => $e->getFile(),
            ], 500);

        }
    }

    public function riwayat()
    {
        $riwayat = Pesanan::where('kasir_id', auth()->id())
                    ->latest()
                    ->get();

        return view('kasir.riwayat.index', compact('riwayat'));
    }

}
