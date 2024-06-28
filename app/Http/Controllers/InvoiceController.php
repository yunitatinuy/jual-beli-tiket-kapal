<?php

namespace App\Http\Controllers;

use App\Models\Pesanan;
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
        $pesanan = Pesanan::with('user', 'rute', 'tiket')->findOrFail($id);
        return view('invoices.show', compact('pesanan'));
    }
}
