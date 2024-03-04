<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function getData() 
    {
        $dataBooking = [
            ['id' => 1, 'nama' => 'Meja 1', 'waktu' => '1 jam', 'harga' => 50000],
            ['id' => 2, 'nama' => 'Meja 2', 'waktu' => '2 jam', 'harga' => 75000],
            ['id' => 3, 'nama' => 'Meja 3', 'waktu' => '1 jam', 'harga' => 50000],
            ['id' => 4, 'nama' => 'Meja 4', 'waktu' => '2 jam', 'harga' => 75000],
            ['id' => 5, 'nama' => 'Meja 5', 'waktu' => '3 jam', 'harga' => 100000],
        ];

        return $dataBooking;
    }

    public function tampilkan(){
        $data = $this->getData();
        return view('booking_view', compact('data'));
    }
}