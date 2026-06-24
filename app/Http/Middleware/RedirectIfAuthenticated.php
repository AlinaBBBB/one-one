<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * Обрабатывает входящий запрос и перенаправляет уже авторизованных пользователей.
     * Например, если пользователь уже вошел в систему, он будет перенаправлен 
     * с страниц логина/регистрации на главную страницу.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$guards): Response
    {
        /**
         * Определяем guards для проверки
         * Если guards не указаны, используем [null] (default guard)
         */
        $guards = empty($guards) ? [null] : $guards;

        /**
         * Проверяем каждый указанный guard
         * Если пользователь авторизован в любом из guards - перенаправляем на HOME
         */
        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                return redirect(RouteServiceProvider::HOME);
            }
        }

        /**
         * Если пользователь не авторизован ни в одном guard - пропускаем запрос дальше
         */
        return $next($request);
    }
}