<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    protected $fillable = ['name', 'category', 'sort_order'];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_sizes', 'size_id', 'product_id');
    }

    /**
     * Получить размеры, которые есть в наличии
     */
    public static function getAvailableSizes()
    {
        return self::whereHas('products', function($query) {
                $query->mainProducts()
                      ->where('product_sizes.stock', '>', 0);
            })
            ->select('name')  // 🔥 Добавить select distinct
            ->distinct()      // 🔥 Добавить distinct
            ->orderBy('name')
            ->get();
    }

    /**
     * Получить размеры для категории одежды (без дубликатов)
     */
    public static function getClothingSizes()
    {
        return self::where('category', 'clothing')
            ->select('name')     // 🔥 Только поле name
            ->distinct()         // 🔥 Убираем дубликаты
            ->orderBy('name')
            ->get();
    }

  /**
 * Получить все уникальные размеры (без дубликатов)
 */
public static function getUniqueSizes()
{
    return self::where('category', 'clothing')
        ->select('name')        // 🔥 Только name, без id
        ->distinct()            // 🔥 Используем distinct вместо groupBy
        ->orderBy('name')
        ->get();
}

    /**
     * 🔥 НОВЫЙ МЕТОД: Получить размеры как простой массив строк
     */
    public static function getSizeNames()
    {
        return self::where('category', 'clothing')
            ->distinct()
            ->pluck('name')
            ->toArray();
    }
}