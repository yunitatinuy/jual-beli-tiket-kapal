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
        'Jumlah_Tiket',
        'Harga_Reguler',
        'Harga_VIP',
    ];
    public $timestamps = false;
    
    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }
}
