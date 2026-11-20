@php
    use App\Models\Pesanan;
    use Carbon\Carbon;

    $rute = $pesanan->rute;
    $asal = $rute?->pelabuhanAsal;
    $tujuan = $rute?->pelabuhanTujuan;
    $berangkat = $rute ? Carbon::parse($rute->Tanggal . ' ' . $rute->Jam) : null;
    $kode = $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan;
    $lunas = $pesanan->Status === Pesanan::STATUS_LUNAS;
    $batal = $pesanan->Status === Pesanan::STATUS_DIBATALKAN;
    [$badgeBg, $badgeFg] = $lunas ? ['#ecfdf5', '#047857'] : ($batal ? ['#fff1f2', '#be123c'] : ['#fffbeb', '#b45309']);
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $kode }}</title>
    <style>
        @page { margin: 28px 34px; }
        * { box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 10.5px; color: #0f172a; margin: 0; }
        table { width: 100%; border-collapse: collapse; }
        td, th { vertical-align: top; }
        .muted { color: #64748b; }
        .label { font-size: 8.5px; letter-spacing: 1px; text-transform: uppercase; color: #94a3b8; font-weight: bold; }
        .mono { font-family: 'DejaVu Sans Mono', monospace; }
        .h-brand { font-size: 17px; font-weight: bold; color: #0f172a; }
        .code { font-size: 19px; font-weight: bold; letter-spacing: 1px; }
        .badge { display: inline-block; padding: 3px 9px; border-radius: 10px; font-size: 9px; font-weight: bold; background: {{ $badgeBg }}; color: {{ $badgeFg }}; }
        .rule { border-top: 1px solid #e2e8f0; margin: 16px 0; }
        .box { border: 1px solid #e2e8f0; border-radius: 6px; }
        .box-head { background: #f8fafc; padding: 7px 12px; font-size: 8.5px; letter-spacing: 1px; text-transform: uppercase; color: #64748b; font-weight: bold; }
        .port { font-size: 14px; font-weight: bold; }
        .grid td { padding: 8px 12px; border-top: 1px solid #e2e8f0; }
        .data th { background: #f8fafc; color: #64748b; font-size: 8.5px; letter-spacing: 1px; text-transform: uppercase; text-align: left; padding: 7px 10px; }
        .data td { padding: 7px 10px; border-top: 1px solid #f1f5f9; }
        .right, .data th.right { text-align: right; }
        .center, .data th.center { text-align: center; }
        .total td { background: #f8fafc; border-top: 1px solid #e2e8f0; padding: 10px; font-weight: bold; }
        .total .amount { font-size: 14px; }
        .section-title { font-size: 11px; font-weight: bold; margin: 18px 0 7px; }
        .stamp { position: absolute; top: -6px; left: 70px; color: #10b981; opacity: 0.25; border: 3px solid #10b981; border-radius: 6px; padding: 3px 14px; font-size: 22px; font-weight: bold; letter-spacing: 6px; transform: rotate(-12deg); }
        .footer { margin-top: 22px; padding-top: 12px; border-top: 1px solid #e2e8f0; font-size: 9.5px; }
        ul { margin: 4px 0 0 14px; padding: 0; }
        li { margin-bottom: 2px; }
    </style>
</head>
<body>
    {{-- Kepala --}}
    <table>
        <tr>
            <td style="width: 55%;">
                <table>
                    <tr>
                        <td style="width: 52px;"><img src="{{ public_path('img/logo3.png') }}" alt="" style="width: 44px; height: 44px;"></td>
                        <td>
                            <div class="h-brand">Anang Ferry</div>
                            <div class="muted">anangferry@gmail.com &middot; 0896-6969-696<br>Batam, Kepulauan Riau</div>
                        </td>
                    </tr>
                </table>
            </td>
            <td class="right">
                <div class="label">Invoice / E-Tiket</div>
                <div class="code mono">{{ $kode }}</div>
                <div style="margin-top: 4px;"><span class="badge">{{ $pesanan->status_label }}</span></div>
            </td>
        </tr>
    </table>

    <div class="rule"></div>

    {{-- Info --}}
    <table>
        <tr>
            <td style="width: 34%;">
                <div class="label">Ditagihkan kepada</div>
                <div style="font-weight: bold; margin-top: 3px;">{{ $pesanan->user->name }}</div>
                <div class="muted">{{ $pesanan->user->email }}</div>
            </td>
            <td style="width: 33%;">
                <div class="label">Tanggal pemesanan</div>
                <div style="font-weight: bold; margin-top: 3px;">{{ Carbon::parse($pesanan->Tanggal_Pesanan)->translatedFormat('d M Y') }}</div>
                <div class="muted">Pukul {{ substr((string) $pesanan->Waktu, 0, 5) }} WIB</div>
            </td>
            <td>
                @if ($lunas)
                    <div class="label">Dibayar</div>
                    <div style="font-weight: bold; margin-top: 3px;">{{ $pesanan->Dibayar_Pada?->translatedFormat('d M Y, H:i') }} WIB</div>
                    <div class="muted">{{ $pesanan->Metode_Bayar }}</div>
                @elseif ($batal)
                    <div class="label">Status</div>
                    <div style="font-weight: bold; margin-top: 3px; color: #be123c;">Dibatalkan</div>
                    <div class="muted">Tiket tidak berlaku</div>
                @else
                    <div class="label">Batas pembayaran</div>
                    <div style="font-weight: bold; margin-top: 3px;">{{ $pesanan->Batas_Bayar?->translatedFormat('d M Y, H:i') ?? '-' }} WIB</div>
                    <div class="muted">Melalui Midtrans</div>
                @endif
            </td>
        </tr>
    </table>

    {{-- Perjalanan --}}
    <div class="box" style="margin-top: 16px;">
        <table>
            <tr>
                <td class="box-head">Detail perjalanan</td>
                <td class="box-head right">Kelas {{ $pesanan->Kelas ?? '-' }}</td>
            </tr>
        </table>
        <table style="margin: 12px 0;">
            <tr>
                <td style="width: 42%; padding-left: 12px;">
                    <div class="muted" style="font-size: 9px;">Dari</div>
                    <div class="port">{{ $asal?->Nama_Pelabuhan ?? '-' }}</div>
                    <div class="muted">{{ $asal?->Nama_Kota }}</div>
                </td>
                <td class="center" style="width: 16%; padding-top: 12px; color: #f49855; font-size: 16px;">&rarr;</td>
                <td class="right" style="padding-right: 12px;">
                    <div class="muted" style="font-size: 9px;">Ke</div>
                    <div class="port">{{ $tujuan?->Nama_Pelabuhan ?? '-' }}</div>
                    <div class="muted">{{ $tujuan?->Nama_Kota }}</div>
                </td>
            </tr>
        </table>
        <table class="grid">
            <tr>
                <td><div class="muted" style="font-size: 9px;">Tanggal</div><strong>{{ $berangkat?->translatedFormat('D, d M Y') ?? '-' }}</strong></td>
                <td><div class="muted" style="font-size: 9px;">Jam berangkat</div><strong>{{ $berangkat?->format('H:i') ?? '-' }} WIB</strong></td>
                <td><div class="muted" style="font-size: 9px;">Kapal</div><strong>{{ $rute?->kapal?->Nama_Kapal ?? '-' }}</strong></td>
                <td><div class="muted" style="font-size: 9px;">Penumpang</div><strong>{{ $pesanan->penumpangs->count() }} orang</strong></td>
            </tr>
        </table>
    </div>

    {{-- Penumpang --}}
    @if ($qr ?? null)
        <div class="box" style="margin-top: 14px; border-color: #a7f3d0; background: #f0fdf4;">
            <table>
                <tr>
                    <td style="width: 120px; padding: 10px;"><img src="{{ $qr }}" alt="" style="width: 104px; height: 104px; background: #fff;"></td>
                    <td style="padding: 14px 10px 10px 4px;">
                        <div class="label" style="color: #047857;">Boarding pass</div>
                        <div style="font-size: 13px; font-weight: bold; margin-top: 3px;">Tunjukkan QR ini kepada petugas saat naik kapal</div>
                        <div class="muted" style="margin-top: 4px;">Satu QR berlaku untuk semua penumpang dalam pesanan ini.</div>
                        <div class="mono" style="margin-top: 6px; font-weight: bold; letter-spacing: 1px;">{{ $kode }}</div>
                    </td>
                </tr>
            </table>
        </div>
    @endif

    <div class="section-title">Data penumpang</div>
    <div class="box">
        <table class="data">
            <thead>
                <tr><th style="width: 30px;">No</th><th>Nama lengkap</th><th>Tipe</th><th>Jenis kelamin</th><th>No. HP</th></tr>
            </thead>
            <tbody>
                @foreach ($pesanan->penumpangs as $p)
                    <tr>
                        <td class="muted">{{ $loop->iteration }}</td>
                        <td><strong>{{ $p->Nama_Lengkap }}</strong></td>
                        <td>{{ ucfirst($p->Tipe_Penumpang) }}</td>
                        <td>{{ ucfirst($p->Jenis_Kelamin) }}</td>
                        <td>{{ $p->Telepon }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Rincian --}}
    <div class="section-title">Rincian biaya</div>
    <div class="box">
        <table class="data">
            <thead>
                <tr><th>Deskripsi</th><th class="center">Jumlah</th><th class="right">Harga satuan</th><th class="right">Subtotal</th></tr>
            </thead>
            <tbody>
                @foreach ($rincian as $r)
                    <tr>
                        <td>Tiket {{ $r['tipe'] }} &middot; Kelas {{ $pesanan->Kelas }}</td>
                        <td class="center">{{ $r['jumlah'] }}</td>
                        <td class="right">{{ rupiah($r['harga']) }}</td>
                        <td class="right"><strong>{{ rupiah($r['subtotal']) }}</strong></td>
                    </tr>
                @endforeach
                <tr class="total">
                    <td style="position: relative;">@if ($lunas)<div class="stamp">LUNAS</div>@endif</td>
                    <td colspan="2" class="right">Total pembayaran</td>
                    <td class="right amount">{{ rupiah($pesanan->Total_Harga) }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="right muted" style="margin-top: 5px; font-size: 9px;">Metode pembayaran: {{ $pesanan->Metode_Bayar }}</div>

    {{-- Catatan --}}
    <table class="footer">
        <tr>
            <td style="width: 55%;">
                <strong>Informasi keberangkatan</strong>
                <ul class="muted">
                    <li>Tunjukkan kode booking <strong class="mono" style="color: #0f172a;">{{ $kode }}</strong> saat check-in.</li>
                    <li>Datang paling lambat 60 menit sebelum jam berangkat.</li>
                    <li>Bawa identitas sesuai nama penumpang.</li>
                </ul>
            </td>
            <td class="right">
                <strong>Terima kasih telah memilih Anang Ferry</strong>
                <div class="muted" style="margin-top: 4px;">Pertanyaan: anangferry@gmail.com &middot; 0896-6969-696</div>
                <div style="margin-top: 8px; color: #94a3b8; font-size: 8.5px;">Dokumen ini dibuat otomatis dan sah tanpa tanda tangan.<br>Dicetak {{ now()->translatedFormat('d M Y, H:i') }} WIB</div>
            </td>
        </tr>
    </table>
</body>
</html>
