<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    protected $fillable = [
        'product_id', 
        'color_id', 
        'sku', 
        'price', 
        'old_price',
        'stock',
        'image',
        'is_active'
    ];
    
    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'stock' => 'integer',
        'is_active' => 'boolean'
    ];
    
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    public function color()
    {
        return $this->belongsTo(Color::class);
    }
    
    // ИСПРАВЛЯЕМ: используем таблицу product_sizes вместо product_variant_sizes
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')
                    ->withPivot('stock')
                    ->wherePivot('product_id', $this->product_id);
    }
    
    // Геттер для изображения вариации
    public function getImageUrlAttribute()
    {
        if ($this->image) {
            return asset($this->image);
        }
        
        // Если нет своего изображения, берем у товара
        return $this->product->image_url ?? asset('images/product-placeholder.jpg');
    }
    
    // Проверка наличия
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }
}