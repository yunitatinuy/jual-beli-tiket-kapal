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
        'ID_Penumpang',
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

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'id');
    }

    public function penumpang()
    {
        return $this->belongsTo(Penumpang::class, 'ID_Penumpang', 'ID_Penumpang');
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'ID_Tiket');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }
}
