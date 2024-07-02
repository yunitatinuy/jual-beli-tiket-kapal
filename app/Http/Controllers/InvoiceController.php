<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Penumpang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
}
