<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
 * Главная страница магазина ONEONE
 * Отображает карусель, категории, популярные товары и новинки
 */
public function index()
{
    // Получаем все категории с подкатегориями и количеством товаров
    $categories = Category::with(['children' => function($query) {
        $query->withCount('products');
    }])
    ->whereNull('parent_id')
    ->withCount('products')
    ->get();
    
    // Популярные товары (is_popular = true или is_bestseller = true)
    $featuredProducts = Product::with(['category', 'images'])
        ->where(function($query) {
            $query->where('is_popular', true)
                  ->orWhere('is_bestseller', true);
        })
        ->where('stock', '>', 0) // Только товары в наличии
        ->orderBy('created_at', 'desc')
        ->take(8) // Ограничение 8 товаров
        ->get();

    // Новинки (is_new = true)
    $newProducts = Product::with(['category', 'images'])
        ->where('is_new', true)
        ->where('stock', '>', 0)
        ->orderBy('created_at', 'desc')
        ->take(8)
        ->get();

    /**
     * Возвращаем представление home с передачей данных
     */
    return view('home', compact('categories', 'featuredProducts', 'newProducts'));
}
}