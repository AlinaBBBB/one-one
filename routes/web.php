<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\QueryController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\CartController; 
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\ProfileController;

// ----------------------------------- домашняя страница -----------------------------------
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/home', [HomeController::class, 'index'])->name('home.old');

// ----------------------------------- аутентификация -----------------------------------
Route::get('/register', [UserController::class, 'create'])->name('register');
Route::post('/register', [UserController::class, 'store'])->name('register.store');
Route::get('/login', [UserController::class, 'loginform'])->name('login');
Route::post('/login', [UserController::class, 'login'])->name('login.auth');
Route::get('/logout', [UserController::class, 'logout'])->name('logout');
Route::get('/profile', [App\Http\Controllers\ProfileController::class, 'index'])->name('profile');

// ----------------------------------- заявки -----------------------------------
Route::get('/queries', [QueryController::class, 'index'])->name('queries');
Route::get('/newquery', [QueryController::class, 'show'])->name('newquery');
Route::post('/newquery', [QueryController::class, 'store'])->name('newquery.create');
Route::get('/queries/{query_id}', [QueryController::class, 'destroy'])->name('queries.destroy');
Route::post('/queries/reject/{query_id}', [QueryController::class, 'reject'])->name('queries.reject');
Route::post('/queries/aprove/{query_id}', [QueryController::class, 'aprove'])->name('queries.aprove');

// ----------------------------------- категории (маршруты каталога) -----------------------------------
Route::get('/category/{slug}', [CategoryController::class, 'show'])->name('category');



// ----------------------------------- товары -----------------------------------
Route::get('/products', [ProductController::class, 'catalog'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/category/{slug}', [ProductController::class, 'byCategory'])->name('products.category');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products/popular', [ProductController::class, 'popular'])->name('products.popular');

// ----------------------------------- корзина -----------------------------------
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('cart.index');
    Route::post('/add', [CartController::class, 'add'])->name('cart.add');
    Route::post('/update', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
    Route::delete('/clear', [CartController::class, 'clear'])->name('cart.clear');
    Route::get('/count', [CartController::class, 'getCartCount'])->name('cart.count');
    Route::get('/checkout', [CartController::class, 'checkout'])->name('cart.checkout');
});

// ============================================
// ЗАКАЗЫ (ПОЛЬЗОВАТЕЛЬСКАЯ ЧАСТЬ)
// ============================================

Route::middleware(['auth'])->group(function () {
    // Оформление заказа
    Route::get('/orders/create', [App\Http\Controllers\OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [App\Http\Controllers\OrderController::class, 'store'])->name('orders.store');
    
    // История заказов
    Route::get('/orders', [App\Http\Controllers\OrderController::class, 'history'])->name('orders.history');
    Route::get('/orders/{id}', [App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

// ============================================
// КАТАЛОГ ТОВАРОВ - ВСЕ МАРШРУТЫ
// ============================================

// Основная страница каталога с фильтрами
Route::get('/catalog', [ProductController::class, 'catalog'])->name('catalog');

// AJAX фильтрация для каталога (без перезагрузки страницы)
Route::get('/catalog/filter', [ProductController::class, 'filter'])->name('catalog.filter');

// Поиск товаров
Route::get('/catalog/search', [ProductController::class, 'search'])->name('catalog.search');

// Товары по категории
Route::get('/catalog/category/{slug}', [ProductController::class, 'byCategory'])->name('catalog.category');

// Отдельные страницы коллекций
Route::get('/catalog/popular', [ProductController::class, 'popular'])->name('catalog.popular');
Route::get('/catalog/sale', [ProductController::class, 'sale'])->name('catalog.sale');
Route::get('/catalog/new', [ProductController::class, 'new'])->name('catalog.new');

// ----------------------------------- статические страницы -----------------------------------
Route::get('/about', function () {
    return view('pages.about');
})->name('about');

Route::get('/contacts', function () {
    return view('pages.contacts');
})->name('contacts');

Route::get('/delivery', function () {
    return view('pages.delivery');
})->name('delivery');

Route::get('/returns', function () {
    return view('pages.returns');
})->name('returns');

// ============================================
// ИЗБРАННОЕ - ОБНОВЛЕННЫЕ МАРШРУТЫ
// ============================================

Route::middleware(['auth'])->group(function () {
    // Страница избранного
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    
    // Добавить в избранное (исправлено - без параметра в URL)
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    
    // Удалить из избранного (исправлено - без параметра в URL)
    Route::delete('/wishlist', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    
    // Переключение избранного (AJAX)
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    
    // Получить количество избранного
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    
    // Очистить всё избранное
    Route::delete('/wishlist/clear', [WishlistController::class, 'clear'])->name('wishlist.clear');
    
    // Удалить конкретный элемент из избранного (со страницы избранного)
    Route::delete('/wishlist/item/{wishlistItemId}', [WishlistController::class, 'remove'])->name('wishlist.remove');
});

// Старые маршруты для обратной совместимости (можно оставить временно)
Route::middleware(['auth'])->group(function () {
    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.add.old');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.remove.old');
});

// ============================================
// МАРШРУТЫ ДЛЯ ВАРИАЦИЙ ТОВАРОВ
// ============================================

// Маршрут для получения данных вариации (AJAX)
Route::post('/product/variant-data', [ProductController::class, 'getVariantData'])
    ->name('product.variant-data');

// Фильтрация товаров по цвету
Route::get('/products/by-color/{colorId}', [ProductController::class, 'byColor'])->name('products.by.color');

// Маршрут для товаров по цвету (альтернативный)
Route::get('/products/color/{colorId}', [ProductController::class, 'byColor'])->name('products.by.color');

// ============================================
// ЛОГИ (только для разработки)
// ============================================

Route::get('/view-logs', function() {
    $logFile = storage_path('logs/laravel.log');
    
    if (!file_exists($logFile)) {
        return 'Log file does not exist';
    }
    
    $logs = file_get_contents($logFile);
    
    // Выводим только последние 50 строк
    $lines = explode("\n", $logs);
    $lastLines = array_slice($lines, -50);
    
    echo "<pre>";
    echo implode("\n", $lastLines);
    echo "</pre>";
    
    return '';
})->middleware('auth'); // Защищаем доступ к логам


// ============================================
// АДМИН-ПАНЕЛЬ
// ============================================
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Дашборд
    Route::get('/', [App\Http\Controllers\Admin\AdminController::class, 'index'])->name('dashboard');
    
    // Товары
    Route::resource('products', App\Http\Controllers\Admin\ProductController::class);
    
    // Заказы
    Route::resource('orders', App\Http\Controllers\Admin\OrderController::class)->only(['index', 'show', 'update']);
    
    // Категории
    Route::resource('categories', App\Http\Controllers\Admin\CategoryController::class);
});