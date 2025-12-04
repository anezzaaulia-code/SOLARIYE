<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\Pesanan;
use App\Http\Controllers\PesananController;
use App\Http\Controllers\WhatsAppController;

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

            // Ambil JSON dari fetch()
            $data = $request->json()->all();

            // Gabungkan agar kompatibel
            $request->merge($data);

            // Simpan transaksi lewat PesananController
            $pesananController = app()->make(PesananController::class);
            $pesanan = $pesananController->store($request, true);

            /*
            |--------------------------------------------------------------------------
            |  🔥 KIRIM WHATSAPP (SETELAH PESANAN TERSIMPAN)
            |--------------------------------------------------------------------------
            */

            if (!empty($request->nomor_wa)) {

                // Siapkan items untuk struk
                $items = [];
                foreach ($request->items as $item) {
                    $items[] = [
                        'name' => $item['nama'],
                        'qty'  => $item['qty'],
                        'subtotal' => $item['qty'] * $item['harga']
                    ];
                }

                // Hitung total
                $total = array_sum(array_column($items, 'subtotal'));

                // Kirim WA pakai WhatsAppController
                app(WhatsAppController::class)->sendReceipt(
                    $request->nomor_wa,
                    $items,
                    $total,
                    strtoupper($request->metode)
                );
            }

            return response()->json([
                'success' => true,
                'message' => 'Transaksi berhasil & struk WhatsApp terkirim!',
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
