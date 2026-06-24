<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    /**
     * Middleware для проверки прав администратора
     * 
     * Проверяет, что текущий авторизованный пользователь имеет роль администратора (role = 1)
     * Если нет - перенаправляет на главную страницу с сообщением об ошибке
     *
     * @param Request $request Входящий HTTP запрос
     * @param Closure $next Следующий middleware/обработчик в цепочке
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        /**
         * Проверка условий:
         * 1. Пользователь авторизован (auth()->user() не null)
         * 2. Роль пользователя не равна 1 (не администратор)
         */
        if (auth()->user() && auth()->user()->role != 1) {
            // Перенаправление на главную страницу с сообщением об ошибке
            return redirect('home')->withErrors(['access' => 'У вас нет прав для выполнения этого действия.']);
        }

        /**
         * Если проверка пройдена - передаем запрос дальше по цепочке middleware
         * $next($request) передает управление следующему middleware или контроллеру
         */
        return $next($request);
    }
}