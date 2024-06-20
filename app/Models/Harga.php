<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = 'harga'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'ID_Harga'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'Tipe_Penumpang',
        'Kelas',
        'Harga',
    ];
    public $timestamps = false;

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }
}
