<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function getData() 
    {
        $dataOrder = [
            ['id' => 1, 'menu' => 'Teh Obeng', 'harga' => 5000],
            ['id' => 2, 'menu' => 'Cappucino', 'harga' => 13000],
            ['id' => 3, 'menu' => 'French Fries', 'harga' => 12500],
            ['id' => 4, 'menu' => 'Waffles', 'harga' => 16000],
            ['id' => 5, 'menu' => 'Pisang Keju', 'harga' => 15000],
        ];

        return $dataOrder;
    }

    public function tampilkan(){
        $data = $this->getData();
        return view('order_view', compact('data'));
    }
}
