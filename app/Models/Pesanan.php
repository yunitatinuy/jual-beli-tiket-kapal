<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $guarded=[];
    protected $table = 'pesanan';
    protected $primaryKey = 'ID_Pesanan';
    protected $fillable = [
        'ID_Rute',
        'ID_User',
        'ID_Tiket',
        'Tanggal_Pesanan',
        'Waktu',
        'Total_Pesanan',
    ];

    public $timestamps = false;

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'ID_Tiket');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }
}
