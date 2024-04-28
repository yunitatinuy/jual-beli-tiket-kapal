<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;
    protected $table = 'kapals';
    protected $filltable = [
        'namakapal',
        'kelas',
        'jumlahkursi',
    ];
}
