<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rute extends Model
{
    use HasFactory;
    protected $table = 'rute';
    protected $primaryKey = 'ID_Rute';
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
        return $this->belongsTo(Kapal::class, 'ID_Kapal', 'ID_Kapal');
    }

    public function pelabuhanAsal()
    {
        return $this->belongsTo(Pelabuhan::class, 'Pelabuhan_Asal', 'ID_Pelabuhan');
    }

    public function pelabuhanTujuan()
    {
        return $this->belongsTo(Pelabuhan::class, 'Pelabuhan_Tujuan', 'ID_Pelabuhan');
    }

    public function tiket()
    {
        return $this->hasMany(Tiket::class, 'ID_Rute', 'ID_Rute');
    }

    public function harga()
    {
        return $this->hasMany(Harga::class, 'ID_Rute');
    }

    public function pesanans()
    {
        return $this->hasMany(Pesanan::class, 'ID_Rute', 'ID_Rute');
    }

    /** Jadwal hari ini dan seterusnya. */
    public function scopeMendatang($query)
    {
        return $query->where('Tanggal', '>=', now()->toDateString());
    }

    /** Label untuk dropdown admin, mis. "#34 Ketapang → Gilimanuk, 20-11-2026 07:00". */
    public function getLabelAttribute(): string
    {
        return sprintf(
            '#%d %s → %s, %s %s',
            $this->ID_Rute,
            $this->pelabuhanAsal?->Nama_Pelabuhan ?? '?',
            $this->pelabuhanTujuan?->Nama_Pelabuhan ?? '?',
            \Carbon\Carbon::parse($this->Tanggal)->format('d-m-Y'),
            substr((string) $this->Jam, 0, 5)
        );
    }
}
