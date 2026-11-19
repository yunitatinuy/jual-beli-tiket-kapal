<?php

namespace App\Services;

use RuntimeException;

/**
 * Kesalahan bisnis saat memesan (kuota habis, rute sudah berangkat, dll).
 * Pesannya aman untuk ditampilkan ke pengguna.
 */
class BookingException extends RuntimeException
{
}
