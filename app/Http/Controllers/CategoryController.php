<?php
//  категории
namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    // ----------------------------------- конструктор с проверкой на роль администратора -----------------------------------
    public function __construct()
    {
        /**
         * Применяем middleware 'admin' только к указанным методам
         * Это гарантирует, что только администраторы могут:
         * - создавать категории (create)
         * - сохранять категории (store) 
         * - просматривать список категорий (index)
         */
        $this->middleware('admin')->only(['create', 'store', 'index']);
    }

    // ----------------------------------- index -----------------------------------
    public function index()
    {
        /**
         * Отображение списка всех категорий
         * Получаем ВСЕ категории из базы данных
         * Возвращаем представление categories.index с передачей данных категорий
         */
        $categories = Category::all(); // Получаем все категории
        return view('categories.index', ['categories' => $categories]); // Возвращаем представление с данными категорий
    }

    // ----------------------------------- create -----------------------------------
    public function create()
    {
        /**
         * Отображение формы для создания новой категории
         * Возвращает представление с формой создания
         * Передает заголовок страницы для использования в шаблоне
         */
        return view('categories.create', ['title' => 'Создать категорию']);
    }

    // ----------------------------------- store -----------------------------------
    public function store(Request $request)
    {
        /**
         * Сохранение новой категории в базу данных
         * Валидация входных данных:
         * - name: обязательное поле, строка, максимум 255 символов
         */
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);
        
        /**
         * Создание новой категории в базе данных
         * Использует validated данные для безопасности
         */
        Category::create([
            'name' => $validated['name'], // Сохраняем новую категорию
        ]);
        
        /**
         * Перенаправление на страницу списка категорий
         * with('success') - передает flash-сообщение об успехе
         * которое можно отобразить в шаблоне
         */
        return redirect()->route('categories.index')->with('success', 'Категория успешно создана!');
    }

    public function show($slug)
{
    /**
     * Отображение товаров конкретной категории по её slug
     */
    
    // Пробуем найти категорию по slug с подкатегориями
    $category = Category::with(['children' => function($query) {
        $query->withCount('products');
    }])
    ->where('slug', $slug)
    ->withCount('products')
    ->first();
    
    // Если не нашли по slug, пробуем найти по ID
    if (!$category) {
        $category = Category::with(['children' => function($query) {
            $query->withCount('products');
        }])
        ->withCount('products')
        ->find($slug);
    }
    
    // Если категория не найдена - 404
    if (!$category) {
        abort(404, 'Категория не найдена');
    }
    
    // Получаем все категории для фильтра
    $categories = Category::with(['children' => function($query) {
        $query->withCount('products');
    }])
    ->whereNull('parent_id')
    ->withCount('products')
    ->get();
    
    // Получаем товары этой категории с пагинацией
    $products = $category->products()->with(['category', 'images'])->paginate(12);
    
    /**
     * Возвращаем представление category.show
     */
    return view('catalog.show', [
        'category' => $category,
        'products' => $products,
        'categories' => $categories,
        'title' => $category->name . ' | ONEONE'
    ]);
}
}