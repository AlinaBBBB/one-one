<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    /**
     * Название таблицы
     */
    protected $table = 'product_reviews';

    /**
     * Поля, которые можно массово присваивать
     */
    protected $fillable = [
        'product_id',
        'user_id', 
        'rating',
        'comment',
        'is_approved'
    ];

    /**
     * Типы данных
     */
    protected $casts = [
        'rating' => 'integer',
        'is_approved' => 'boolean'
    ];

    /**
     * Связь с товаром
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Связь с пользователем
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Scope для одобренных отзывов
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Геттер для имени пользователя (если пользователь удален)
     */
    public function getUserNameAttribute()
    {
        return $this->user ? $this->user->name : 'Аноним';
    }
}