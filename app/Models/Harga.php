<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harga extends Model
{
    use HasFactory;
    protected $table = 'harga';
    protected $primaryKey = 'ID_Harga';
    protected $fillable = [
        'ID_Rute',
        'Tipe_Penumpang',
        'Kelas',
        'Harga',
    ];
    public $timestamps = false;

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute');
    }

    public function tikets()
    {
        return $this->hasMany(Tiket::class, 'ID_Harga', 'ID_Harga');
    }
}
