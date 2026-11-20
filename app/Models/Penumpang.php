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
        'ID_Pesanan',
        'ID_Tiket',
        'id', // ID user pemesan
        'Nama_Lengkap',
        'Telepon',
        'Alamat',
        'Jenis_Kelamin',
        'Tipe_Penumpang',
        'Harga',
    ];

    protected $casts = [
        'Jenis_Kelamin' => JenisKelamin::class,
        'Harga' => 'integer',
        'Checkin_Pada' => 'datetime',
    ];

    public $timestamps = false;

    /** User yang memesan (kolom `id` berisi ID user) */
    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'ID_Pesanan', 'ID_Pesanan');
    }

    public function tiket()
    {
        return $this->belongsTo(Tiket::class, 'ID_Tiket', 'ID_Tiket');
    }
}
