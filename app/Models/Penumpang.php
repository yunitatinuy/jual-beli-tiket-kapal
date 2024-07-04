<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\JenisKelamin;

class Penumpang extends Model
{
    use HasFactory;

    protected $table = 'penumpang';
    protected $primaryKey = 'ID_Penumpang';
    protected $fillable = [
        'id',
        'Nama_Lengkap',
        'Telepon',
        'Alamat',
        'Jenis_Kelamin',
    ];

    protected $casts = [
        'Jenis_Kelamin' => JenisKelamin::class,
    ];

    public $timestamps = false;

    /**
     * Define the relationship with User model.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function pesanan()
    {
        return $this->hasMany(Pesanan::class, 'ID_Penumpang', 'ID_Penumpang');
    }
}
