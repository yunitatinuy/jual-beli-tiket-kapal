<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;
    protected $table = 'kapal'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'idkapal'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'namakapal',
        'kelas',
        'jumlahkursi',
    ];
    public $timestamps = false;
}
