<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AlbumController extends Controller
{
    public function getData() 
    {
        $dataAlbum = [
            ['id' => 1, 'nama' => 'Glitch Mode', 'grup' => 'NCT Dream', 'harga' => 355000],
            ['id' => 2, 'nama' => 'Fast Check', 'grup' => 'NCT 127', 'harga' => 130000],
            ['id' => 3, 'nama' => 'Phantom', 'grup' => 'WayV', 'harga' => 225000],
            ['id' => 4, 'nama' => 'Feel My Rhythm', 'grup' => 'Red Velvet', 'harga' => 360000],
            ['id' => 5, 'nama' => 'Get A Guitar', 'grup' => 'RIIZE', 'harga' => 250000],
        ];

        return $dataAlbum;
    }

    public function tampilkan(){
        $data = $this->getData();
        return view('album_view', compact('data'));
    }
}
