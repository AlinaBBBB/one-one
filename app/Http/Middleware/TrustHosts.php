<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustHosts as Middleware;

class TrustHosts extends Middleware
{
    /**
     * Get the host patterns that should be trusted.
     * 
     * Этот метод определяет, какие хосты (домены) должны считаться доверенными
     * Доверенные хосты важны для безопасности, особенно при работе с:
     * - HTTPS редиректами
     * - Сессиями
     * - CSRF защитой
     *
     * @return array<int, string|null>
     */
    public function hosts(): array
    {
        return [
            // Доверяем всем поддоменам основного URL приложения
            // Например, если основной URL: https://example.com
            // Будут доверены: admin.example.com, api.example.com, www.example.com и т.д.
            $this->allSubdomainsOfApplicationUrl(),
            
            // Можно добавить дополнительные доверенные хосты:
            // 'my-trusted-domain.com',
            // 'api.my-app.com',
            // 'localhost', // для разработки
        ];
    }
}