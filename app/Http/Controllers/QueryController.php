<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Query;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class QueryController extends Controller
{
    // ----------------------------------- конструктор с проверкой на роль администратора -----------------------------------
    // public function __construct()
    // {
    //     $this->middleware('admin')->only(['show', 'store', 'index']);
    // }
    // Закомментированный middleware - вероятно, для временного отключения проверки прав доступа

    // ----------------------------------- index -----------------------------------
    public function index()
    {
        /**
         * Показывает список заявок текущего пользователя
         * Фильтрует заявки по ID текущего авторизованного пользователя
         * Сортирует по дате создания (новые сверху)
         */
        $queries = Query::where('user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return view('queries', ['title' => 'Заявки', 'queries' => $queries]);
    }

    // ----------------------------------- store -----------------------------------
    public function store(Request $request)
    {
        /**
         * Создание новой заявки
         * Валидация загружаемых файлов - только изображения
         */
        $validate = $request->validate([
            'photo_before' => ['image'], // Проверка, что файл является изображением
            'photo_after' => ['image'],
        ]);

        /**
         * Обработка загрузки фото "до"
         * Обязательное поле - если файл не загружен, возвращает ошибку 403
         */
        if ($request->hasFile('photo_before')) {
            $photo_before_path = $request->file('photo_before')->store('images');
        } elseif (!$request->hasFile('photo_before')) {
            abort(403); // Запрещено - фото "до" обязательно
        }

        /**
         * Создание новой заявки в базе данных
         */
        Query::create([
            'category_id' => $request->category_id,    // ID категории из формы
            'description' => $request->description,    // Описание проблемы
            'photo_before' => $photo_before_path,      // Путь к фото "до"
            'user_id' => auth()->user()->id,           // ID текущего пользователя
            'title' => $request->title,                // Заголовок заявки
            'status' => $request->status ?? 'Новая',   // Статус (по умолчанию "Новая")
        ]);

        // Перенаправление в профиль после создания
        return redirect('profile');
    }

    // ----------------------------------- show -----------------------------------
    public function show()
    {
        /**
         * Показывает форму создания новой заявки
         * Получает все категории для выпадающего списка
         */
        $categories = Category::all();
        return view('newquery', ['title' => 'Создание заявки', 'categories' => $categories]);
    }

    // ----------------------------------- destroy -----------------------------------
    public function destroy($query_id)
    {
        /**
         * Удаление заявки по ID
         * Удаляет заявку из базы данных
         */
        Query::where('query_id', $query_id)->delete();
        return redirect('profile');
    }

    // ----------------------------------- reject -----------------------------------
    public function reject(Request $request, $query_id)
    {
        /**
         * Отклонение заявки администратором
         * Обновляет статус на "Отклонено" и добавляет комментарий
         */
        Query::where('query_id', $query_id)->update([
            'status' => 'Отклонено', 
            'comment' => $request->comment // Комментарий с причиной отклонения
        ]);
        return redirect('/home');
    }

    // ----------------------------------- aprove -----------------------------------
    public function aprove($query_id)
    {
        /**
         * Подтверждение/принятие заявки в работу
         * Меняет статус на "В процессе"
         * Опечатка в названии метода - должно быть "approve"
         */
        Query::where('query_id', $query_id)->update(['status' => 'В процессе']);
        return redirect('/home');
    }
}