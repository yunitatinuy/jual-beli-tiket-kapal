<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice PDF</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=DejaVu+Sans&display=swap');
        body {
            font-family: 'DejaVu Sans', sans-serif;
        }
        .container {
            max-width: 85rem;
            margin: 0 auto;
            padding: 1rem;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header img {
            width: 80px;
            height: 80px;
        }
        .header h1 {
            color: #1E40AF;
            font-size: 1.25rem;
        }
        .header h2 {
            font-size: 2rem;
        }
        .header span {
            color: #6B7280;
        }
        .content {
            margin-top: 2rem;
        }
        .content address, .content dl {
            margin-bottom: 1rem;
        }
        .content dl {
            display: flex;
            justify-content: space-between;
        }
        .content dl dt, .content dl dd {
            flex: 1;
        }
        .table {
            margin-top: 1.5rem;
            border: 1px solid #E5E7EB;
            border-radius: 0.5rem;
            padding: 1rem;
        }
        .table .row {
            display: flex;
            justify-content: space-between;
        }
        .table .row:not(:last-child) {
            border-bottom: 1px solid #E5E7EB;
            padding-bottom: 0.5rem;
            margin-bottom: 0.5rem;
        }
        .footer {
            margin-top: 2rem;
        }
        .footer p {
            margin-bottom: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <img src="{{ public_path('img/logo3.png') }}" alt="logo">
                <h1>Anang Ferry</h1>
            </div>
            <div>
                <h2>Invoice</h2>
                <span>{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</span>
            </div>
        </div>
        <div class="content">
            @php
                $rute = $pesanan->rute;
            @endphp
            <address>
                Nama Pemesan: {{ $pesanan->user->name }}<br>
                Email: {{ $pesanan->user->email }}
            </address>
            <dl>
                <dt>Kode Booking:</dt>
                <dd>{{ $pesanan->Kode_Booking ?? $pesanan->ID_Pesanan }}</dd>
                <dt>Status:</dt>
                <dd>{{ $pesanan->status_label }}</dd>
                <dt>Tanggal Pemesanan:</dt>
                <dd>{{ \Carbon\Carbon::parse($pesanan->Tanggal_Pesanan)->format('d-m-Y') }} {{ substr($pesanan->Waktu, 0, 5) }}</dd>
            </dl>
            <div class="table">
                <div class="row">
                    <div>Rute</div>
                    <div>Nama Kapal</div>
                    <div>Keberangkatan</div>
                    <div>Kelas</div>
                </div>
                <div class="row">
                    <div>{{ $rute?->pelabuhanAsal?->Nama_Pelabuhan ?? '-' }} - {{ $rute?->pelabuhanTujuan?->Nama_Pelabuhan ?? '-' }}</div>
                    <div>{{ $rute?->kapal?->Nama_Kapal ?? '-' }}</div>
                    <div>{{ $rute ? \Carbon\Carbon::parse($rute->Tanggal)->format('d-m-Y') . ' ' . \Carbon\Carbon::parse($rute->Jam)->format('H:i') : '-' }}</div>
                    <div>{{ $pesanan->Kelas ?? '-' }}</div>
                </div>
            </div>
            <h3>Penumpang:</h3>
            <div class="table">
                <div class="row">
                    <div>Nama Lengkap</div>
                    <div>Tipe</div>
                    <div>Jenis Kelamin</div>
                    <div>No. HP</div>
                </div>
                @foreach ($pesanan->penumpangs as $p)
                    <div class="row">
                        <div>{{ $p->Nama_Lengkap }}</div>
                        <div>{{ ucfirst($p->Tipe_Penumpang) }}</div>
                        <div>{{ ucfirst($p->Jenis_Kelamin) }}</div>
                        <div>{{ $p->Telepon }}</div>
                    </div>
                @endforeach
            </div>
            <div class="table">
                <div class="row">
                    <div>Jenis Penumpang</div>
                    <div>Jumlah</div>
                    <div>Harga Tiket</div>
                    <div>Total</div>
                </div>
                @foreach ($rincian as $r)
                    <div class="row">
                        <div>{{ $r['tipe'] }}</div>
                        <div>{{ $r['jumlah'] }}</div>
                        <div>{{ rupiah($r['harga']) }}</div>
                        <div>{{ rupiah($r['subtotal']) }}</div>
                    </div>
                @endforeach
            </div>
            <dl>
                <dt>Total Harga:</dt>
                <dd>{{ rupiah($pesanan->Total_Harga) }}</dd>
                <dt>Metode Bayar:</dt>
                <dd>{{ $pesanan->Metode_Bayar }}</dd>
            </dl>
            <div class="footer">
                <h4>Terima Kasih!</h4>
                <p>Jika Anda memiliki pertanyaan mengenai Invoice ini, gunakan informasi kontak berikut:</p>
                <p>anangferry@gmail.com</p>
                <p>0896-6969-696</p>
                <p>© {{ date('Y') }} Anang Ferry.</p>
            </div>
        </div>
    </div>
</body>
</html>
