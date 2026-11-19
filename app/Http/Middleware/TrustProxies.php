<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * Daftar proxy diambil dari config('app.trusted_proxies') / env TRUSTED_PROXIES:
     * kosong = tidak ada, "*" = semua (mis. di belakang Cloudflare / load balancer
     * hosting), atau daftar IP dipisah koma.
     */
    protected function proxies()
    {
        $proxies = (string) config('app.trusted_proxies', '');

        if ($proxies === '') {
            return $this->proxies;
        }

        return $proxies === '*' ? '*' : array_map('trim', explode(',', $proxies));
    }

    /**
     * The headers that should be used to detect proxies.
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |
        Request::HEADER_X_FORWARDED_HOST |
        Request::HEADER_X_FORWARDED_PORT |
        Request::HEADER_X_FORWARDED_PROTO |
        Request::HEADER_X_FORWARDED_AWS_ELB;
}
