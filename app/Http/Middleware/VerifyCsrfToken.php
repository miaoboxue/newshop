<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        '/test/',
        '/pay/alipay/notify',
        '/weixin/valid1',
        '/formShow',
        '/admin/touser',
        '/admin/message',
        '/weixin/pay/notice',
        '/weixin/pay/payweixn',
        '/userreg',
        '/userlogin',
        '/curl/test',
        '/curl/*',
        '/ajax/mui',
        '/center',
        '/*'
    ];
}
