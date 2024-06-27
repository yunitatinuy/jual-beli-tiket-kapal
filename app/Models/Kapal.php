<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kapal extends Model
{
    use HasFactory;
    protected $table = 'kapal'; 
    protected $primaryKey = 'ID_Kapal';
    protected $fillable = [
        'Nama_Kapal',
        'Kelas',
        'Jumlah_Kursi',
    ];
    public $timestamps = false;

    public function rute()
    {
        return $this->hasMany(Rute::class, 'ID_Kapal');
    }
}
