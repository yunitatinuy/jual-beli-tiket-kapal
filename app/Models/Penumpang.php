<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang';
    protected $primaryKey = 'ID_Penumpang';
    protected $fillable = [
        'id',
        'Nama_Lengkap',
        'Telepon',
        'Alamat',
    ];

    public $timestamps = false;
}
