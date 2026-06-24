<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = [
        'product_id',
        'image_path',
        'image_url',
        'is_main',
        'sort_order',
        'alt_text'
    ];
    
    protected $appends = ['url'];
    
    /**
     * Связь с товаром
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    
    /**
     * Accessor для получения URL изображения (УПРОЩЕННАЯ ВЕРСИЯ)
     */
    public function getUrlAttribute()
    {
        // Если есть поле image_url, используем его
        if ($this->image_url) {
            return asset($this->image_url);
        }
        
        // Если нет image_url, но есть image_path
        if ($this->image_path) {
            // Проверяем, не является ли это уже полным URL
            if (filter_var($this->image_path, FILTER_VALIDATE_URL)) {
                return $this->image_path;
            }
            
            // Если начинается с 'storage/', используем Storage
            if (str_starts_with($this->image_path, 'storage/')) {
                return Storage::url($this->image_path);
            }
            
            // Если начинается с 'images/', используем asset
            if (str_starts_with($this->image_path, 'images/')) {
                return asset($this->image_path);
            }
            
            // Для других относительных путей
            return asset('storage/' . $this->image_path);
        }
        
        // Если ничего нет
        return asset('images/product-placeholder.jpg');
    }
    
    /**
     * Scope для главного изображения
     */
    public function scopeMain($query)
    {
        return $query->where('is_main', true);
    }
    
    /**
     * Scope для сортировки
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('is_main', 'desc')
                     ->orderBy('sort_order')
                     ->orderBy('id');
    }
    
    /**
     * Проверяет, существует ли файл изображения
     */
    public function fileExists()
    {
        if (!$this->image_path) return false;
        
        // Если это локальный файл
        if (str_starts_with($this->image_path, '/') || 
            preg_match('/^[A-Za-z]:\\\\/', $this->image_path)) {
            return file_exists($this->image_path);
        }
        
        // Если это относительный путь в public
        $publicPath = public_path($this->image_path);
        return file_exists($publicPath);
    }
}