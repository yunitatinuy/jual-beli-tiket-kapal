<?php

namespace Tests\Feature;

use App\Models\Pesanan;
use App\Models\User;
use App\Services\BookingService;
use App\Services\Payment\MidtransGateway;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

/** Gateway palsu: tidak memanggil server Midtrans */
class FakeMidtransGateway extends MidtransGateway
{
    public array $lastParams = [];
    public array $statuses = [];
    public int $tokenCalls = 0;

    public function createSnapToken(array $params): string
    {
        $this->tokenCalls++;
        $this->lastParams = $params;

        return 'snap-token-' . $params['transaction_details']['order_id'];
    }

    public function status(string $orderId): array
    {
        if (! isset($this->statuses[$orderId])) {
            throw new \RuntimeException('404 transaction not found');
        }

        return $this->statuses[$orderId];
    }
}

class PaymentTest extends TestCase
{
    use RefreshDatabase;

    private const SERVER_KEY = 'SB-Mid-server-test';

    private FakeMidtransGateway $gateway;
    private User $user;
    private int $ruteId;
    private int $tiketDewasa;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.midtrans.server_key' => self::SERVER_KEY,
            'services.midtrans.client_key' => 'SB-Mid-client-test',
            'services.midtrans.batas_bayar_menit' => 60,
        ]);

        $this->gateway = new FakeMidtransGateway();
        $this->app->instance(MidtransGateway::class, $this->gateway);

        $this->user = $this->makeUser();

        $kapal = DB::table('kapal')->insertGetId(['Nama_Kapal' => 'KM Uji', 'Kelas' => 'VIP', 'Jumlah_Kursi' => '100'], 'ID_Kapal');
        $asal = DB::table('pelabuhan')->insertGetId(['Nama_Pelabuhan' => 'Batam Centre', 'Nama_Kota' => 'Batam'], 'ID_Pelabuhan');
        $tujuan = DB::table('pelabuhan')->insertGetId(['Nama_Pelabuhan' => 'Tanjung Pinang', 'Nama_Kota' => 'Tanjung Pinang'], 'ID_Pelabuhan');
        $waktu = now()->addDays(3);
        $this->ruteId = DB::table('rute')->insertGetId([
            'ID_Kapal' => $kapal, 'Pelabuhan_Asal' => $asal, 'Pelabuhan_Tujuan' => $tujuan,
            'Tanggal' => $waktu->toDateString(), 'Jam' => $waktu->format('H:i:s'),
        ], 'ID_Rute');
        $hargaId = DB::table('harga')->insertGetId(['ID_Rute' => $this->ruteId, 'Tipe_Penumpang' => 'dewasa', 'Kelas' => 'VIP', 'Harga' => 150000], 'ID_Harga');
        $this->tiketDewasa = DB::table('tiket')->insertGetId(['ID_Rute' => $this->ruteId, 'ID_Harga' => $hargaId, 'Jumlah_Tiket' => 10], 'ID_Tiket');
    }

    private function makeUser(): User
    {
        $user = new User(['name' => 'Pembeli', 'email' => uniqid('u') . '@example.test', 'password' => bcrypt('password123')]);
        $user->role = 'pengguna';
        $user->email_verified_at = now();
        $user->save();

        return $user;
    }

    private function buatPesanan(int $jumlah = 2): Pesanan
    {
        $penumpang = collect(range(1, $jumlah))->map(fn ($i) => [
            'Nama_Lengkap' => "Penumpang {$i}", 'Telepon' => '081234567890', 'Alamat' => 'Jl. Uji', 'Jenis_Kelamin' => 'laki-laki',
        ])->all();

        return app(BookingService::class)->book($this->user, $this->ruteId, 'VIP', $jumlah, 0, $penumpang);
    }

    private function kuota(): int
    {
        return (int) DB::table('tiket')->where('ID_Tiket', $this->tiketDewasa)->value('Jumlah_Tiket');
    }

    private function notifikasi(Pesanan $pesanan, string $status, ?string $signature = null, ?string $gross = null)
    {
        $gross ??= $pesanan->Total_Harga . '.00';
        $payload = [
            'order_id' => $pesanan->Kode_Booking,
            'status_code' => '200',
            'gross_amount' => $gross,
            'transaction_status' => $status,
        ];
        $payload['signature_key'] = $signature
            ?? hash('sha512', $payload['order_id'] . $payload['status_code'] . $payload['gross_amount'] . self::SERVER_KEY);

        return $this->postJson('/midtrans/notification', $payload);
    }

    private function statusMidtrans(Pesanan $pesanan, string $status, ?int $gross = null): void
    {
        $this->gateway->statuses[$pesanan->Kode_Booking] = [
            'order_id' => $pesanan->Kode_Booking,
            'transaction_id' => 'trx-123',
            'transaction_status' => $status,
            'fraud_status' => 'accept',
            'payment_type' => 'bank_transfer',
            'va_numbers' => [['bank' => 'bca', 'va_number' => '12345']],
            'gross_amount' => ($gross ?? $pesanan->Total_Harga) . '.00',
        ];
    }

    // ---------------------------------------------------------------

    public function test_pesanan_baru_punya_batas_bayar_60_menit(): void
    {
        $pesanan = $this->buatPesanan();

        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $pesanan->Status);
        $this->assertEqualsWithDelta(60, now()->diffInMinutes($pesanan->Batas_Bayar), 1);
        $this->assertSame(8, $this->kuota());
    }

    public function test_tombol_bayar_membuat_snap_token_dengan_nominal_dari_database(): void
    {
        $pesanan = $this->buatPesanan();

        $this->actingAs($this->user)->postJson("/pesanan/{$pesanan->ID_Pesanan}/bayar")
            ->assertOk()
            ->assertJson(['token' => 'snap-token-' . $pesanan->Kode_Booking]);

        $params = $this->gateway->lastParams;
        $this->assertSame($pesanan->Kode_Booking, $params['transaction_details']['order_id']);
        $this->assertSame(300000, $params['transaction_details']['gross_amount']);
        $this->assertSame(300000, collect($params['item_details'])->sum(fn ($i) => $i['price'] * $i['quantity']));
        $this->assertSame('minute', $params['expiry']['unit']);

        // Klik kedua memakai token yang sama, tidak membuat transaksi baru
        $this->actingAs($this->user)->postJson("/pesanan/{$pesanan->ID_Pesanan}/bayar")->assertOk();
        $this->assertSame(1, $this->gateway->tokenCalls);
    }

    public function test_tidak_bisa_membayar_pesanan_milik_orang_lain(): void
    {
        $pesanan = $this->buatPesanan();

        $this->actingAs($this->makeUser())->postJson("/pesanan/{$pesanan->ID_Pesanan}/bayar")->assertNotFound();
    }

    public function test_notifikasi_dengan_signature_salah_ditolak(): void
    {
        $pesanan = $this->buatPesanan();
        $this->statusMidtrans($pesanan, 'settlement');

        $this->notifikasi($pesanan, 'settlement', signature: str_repeat('a', 128))->assertStatus(403);

        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $pesanan->refresh()->Status);
    }

    public function test_notifikasi_settlement_menandai_pesanan_lunas(): void
    {
        $pesanan = $this->buatPesanan();
        $this->statusMidtrans($pesanan, 'settlement');

        $this->notifikasi($pesanan, 'settlement')->assertOk();

        $pesanan->refresh();
        $this->assertSame(Pesanan::STATUS_LUNAS, $pesanan->Status);
        $this->assertNotNull($pesanan->Dibayar_Pada);
        $this->assertSame('Midtrans - Bank Transfer (BCA)', $pesanan->Metode_Bayar);
        $this->assertSame(8, $this->kuota(), 'Kuota tetap terpakai setelah lunas');

        // Notifikasi dikirim ulang: tetap aman
        $this->notifikasi($pesanan, 'settlement')->assertOk();
        $this->assertSame(Pesanan::STATUS_LUNAS, $pesanan->refresh()->Status);
    }

    public function test_email_tiket_lunas_diantrekan_sekali_saja(): void
    {
        \Illuminate\Support\Facades\Mail::fake();
        $pesanan = $this->buatPesanan();
        $this->statusMidtrans($pesanan, 'settlement');

        $this->notifikasi($pesanan, 'settlement')->assertOk();
        $this->notifikasi($pesanan, 'settlement')->assertOk();

        \Illuminate\Support\Facades\Mail::assertQueued(\App\Mail\TiketLunas::class, 1);
        \Illuminate\Support\Facades\Mail::assertQueued(
            \App\Mail\TiketLunas::class,
            fn ($mail) => $mail->hasTo($pesanan->user->email) && $mail->pesanan->is($pesanan)
        );
    }

    public function test_email_tiket_lunas_bisa_dirender(): void
    {
        $pesanan = $this->buatPesanan();
        $pesanan->forceFill(['Status' => Pesanan::STATUS_LUNAS, 'Dibayar_Pada' => now(), 'Metode_Bayar' => 'Midtrans - QRIS'])->save();

        $html = (new \App\Mail\TiketLunas($pesanan))->render();

        $this->assertStringContainsString($pesanan->Kode_Booking, $html);
        $this->assertStringContainsString(rupiah($pesanan->Total_Harga), $html);
    }

    public function test_status_diambil_dari_api_midtrans_bukan_dari_isi_notifikasi(): void
    {
        $pesanan = $this->buatPesanan();
        // Payload bilang settlement, tapi API Midtrans bilang masih pending
        $this->statusMidtrans($pesanan, 'pending');

        $this->notifikasi($pesanan, 'settlement')->assertOk();

        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $pesanan->refresh()->Status);
    }

    public function test_nominal_berbeda_tidak_dianggap_lunas(): void
    {
        $pesanan = $this->buatPesanan();
        $this->statusMidtrans($pesanan, 'settlement', gross: 1000);

        $this->notifikasi($pesanan, 'settlement')->assertOk();

        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $pesanan->refresh()->Status);
    }

    public function test_notifikasi_expire_membatalkan_dan_mengembalikan_kuota(): void
    {
        $pesanan = $this->buatPesanan();
        $this->statusMidtrans($pesanan, 'expire');

        $this->notifikasi($pesanan, 'expire')->assertOk();

        $this->assertSame(Pesanan::STATUS_DIBATALKAN, $pesanan->refresh()->Status);
        $this->assertSame(10, $this->kuota());
    }

    public function test_command_membatalkan_pesanan_yang_lewat_batas_bayar(): void
    {
        $kedaluwarsa = $this->buatPesanan(2);
        $masihAktif = $this->buatPesanan(1);
        $lunas = $this->buatPesanan(1);
        $this->assertSame(6, $this->kuota());

        $kedaluwarsa->forceFill(['Batas_Bayar' => now()->subMinutes(10)])->save();
        $lunas->forceFill(['Batas_Bayar' => now()->subMinutes(10), 'Status' => Pesanan::STATUS_LUNAS])->save();

        $this->artisan('pesanan:batalkan-kedaluwarsa')->assertSuccessful();

        $this->assertSame(Pesanan::STATUS_DIBATALKAN, $kedaluwarsa->refresh()->Status);
        $this->assertSame(Pesanan::STATUS_MENUNGGU_PEMBAYARAN, $masihAktif->refresh()->Status);
        $this->assertSame(Pesanan::STATUS_LUNAS, $lunas->refresh()->Status);
        $this->assertSame(8, $this->kuota(), 'Hanya 2 kursi dari pesanan kedaluwarsa yang dikembalikan');

        // Dijalankan lagi: kuota tidak dikembalikan dua kali
        $this->artisan('pesanan:batalkan-kedaluwarsa')->assertSuccessful();
        $this->assertSame(8, $this->kuota());
    }

    public function test_pembeli_bisa_membatalkan_pesanan_yang_belum_dibayar(): void
    {
        $pesanan = $this->buatPesanan();

        $this->actingAs($this->user)->post("/pesanan/{$pesanan->ID_Pesanan}/batal")
            ->assertRedirect(route('invoices.show', $pesanan->ID_Pesanan));

        $this->assertSame(Pesanan::STATUS_DIBATALKAN, $pesanan->refresh()->Status);
        $this->assertSame(10, $this->kuota());
    }

    public function test_pesanan_yang_sudah_dibayar_di_midtrans_tidak_bisa_dibatalkan(): void
    {
        $pesanan = $this->buatPesanan();
        $pesanan->forceFill(['Snap_Token' => 'tok'])->save();
        $this->statusMidtrans($pesanan, 'settlement');

        $this->actingAs($this->user)->post("/pesanan/{$pesanan->ID_Pesanan}/batal")
            ->assertSessionHas('error');

        $this->assertSame(Pesanan::STATUS_LUNAS, $pesanan->refresh()->Status);
        $this->assertSame(8, $this->kuota());
    }

    public function test_membuka_invoice_yang_lewat_batas_bayar_membatalkan_pesanan(): void
    {
        $pesanan = $this->buatPesanan();
        $pesanan->forceFill(['Batas_Bayar' => now()->subMinute()])->save();

        $this->actingAs($this->user)->get("/invoices/{$pesanan->ID_Pesanan}")
            ->assertOk()
            ->assertSee('Pesanan dibatalkan');

        $this->assertSame(10, $this->kuota());
    }

    public function test_invoice_menampilkan_tombol_bayar_saat_menunggu(): void
    {
        $pesanan = $this->buatPesanan();

        $this->actingAs($this->user)->get("/invoices/{$pesanan->ID_Pesanan}")
            ->assertOk()
            ->assertSee('Bayar Sekarang')
            ->assertSee('app.sandbox.midtrans.com/snap/snap.js', false);
    }
}
