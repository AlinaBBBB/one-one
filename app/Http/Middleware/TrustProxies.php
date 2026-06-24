<?php

namespace App\Http\Middleware;

use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * Этот массив определяет IP-адреса прокси-серверов, которые находятся
     * между клиентом и вашим приложением (например: балансировщики нагрузки, CDN)
     * 
     * Варианты значений:
     * - null: не доверять никаким прокси
     * - '*': доверять всем прокси (ОПАСНО - только для разработки)
     * - массив IP: доверять только указанным IP-адресам
     * - строка CIDR: доверять диапазону IP (например: '192.168.1.0/24')
     *
     * @var array<int, string>|string|null
     */
    protected $proxies;

    /**
     * The headers that should be used to detect proxies.
     *
     * Определяет, какие заголовки HTTP используются для передачи информации
     * от прокси-серверов к приложению
     * 
     * Битовая маска, которая включает:
     * - X_FORWARDED_FOR: реальный IP клиента
     * - X_FORWARDED_HOST: оригинальный хост
     * - X_FORWARDED_PORT: оригинальный порт
     * - X_FORWARDED_PROTO: оригинальный протокол (http/https)
     * - X_FORWARDED_AWS_ELB: специфичные заголовки AWS Elastic Load Balancer
     *
     * @var int
     */
    protected $headers =
        Request::HEADER_X_FORWARDED_FOR |    // Клиентский IP: X-Forwarded-For
        Request::HEADER_X_FORWARDED_HOST |   // Оригинальный хост: X-Forwarded-Host
        Request::HEADER_X_FORWARDED_PORT |   // Оригинальный порт: X-Forwarded-Port
        Request::HEADER_X_FORWARDED_PROTO |  // Протокол: X-Forwarded-Proto
        Request::HEADER_X_FORWARDED_AWS_ELB; // AWS ELB заголовки
}