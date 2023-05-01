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

    // TODO: Do not trust all proxies
    // In a production environment, it's recommended to set the proxies
    // value to the IP addresses of your reverse proxy server(s) instead of using *.
    // protected $proxies = ["142.93.103.12"];
    protected $proxies = "*";

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

    protected $headersToTrust = [
        Request::HEADER_X_FORWARDED_FOR,
        Request::HEADER_X_FORWARDED_PROTO,
    ];
}
