<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\PreventRequestsDuringMaintenance as Middleware;

class PreventRequestsDuringMaintenance extends Middleware
{
    /**
     * The URIs that should be reachable while maintenance mode is enabled.
     *
     * URI-адреса, которые должны оставаться доступными при включенном режиме обслуживания.
     * Эти маршруты будут работать нормально, даже когда сайт находится в режиме техобслуживания.
     *
     * @var array<int, string>
     */
    protected $except = [
        //
    ];
}