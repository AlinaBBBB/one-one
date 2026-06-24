<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 
        'slug', 
        'description', 
        'parent_id', 
        'image', 
        'is_featured'
    ];

    // Родительская категория
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Дочерние категории (подкатегории) - ЭТОГО ОТНОШЕНИЯ НЕ БЫЛО!
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Товары в этой категории
    public function products()
    {
        return $this->hasMany(Product::class, 'category_id');
    }

    /**
     * Автоматическое создание slug при сохранении
     */
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
        
        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->name);
            }
        });
    }
    
    /**
     * Получить URL категории
     */
    public function getUrlAttribute()
    {
        return route('category.show', ['slug' => $this->slug]);
    }
    
    /**
     * Проверка, есть ли подкатегории
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }
    
    /**
     * Получить количество товаров
     */
    public function getProductsCountAttribute()
    {
        return $this->products()->count();
    }

    /**
 * Получить все активные категории с товарами
 */
public static function getActiveWithProducts()
{
    return self::with(['children' => function($query) {
            $query->withCount('products')
                  ->having('products_count', '>', 0);
        }])
        ->whereNull('parent_id')
        ->withCount('products')
        ->having('products_count', '>', 0)
        ->get();
}

/**
 * Получить дерево категорий для фильтра
 */
public static function getFilterTree()
{
    return self::with(['children' => function($query) {
            $query->select('id', 'parent_id', 'name', 'slug')
                  ->withCount('products');
        }])
        ->whereNull('parent_id')
        ->select('id', 'name', 'slug')
        ->withCount('products')
        ->get();
}

}