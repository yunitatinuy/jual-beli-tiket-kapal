<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;
    protected $table = 'rute'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'ID_Rute'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'ID_Kapal',
        'Pelabuhan_Asal',
        'Pelabuhan_Tujuan',
        'Tanggal',
        'Jam',

    ];
    public $timestamps = false;

    public function kapal()
    {
        return $this->belongsTo(Kapal::class,'ID_Kapal', 'ID_Kapal');  
    }

    public function pelabuhanAsal()
    {
        return $this->belongsTo(Pelabuhan::class, 'Pelabuhan_Asal', 'ID_Pelabuhan');
    }

    public function pelabuhanTujuan()
    {
        return $this->belongsTo(Pelabuhan::class, 'Pelabuhan_Tujuan', 'ID_Pelabuhan');
    }
}
