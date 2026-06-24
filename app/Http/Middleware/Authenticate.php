<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     * 
     * Этот метод определяет, куда перенаправлять пользователя, если он не авторизован
     * при попытке доступа к защищенным маршрутам.
     * 
     * @param Request $request Входящий HTTP запрос
     * @return string|null URL для перенаправления или null для JSON ответов
     */
    protected function redirectTo(Request $request): ?string
    {
        /**
         * Логика перенаправления:
         * - Если запрос ожидает JSON ответ (AJAX/API запросы) -> возвращаем null
         *   В этом случае Laravel автоматически вернет JSON ответ с ошибкой 401
         * - Если обычный HTTP запрос -> перенаправляем на страницу входа
         */
        return $request->expectsJson() ? null : route('login');
    }
}