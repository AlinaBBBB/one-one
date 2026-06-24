<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ValidateSignature as Middleware;

class ValidateSignature extends Middleware
{
    /**
     * The names of the query string parameters that should be ignored.
     *
     * Этот массив содержит имена параметров query string, которые должны быть
     * проигнорированы при проверке подписи URL.
     *
     * Зачем это нужно?
     * - Параметры трекинга (UTM, fbclid) часто добавляются автоматически
     * - Они не должны влиять на валидность подписи URL
     * - Без этого подписанные URL ломались бы при добавлении tracking параметров
     *
     * @var array<int, string>
     */
    protected $except = [
        // 'fbclid',          // Facebook Click Identifier - параметр отслеживания кликов из Facebook
        // 'utm_campaign',    // UTM параметр: название кампании
        // 'utm_content',     // UTM параметр: содержание (A/B тестирование)
        // 'utm_medium',      // UTM параметр: канал (email, social, cpc и т.д.)
        // 'utm_source',      // UTM параметр: источник (google, facebook, newsletter)
        // 'utm_term',        // UTM параметр: ключевое слово
    ];
}