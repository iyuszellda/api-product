<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * You can use '*' to trust all proxies, or specify IPs.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*';
}