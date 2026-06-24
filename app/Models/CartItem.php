<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * 
     * Эти поля можно заполнять массово через create() или fill()
     * Защита от массового присвоения (mass assignment)
     *
     * @var array<string>
     */
    protected $fillable = [
        'user_id',      // ID пользователя-владельца корзины
        'product_id', // ID товара в корзине
        'variant_id', // ДОБАВЛЯЕМ
        'color_id',   // ДОБАВЛЯЕМ
        'size',       // ДОБАВЛЯЕМ  
        'quantity'      // Количество товара
    ];

    /**
     * Связь с пользователем
     * 
     * Один элемент корзины принадлежит одному пользователю
     * Обратное отношение: у пользователя много элементов корзины
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */



        // Связь с вариацией
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }
    
    // Связь с цветом
    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Связь с товаром
     * 
     * Один элемент корзины принадлежит одному товару
     * Через эту связь можно получить информацию о товаре
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Геттер для общей стоимости позиции
     * 
     * Вычисляет общую стоимость: цена товара × количество
     * Используется как свойство: $cartItem->total_price
     * Автоматически использует discounted_price из модели Product
     *
     * @return float
     */
    public function getTotalPriceAttribute()
    {

          // Используем цену вариации, если есть
        if ($this->variant && $this->variant->price) {
            return $this->variant->price * $this->quantity;
        }

        return $this->product->discounted_price * $this->quantity;
    }

    // Получаем выбранный цвет
    public function getSelectedColorAttribute()
    {
        if ($this->color) {
            return $this->color;
        }
        
        if ($this->variant && $this->variant->color) {
            return $this->variant->color;
        }
        
        return null;
    }

    /**
     * Форматированная общая стоимость
     * 
     * Возвращает стоимость в красивом формате с валютой
     * Используется как свойство: $cartItem->total_price_formatted
     * Пример: "1 500₽", "25 000₽"
     *
     * @return string
     */
    public function getTotalPriceFormattedAttribute()
    {
        return number_format($this->total_price, 0, ',', ' ') . '₽';
    }

    /**
     * Увеличение количества товара в корзине
     *
     * @param int $amount На сколько увеличить количество (по умолчанию 1)
     * @return bool Успешность сохранения
     */
    public function incrementQuantity($amount = 1)
    {
        $this->quantity += $amount;
        return $this->save();
    }

    /**
     * Уменьшение количества товара в корзине
     * 
     * Количество не может быть меньше 1
     *
     * @param int $amount На сколько уменьшить количество (по умолчанию 1)
     * @return bool Успешность сохранения
     */
    public function decrementQuantity($amount = 1)
    {
        $this->quantity = max(1, $this->quantity - $amount);
        return $this->save();
    }

    // ⚡ ДОПОЛНИТЕЛЬНЫЕ ПОЛЕЗНЫЕ МЕТОДЫ:

    /**
     * Проверка, доступен ли товар в нужном количестве
     *
     * @return bool
     */
    public function isAvailable()
    {
        if ($this->variant) {
            return $this->variant->stock >= $this->quantity;
        }

        return $this->product->stock >= $this->quantity;
    }

    /**
     * Получить максимально доступное количество для этого товара
     *
     * @return int
     */
    public function getMaxAvailableQuantity()
    {
        return min($this->product->stock, 100); // Ограничение 100 штук
    }

    /**
     * Обновить количество с проверкой доступности
     *
     * @param int $newQuantity
     * @return bool
     */
    public function updateQuantitySafely($newQuantity)
    {
        $maxAvailable = $this->getMaxAvailableQuantity();
        $this->quantity = min(max(1, $newQuantity), $maxAvailable);
        return $this->save();
    }

}