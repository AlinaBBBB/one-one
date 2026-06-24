<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // HasFactory - для создания фабрик и сидеров
    // Notifiable - для отправки уведомлений (email, SMS и т.д.)
    use HasFactory, Notifiable;

    /**
     * Поля, разрешенные для массового заполнения
     * 
     * При создании или обновлении пользователя через create()/update()
     * можно заполнять только эти поля
     */
    protected $fillable = [
        'name',          // Имя пользователя
        'email',         // Email (используется для входа)
        'password',      // Пароль (будет автоматически хешироваться)
        'phone',         // Телефон
        'address',       // Адрес
        'birth_date',    // Дата рождения
        'avatar',        // Аватар (путь к файлу)
        'role'           // Роль пользователя (например: 0 - пользователь, 1 - администратор)
    ];

    /**
     * Поля, которые должны быть скрыты при сериализации
     * 
     * Эти поля не будут видны при преобразовании модели в массив или JSON
     * Важно для безопасности - не exposing чувствительные данные
     */
    protected $hidden = [
        'password',              // Хеш пароля
        'remember_token',        // Токен для "запомнить меня"
    ];

    /**
     * Преобразование типов данных атрибутов
     * 
     * Laravel автоматически преобразует данные при получении/сохранении
     * 
     * @return array
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',  // Преобразование в объект Carbon
            'password' => 'hashed',             // Автоматическое хеширование пароля
        ];
    }

    /**
     * Связь с запросами (queries)
     * 
     * Один пользователь может иметь много запросов
     * По умолчанию ищет user_id в таблице queries
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function queries()
    {
        return $this->hasMany(Query::class);
    }

    /**
     * Связь с элементами корзины
     * 
     * Один пользователь может иметь много элементов в корзине
     * Второй параметр - внешний ключ в таблице cart_items
     * 
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'user_id');
    }

    /**
     * Проверка, является ли пользователь администратором
     * 
     * Предполагается, что role = 1 для администраторов
     * 
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 1;
    }

    // ⚡ ДОПОЛНИТЕЛЬНЫЕ ПОЛЕЗНЫЕ МЕТОДЫ:

    /**
     * Геттер для URL аватара
     * 
     * @return string|null
     */
    public function getAvatarUrlAttribute()
    {
        if (!$this->avatar) {
            return null;
        }
        
        // Если avatar уже содержит полный URL, возвращаем как есть
        if (filter_var($this->avatar, FILTER_VALIDATE_URL)) {
            return $this->avatar;
        }
        
        // Иначе генерируем URL из пути хранения
        return asset('storage/' . $this->avatar);
    }

    /**
     * Получить корзину пользователя
     * 
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getCart()
    {
        return $this->cartItems()->with('product')->get();
    }

    /**
     * Получить общую стоимость корзины
     * 
     * @return float
     */
    public function getCartTotalAttribute()
    {
        return $this->cartItems->sum(function ($cartItem) {
            return $cartItem->total_price;
        });
    }

    /**
     * Получить форматированную общую стоимость корзины
     * 
     * @return string
     */
    public function getCartTotalFormattedAttribute()
    {
        return number_format($this->cart_total, 0, ',', ' ') . '₽';
    }

    /**
     * Проверка, верифицирован ли email пользователя
     * 
     * @return bool
     */
    public function isEmailVerified()
    {
        return $this->email_verified_at !== null;
    }

    /**
     * Scope для администраторов
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAdmins($query)
    {
        return $query->where('role', 1);
    }

    /**
     * Scope для обычных пользователей
     * 
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRegularUsers($query)
    {
        return $query->where('role', 0);
    }
    public function wishlistItems()
    {
        return $this->hasMany(WishlistItem::class, 'user_id', 'id');
    }

    public function wishlistProducts()
    {
        return $this->belongsToMany(Product::class, 'wishlist_items', 'user_id', 'product_id')
                    ->withTimestamps()
                    ->orderBy('wishlist_items.created_at', 'desc');
    }
}