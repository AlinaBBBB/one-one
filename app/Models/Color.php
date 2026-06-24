<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    protected $fillable = ['name', 'hex_code'];
    
    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_colors', 'color_id', 'product_id');
    }
    
    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'color_id');
    }

    /**
 * Получить цвета, которые есть в наличии у товаров
 */
public static function getAvailableColors()
{
    return self::whereHas('products', function($query) {
            $query->mainProducts()->inStock();
        })
        ->orWhereHas('variants', function($query) {
            $query->whereHas('product', function($q) {
                $q->mainProducts();
            })
            ->where('stock', '>', 0);
        })
        ->orderBy('name')
        ->get();
}

/**
 * Получить количество товаров для каждого цвета
 */
public function getProductsCountAttribute()
{
    $mainProductsCount = Product::mainProducts()
        ->where('main_color_id', $this->id)
        ->count();
        
    $variantProductsCount = Product::mainProducts()
        ->whereHas('childVariants', function($query) {
            $query->where('main_color_id', $this->id);
        })
        ->count();
        
    return $mainProductsCount + $variantProductsCount;
}
}