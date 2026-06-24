<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;

class AuthControllerTest extends TestCase
{
    /**
     * Тест доступности страницы логина.
     *
     * @return void
     */
    public function test_login_page_is_available()
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
        $response->assertViewIs('users.loginform');
    }

    /**
     * Тест доступности страницы регистрации.
     *
     * @return void
     */
    public function test_registration_page_is_available()
    {
        $response = $this->get('/register');

        $response->assertStatus(200);
        $response->assertViewIs('users.create');
    }

    /**
     * Тест успешной регистрации нового пользователя.
     *
     * @return void
     */
    public function test_user_can_register()
    {
        $userData = [
            'name'                  => 'Тестовый Пользователь',
            'email'                 => 'testuser_' . time() . '@example.com',
            'password'              => 'password123',
            'password_confirmation' => 'password123',
        ];

        $response = $this->post('/register', $userData);

        $response->assertRedirect('/');

        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
        ]);

        $this->assertAuthenticated();
    }

    /**
     * Тест успешного выхода из аккаунта.
     *
     * @return void
     */
    public function test_user_can_logout()
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        // Если POST /logout не работает, пробуем GET
        if ($response->status() === 405) {
            $response = $this->actingAs($user)->get('/logout');
        }

        $response->assertRedirect();

        $this->assertGuest();
    }
}