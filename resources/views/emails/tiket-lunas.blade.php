@php
    use Carbon\Carbon;
    $rute = $pesanan->rute;
@endphp
<x-mail::message>
# Pembayaran diterima

Halo {{ $pesanan->user->name }}, pembayaran untuk pesanan Anda sudah kami terima. Tiket Anda **aktif**.

<x-mail::panel>
Kode Booking: **{{ $pesanan->Kode_Booking }}**<br>
Tunjukkan kode ini saat check-in di pelabuhan.
</x-mail::panel>

<x-mail::table>
| Perjalanan | |
|:--|:--|
| Rute | {{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} → {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }} |
| Kapal | {{ $rute?->kapal?->Nama_Kapal ?? '-' }} |
| Berangkat | {{ $rute ? Carbon::parse($rute->Tanggal)->format('d-m-Y') . ' ' . Carbon::parse($rute->Jam)->format('H:i') : '-' }} WIB |
| Kelas | {{ $pesanan->Kelas }} |
| Penumpang | {{ $pesanan->penumpangs->pluck('Nama_Lengkap')->join(', ') }} |
| Total | {{ rupiah($pesanan->Total_Harga) }} |
| Dibayar | {{ $pesanan->Dibayar_Pada?->format('d-m-Y H:i') }} WIB via {{ $pesanan->Metode_Bayar }} |
</x-mail::table>

<x-mail::button :url="route('invoices.show', $pesanan->ID_Pesanan)">
Lihat Invoice
</x-mail::button>

Salam,<br>
{{ config('app.name') }}
</x-mail::message>
