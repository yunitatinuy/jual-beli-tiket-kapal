<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    use HasFactory;
    protected $table = 'pelabuhan'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'idpelabuhan'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'namapelabuhan',
        'namakapal',

    ];
    public $timestamps = false;
}
