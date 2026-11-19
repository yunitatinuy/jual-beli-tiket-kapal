<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoiceController extends Controller
{
    public function __construct(
        private PaymentService $payment,
        private BookingService $booking,
    ) {
    }

    public function show($id)
    {
        $pesanan = $this->findOwnedPesanan($id);

        if ($pesanan->menungguPembayaran()) {
            // Di localhost webhook Midtrans tidak sampai, jadi cek status langsung
            $pesanan = $this->payment->syncStatus($pesanan);

            if ($pesanan->sudahKedaluwarsa()) {
                $this->booking->cancel($pesanan, 'Batas waktu pembayaran habis');
            }
        }

        $gateway = $this->payment->gateway();

        return view('invoices.show', $this->invoiceData($id) + [
            'midtransSiap' => $gateway->isConfigured(),
            'midtransClientKey' => $gateway->clientKey(),
            'midtransSnapJs' => $gateway->snapJsUrl(),
        ]);
    }

    public function generatePDF($id)
    {
        $data = $this->invoiceData($id);

        $pdf = PDF::loadView('invoices.pdf', $data)->setPaper('a4');

        return $pdf->download('invoice-' . ($data['pesanan']->Kode_Booking ?? $data['pesanan']->ID_Pesanan) . '.pdf');
    }

    private function invoiceData($id): array
    {
        $pesanan = $this->findOwnedPesanan($id);
        $pesanan->load(['user', 'rute.kapal', 'rute.pelabuhanAsal', 'rute.pelabuhanTujuan', 'penumpangs']);

        // Rincian harga per tipe penumpang, dari harga yang tersimpan saat pemesanan
        $rincian = $pesanan->penumpangs
            ->groupBy('Tipe_Penumpang')
            ->map(fn ($group, $tipe) => [
                'tipe' => ucfirst((string) $tipe),
                'jumlah' => $group->count(),
                'harga' => (int) $group->first()->Harga,
                'subtotal' => (int) $group->sum('Harga'),
            ])
            ->values();

        return compact('pesanan', 'rincian');
    }

    /**
     * Ambil pesanan hanya jika milik user yang sedang login.
     * Pesanan milik orang lain diperlakukan seperti tidak ada (404),
     * agar ID pesanan lain tidak bisa ditebak.
     */
    private function findOwnedPesanan($id): Pesanan
    {
        return Pesanan::where('ID_Pesanan', $id)
            ->where('ID_User', Auth::id())
            ->firstOrFail();
    }
}
