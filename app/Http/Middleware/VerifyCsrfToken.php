<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * Этот массив содержит URI (адреса), для которых отключается
     * проверка CSRF токена.
     *
     * CSRF (Cross-Site Request Forgery) - защита от межсайтовой подделки запросов
     * 
     * Когда отключать CSRF защиту?
     * - Внешние API, которые не используют сессии Laravel
     * - Webhook-и от сторонних сервисов
     * - Интеграции с платежными системами
     * - Любые endpoint-ы, которые не используют сессии браузера
     *
     * ВАЖНО: Будьте осторожны! Отключение CSRF может создать уязвимости
     *
     * @var array<int, string>
     */
    protected $except = [
        // Примеры URI, которые часто исключают:
        //
        // 'api/webhook/stripe',          // Webhook от Stripe
        // 'api/webhook/github',          // Webhook от GitHub
        // 'telegram/webhook',            // Webhook для Telegram бота
        // 'payment/callback',            // Callback от платежной системы
        // 'external-api/*',              // Все URL начинающиеся с external-api/
    ];
}