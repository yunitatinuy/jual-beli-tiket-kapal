<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pesanan extends Model
{
    use HasFactory;

    protected $table = 'pesanan';
    protected $primaryKey = 'ID_Pesanan';

    // Semua kolom diisi oleh server (BookingService), bukan langsung dari form
    protected $fillable = [
        'Kode_Booking',
        'ID_User',
        'ID_Rute',
        'Kelas',
        'Jumlah_Dewasa',
        'Jumlah_Anak',
        'Tanggal_Pesanan',
        'Waktu',
        'Total_Harga',
        'Metode_Bayar',
        'Status',
        'Batas_Bayar',
    ];

    protected $casts = [
        'Batas_Bayar' => 'datetime',
        'Dibayar_Pada' => 'datetime',
        'Total_Harga' => 'integer',
        'Jumlah_Dewasa' => 'integer',
        'Jumlah_Anak' => 'integer',
    ];

    public const STATUS_MENUNGGU_PEMBAYARAN = 'menunggu_pembayaran';
    public const STATUS_LUNAS = 'lunas';
    public const STATUS_DIBATALKAN = 'dibatalkan';

    public const STATUS_LABEL = [
        self::STATUS_MENUNGGU_PEMBAYARAN => 'Menunggu Pembayaran',
        self::STATUS_LUNAS => 'Lunas',
        self::STATUS_DIBATALKAN => 'Dibatalkan',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'ID_User', 'id');
    }

    public function rute()
    {
        return $this->belongsTo(Rute::class, 'ID_Rute', 'ID_Rute');
    }

    /** Semua penumpang dalam pesanan ini */
    public function penumpangs()
    {
        return $this->hasMany(Penumpang::class, 'ID_Pesanan', 'ID_Pesanan');
    }

    public function menungguPembayaran(): bool
    {
        return $this->Status === self::STATUS_MENUNGGU_PEMBAYARAN;
    }

    public function sudahKedaluwarsa(): bool
    {
        return $this->menungguPembayaran() && $this->Batas_Bayar && $this->Batas_Bayar->isPast();
    }

    public function getStatusLabelAttribute(): string
    {
        return self::STATUS_LABEL[$this->Status] ?? ucfirst((string) $this->Status);
    }
}
