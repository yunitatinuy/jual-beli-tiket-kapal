<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tiket extends Model
{
    use HasFactory;
    protected $table = 'tiket'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'ID_Tiket'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'ID_Rute',
        'ID_Harga',
        'Jumlah_Tiket',
    ];
    public $timestamps = false;
    
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute', 'ID_Rute');
    }

    public function harga()
    {
        return $this->belongsTo(Harga::class, 'ID_Harga', 'ID_Harga');
    }
}
