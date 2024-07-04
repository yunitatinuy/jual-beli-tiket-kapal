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
                <span>{{ $pesanan->ID_Pesanan }}</span>
            </div>
        </div>
        <div class="content">
            <address>
                Nama Pemesan: {{ $pesanan->user->name }}<br>
                Email: {{ $pesanan->user->email }}
            </address>
            <h3>Biodata Penumpang:</h3>
            <address>
                Nama Lengkap: {{ $penumpang->Nama_Lengkap }}<br>
                Alamat: {{ $penumpang->Alamat }}<br>
                No.HP: {{ $penumpang->Telepon }}<br>
                Jenis Kelamin: {{ $penumpang->Jenis_Kelamin }}<br>
            </address>
            <dl>
                <dt>Tanggal Pemesanan:</dt>
                <dd>{{ $pesanan->Tanggal_Pesanan }}</dd>
                <dt>Waktu Pemesanan:</dt>
                <dd>{{ $pesanan->Waktu }}</dd>
            </dl>
            <div class="table">
                <div class="row">
                    <div>Tanggal</div>
                    <div>Nama Kapal</div>
                    <div>Jadwal Keberangkatan</div>
                    <div>Waktu</div>
                </div>
                <div class="row">
                    <div>18/07/2024</div>
                    <div>Anang Kapal</div>
                    <div>18/07/2024</div>
                    <div>23.30</div>
                </div>
            </div>
            <div class="table">
                <div class="row">
                    <div>Jenis Penumpang</div>
                    <div>Jumlah</div>
                    <div>Harga Tiket</div>
                    <div>Total</div>
                </div>
                <div class="row">
                    <div>Dewasa</div>
                    <div>1</div>
                    <div>Rp145.000</div>
                    <div>Rp145.000</div>
                </div>
                <div class="row">
                    <div>Anak</div>
                    <div>1</div>
                    <div>Rp100.000</div>
                    <div>Rp100.000</div>
                </div>
            </div>
            <dl>
                <dt>Total Harga:</dt>
                <dd>Rp{{ number_format($pesanan->Total_Harga, 0, ',', '.') }}.000</dd>
                <dt>Metode Bayar:</dt>
                <dd>{{ $pesanan->Metode_Bayar }}</dd>
            </dl>
            <div class="footer">
                <h4>Terima Kasih!</h4>
                <p>Jika Anda memiliki pertanyaan mengenai Invoice ini, gunakan informasi kontak berikut:</p>
                <p>anangferry@gmail.com</p>
                <p>0896-6969-696</p>
                <p>© 2024 Anang Ferry.</p>
            </div>
        </div>
    </div>
</body>
</html>
