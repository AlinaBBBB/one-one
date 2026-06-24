<?php

namespace App\Http\Middleware;

use Illuminate\Cookie\Middleware\EncryptCookies as Middleware;

class EncryptCookies extends Middleware
{
    /**
     * The names of the cookies that should not be encrypted.
     *
     * Имена cookies, которые НЕ должны шифроваться.
     * Эти cookies будут передаваться в открытом виде.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}