<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use DatabaseTransactions;

    
    public function test_register_page_is_available(): void
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    
    public function test_login_page_is_available(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertViewIs('users.loginform');
    }

    
    public function test_user_can_register(): void
    {
        $email = 'test_' . time() . '@example.com';
        $response = $this->post('/register', [
            'name' => 'Тестовый Пользователь',
            'email' => $email,
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $this->assertDatabaseHas('users', ['email' => $email]);
        $response->assertStatus(302); // 302 = редирект
    }

    /**
     * Тест: пользователь может войти
     */
    public function test_user_can_login(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'password',
        ]);

        // Проверяем, что редирект на /home (как в вашем контроллере)
        $response->assertRedirect('/home');
    }

    /**
     * Тест: неверный пароль возвращает ошибку
     */
    public function test_login_with_wrong_password_fails(): void
    {
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrong_password_123',
        ]);

        // Проверяем, что статус 302 (редирект обратно)
        $response->assertStatus(302);
    }
}