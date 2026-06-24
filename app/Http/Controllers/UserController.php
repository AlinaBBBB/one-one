<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    // ----------------------------------- index -----------------------------------
    public function index()
    {
        /**
         * Показывает страницу профиля пользователя
         * Для администратора отображает все заявки, для обычного пользователя - только свои
         */
        
        // Проверяем, что пользователь авторизован
        if (!auth()->check()) {
            return redirect()->route('login');
        }
    
        // Безопасное получение заявок с проверкой
        $user = auth()->user();
        
        // Для администратора показываем все заказы, для клиента - только свои
        if ($user->role == 1) {
            // Администратор: получаем все заявки с информацией о пользователях
            $queries = \App\Models\Query::with('user')->latest()->get();
        } else {
            // Обычный пользователь: получаем только свои заявки
            $queries = $user->queries()->latest()->get();
        }
    
        return view('users.profile', [
            'title' => 'Профиль', 
            'queries' => $queries
        ]);
    }

    // ----------------------------------- create -----------------------------------
    public function create()
    {
        /**
         * Показывает форму регистрации нового пользователя
         */
        return view('users.create', ['title' => "Создание пользователя"]);
    }

    // ----------------------------------- store -----------------------------------
    public function store(Request $request)
    {
        /**
         * Создание нового пользователя (регистрация)
         * Валидация входных данных и создание учетной записи
         */
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],          // Имя обязательно, строка до 255 символов
            'email' => ['required', 'email', 'unique:users'],     // Email обязателен, должен быть уникальным
            'password' => ['required', 'confirmed', 'min:3', 'max:15'], // Пароль с подтверждением
        ]);

        // Создание пользователя
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password), // Хеширование пароля
            'role' => $request->role ?? 0, // Роль по умолчанию 0 (обычный пользователь)
        ]);

        // Авторизуем пользователя после регистрации
        Auth::login($user);

        return redirect()->route('home')->with('success', 'Регистрация прошла успешно!');
    }

    // ----------------------------------- update -----------------------------------
    public function update(Request $request, $queryId)
    {
        /** 
         * Метод для обновления заявки (заглушка)
         * Содержит проверки прав доступа, но не реализует логику обновления
         */
        
        // Проверка авторизации
        if (!auth()->check()) {
            return redirect()->route('login')->withErrors(['auth' => 'Пожалуйста, войдите в систему']);
        }

        // Проверка прав администратора
        if (auth()->user()->role != 1) {
            return redirect()->route('home')->withErrors(['access' => 'У вас нет прав для выполнения этого действия.']);
        }
        
        // Логика обновления заявки отсутствует - метод не завершен
    }

    // ----------------------------------- loginform -----------------------------------
    public function loginform() {
        /**
         * Показывает форму входа в систему
         */
        return view('users.loginform', ['title' => "Вход"]);
    }

    // ----------------------------------- login -----------------------------------
    public function login(Request $request) {
        /**
         * Обработка входа пользователя в систему
         * Проверяет credentials и авторизует пользователя
         */
        $credentials = $request->validate([
            'email' => ['required', 'email'],     // Email обязателен и должен быть валидным
            'password' => ['required'],           // Пароль обязателен
        ]);

        // Попытка авторизации
        if (Auth::attempt($credentials)) {
            // Регенерация сессии для защиты от fixation attacks
            $request->session()->regenerate();

            // Перенаправление на intended URL или на главную
            return redirect()->intended('home');
        }

        // Если авторизация не удалась - возврат с ошибкой
        return back()->withErrors([
            'email' => 'Введенные данные не соответствуют.',
        ])->onlyInput('email');
    }

    // ----------------------------------- logout -----------------------------------
    public function logout() {
        /**
         * Выход пользователя из системы
         * Завершает сессию и перенаправляет на страницу входа
         */
        Auth::logout();
        return redirect()->route('login');
    }
}