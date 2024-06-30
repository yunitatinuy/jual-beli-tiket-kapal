<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded = [];
    protected $table = 'pesanan';
    protected $primaryKey = 'ID_Pesanan';
    protected $fillable = [
        'ID_User',
        'Tanggal_Pesanan',
        'Waktu',
        'Total_Harga',
        'Metode_Bayar',
    ];

    public $timestamps = false;

    const METODE_BAYAR = [
        'BNI - 1528222928',
        'Dana - 083181531047',
        'LinkAja - 083181531047',
    ];

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'ID_Tiket');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }
}
