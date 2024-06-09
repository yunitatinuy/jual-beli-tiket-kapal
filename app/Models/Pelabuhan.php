<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pelabuhan extends Model
{
    use HasFactory;
    protected $table = 'pelabuhan'; // sesuaikan dengan nama tabel Anda
    protected $primaryKey = 'ID_Pelabuhan'; // sesuaikan dengan nama primary key Anda
    protected $fillable = [
        'Nama_Pelabuhan',
        'Nama_Kota',

    ];
    public $timestamps = false;

    public function rute()
    {
        return $this->hasMany(Rute::class, 'ID_Pelabuhan');
    }
}
