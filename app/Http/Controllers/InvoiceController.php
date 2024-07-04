<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;

class InvoiceController extends Controller
{
    public function createInvoice(Request $request, $pesananId)
    {
        $pesanan = Pesanan::findOrFail($pesananId);
        $user = Auth::user();

        return redirect()->route('invoices.show', $pesanan->ID_Pesanan);
    }

    public function show($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $penumpang = Penumpang::where('id', $pesanan->ID_User)
            ->orderBy('ID_Penumpang', 'desc')
            ->first();

        return view('invoices.show', compact('pesanan', 'penumpang'));
    }

    public function generatePDF($id)
    {
        $pesanan = Pesanan::findOrFail($id);

        $penumpang = Penumpang::where('id', $pesanan->ID_User)
            ->orderBy('ID_Penumpang', 'desc')
            ->first();

        $pdf = PDF::loadView('invoices.pdf', compact('pesanan', 'penumpang'))->setPaper('a4');
        return $pdf->download('invoices.pdf');
    }
}
