<?php

namespace App\Http\Controllers;

use App\Models\Subscriber;
use App\Mail\WelcomeSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class SubscriberController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:subscribers,email'
        ], [
            'email.required' => 'Введите email',
            'email.email' => 'Некорректный email',
            'email.unique' => 'Вы уже подписаны!'
        ]);

        $subscriber = Subscriber::create([
            'email' => $request->email,
            'is_active' => true
        ]);

        // 🔥 Отправляем приветственное письмо
        try {
            Mail::to($subscriber->email)->send(new WelcomeSubscriber($subscriber));
        } catch (\Exception $e) {
            \Log::error('Ошибка отправки письма: ' . $e->getMessage());
        }

        return response()->json([
            'success' => true,
            'message' => 'Спасибо за подписку! Проверьте почту.'
        ]);
    }
}