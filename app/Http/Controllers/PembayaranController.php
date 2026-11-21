<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Services\BookingException;
use App\Services\BookingService;
use App\Services\PaymentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class PembayaranController extends Controller
{
    public function __construct(
        private PaymentService $payment,
        private BookingService $booking,
    ) {
    }

    /** Buat / ambil Snap token untuk tombol "Bayar Sekarang" (dipanggil via AJAX). */
    public function bayar(Request $request, $id)
    {
        $pesanan = $this->findOwned($request, $id);

        try {
            $token = $this->payment->snapToken($pesanan);
        } catch (BookingException $e) {
            return response()->json(['message' => $e->getMessage()], 422);
        } catch (\Throwable $e) {
            Log::error('Gagal membuat transaksi Midtrans', ['pesanan' => $pesanan->ID_Pesanan, 'error' => $e->getMessage()]);

            return response()->json(['message' => 'Layanan pembayaran sedang tidak tersedia. Coba lagi beberapa saat.'], 503);
        }

        return response()->json(['token' => $token]);
    }

    /** Sinkronkan status pembayaran dari Midtrans (setelah popup Snap ditutup). */
    public function cekStatus(Request $request, $id)
    {
        $pesanan = $this->payment->syncStatus($this->findOwned($request, $id));

        return redirect()->route('invoices.show', $pesanan->ID_Pesanan)
            ->with($pesanan->Status === Pesanan::STATUS_LUNAS ? 'success' : 'info',
                $pesanan->Status === Pesanan::STATUS_LUNAS
                    ? 'Pembayaran berhasil. Tiket Anda sudah aktif.'
                    : 'Status pesanan: ' . $pesanan->status_label . '.');
    }

    /** Pembeli membatalkan pesanan yang belum dibayar. */
    public function batal(Request $request, $id)
    {
        $pesanan = $this->findOwned($request, $id);

        // Pastikan belum dibayar di Midtrans sebelum membatalkan
        $pesanan = $this->payment->syncStatus($pesanan);

        if (! $this->booking->cancel($pesanan, 'Dibatalkan oleh pembeli')) {
            return back()->with('error', 'Pesanan ini tidak bisa dibatalkan.');
        }

        return redirect()->route('invoices.show', $pesanan->ID_Pesanan)
            ->with('info', 'Pesanan dibatalkan dan kursi sudah dilepas.');
    }

    /** Simulasi pembayaran berhasil khusus mode sandbox/testing lokal. */
    public function simulasiSandbox(Request $request, $id)
    {
        if (config('services.midtrans.is_production', false)) {
            abort(403, 'Simulasi hanya dapat digunakan pada mode Sandbox.');
        }

        $pesanan = $this->findOwned($request, $id);

        if ($pesanan->menungguPembayaran()) {
            $pesanan->forceFill([
                'Status' => Pesanan::STATUS_LUNAS,
                'Dibayar_Pada' => now(),
                'Midtrans_Transaction_Id' => 'SANDBOX-' . strtoupper(\Illuminate\Support\Str::random(10)),
                'Midtrans_Payment_Type' => 'qris',
                'Metode_Bayar' => 'QRIS (Sandbox Simulasi)',
            ])->save();
        }

        return redirect()->route('invoices.show', $pesanan->ID_Pesanan)
            ->with('success', 'Simulasi pembayaran Sandbox berhasil! E-tiket telah diterbitkan.');
    }

    /** Webhook dari Midtrans (Payment Notification URL). */
    public function notification(Request $request)
    {
        try {
            $ok = $this->payment->handleNotification($request->all());
        } catch (\Throwable $e) {
            Log::error('Midtrans: gagal memproses notifikasi', ['error' => $e->getMessage()]);

            // 500 membuat Midtrans mengirim ulang notifikasi nanti
            return response()->json(['message' => 'error'], 500);
        }

        return $ok
            ? response()->json(['message' => 'ok'])
            : response()->json(['message' => 'invalid signature'], 403);
    }

    private function findOwned(Request $request, $id): Pesanan
    {
        return Pesanan::where('ID_Pesanan', $id)
            ->where('ID_User', $request->user()->id)
            ->firstOrFail();
    }
}
