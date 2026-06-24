<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    const AVAILABILITY_IN_STOCK = 'in_stock';
    const AVAILABILITY_ON_ORDER = 'on_order';
    const AVAILABILITY_OUT_OF_STOCK = 'out_of_stock';

    protected $primaryKey = 'id';
    
    protected $fillable = [
        'title',
        'description',
        'short_description',
        'price',
        'old_price',
        'image',
        'material',
        'height',
        'discount',
        'is_new',
        'is_popular',
        'is_bestseller',
        'is_on_sale',
        'category_id',
        'product_type',
        'color',
        'size',
        'stock',
        'sku',
        'parent_product_id',
        'is_variant',
        'main_color_id',
        'availability',        
        'avg_rating',           
        'reviews_count'      
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'old_price' => 'decimal:2',
        'is_new' => 'boolean',
        'is_popular' => 'boolean',
        'is_bestseller' => 'boolean',
        'is_on_sale' => 'boolean',
        'discount' => 'integer',
        'stock' => 'integer',
        'height' => 'integer',
        'parent_product_id' => 'integer',
        'is_variant' => 'boolean',
        'main_color_id' => 'integer',
        'avg_rating' => 'float',
        'reviews_count' => 'integer'
    ];

    // ============== СВЯЗИ ==============
    
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('sort_order')->orderBy('id');
    }

    public function mainImage()
    {
        return $this->hasOne(ProductImage::class, 'product_id')->where('is_main', true);
    }

    public function reviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id')
                    ->where('is_approved', true)
                    ->with('user')
                    ->latest();
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    public function wishlistedBy()
    {
        return $this->belongsToMany(User::class, 'wishlist_items', 'product_id', 'user_id');
    }
    
    public function colors()
    {
        return $this->belongsToMany(Color::class, 'product_colors', 'product_id', 'color_id');
    }
    
    public function mainColor()
    {
        return $this->belongsTo(Color::class, 'main_color_id');
    }
    
    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_sizes', 'product_id', 'size_id')
                    ->withPivot('stock');
    }
    
    public function parentProduct()
    {
        return $this->belongsTo(Product::class, 'parent_product_id');
    }
    
    public function childVariants()
    {
        return $this->hasMany(Product::class, 'parent_product_id')->where('is_variant', true);
    }

    // ============== ACCESSORS ==============
    
    /**
     * Accessor для URL главного изображения
     */
    public function getMainImageUrlAttribute()
    {
        // 1. Проверяем связанное главное изображение
        if ($this->relationLoaded('mainImage') && $this->mainImage) {
            return $this->formatImageUrl($this->mainImage->url ?? $this->mainImage->image_path);
        }
        
        // 2. Проверяем загруженные изображения
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $mainImage = $this->images->where('is_main', true)->first();
            if ($mainImage) {
                return $this->formatImageUrl($mainImage->url ?? $mainImage->image_path);
            }
            $firstImage = $this->images->first();
            return $this->formatImageUrl($firstImage->url ?? $firstImage->image_path);
        }
        
        // 3. Загружаем главное изображение из БД
        $mainImage = $this->mainImage()->first();
        if ($mainImage) {
            return $this->formatImageUrl($mainImage->url ?? $mainImage->image_path);
        }
        
        // 4. Используем основное поле image
        return $this->getImageUrlAttribute();
    }

    /**
     * Accessor для URL изображения
     */
    public function getImageUrlAttribute()
    {
        // 1. Проверяем загруженные изображения
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            $firstImage = $this->images->first();
            return $this->formatImageUrl($firstImage->url ?? $firstImage->image_path);
        }
        
        // 2. Проверяем поле image
        if (!empty($this->image)) {
            return $this->formatImageUrl($this->image);
        }
        
        // 3. Пытаемся загрузить первое изображение из БД
        if ($this->images()->exists()) {
            $firstImage = $this->images()->first();
            if ($firstImage && ($firstImage->url || $firstImage->image_path)) {
                return $this->formatImageUrl($firstImage->url ?? $firstImage->image_path);
            }
        }
        
        return null;
    }

    /**
     * Форматирование URL изображения
     */
    /**
 * Форматирование URL изображения
 */
protected function formatImageUrl($url)
{
    if (empty($url)) {
        return null;
    }
    
    // Если это уже полный URL (начинается с http:// или https://)
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    
    // Убираем возможный начальный слеш
    $url = ltrim($url, '/');
    
    // 🔥 ГЛАВНОЕ ИСПРАВЛЕНИЕ: 
    // Если путь начинается с "images/" - это публичная папка, НЕ добавляем storage/
    if (str_starts_with($url, 'images/')) {
        return asset($url);
    }
    
    // Если путь начинается с "products/" или других папок внутри storage
    if (str_starts_with($url, 'products/') || str_starts_with($url, 'uploads/')) {
        return asset('storage/' . $url);
    }
    
    // По умолчанию возвращаем как есть
    return asset($url);
}

    /**
     * Accessor для получения всех изображений с форматированными URL
     */
    public function getAllImagesAttribute()
    {
        $images = $this->images;
        
        // Если нет связанных изображений, но есть поле image
        if ($images->isEmpty() && $this->image) {
            return collect([(object)[
                'id' => 0,
                'product_id' => $this->id,
                'image_path' => $this->image,
                'url' => $this->getImageUrlAttribute(),
                'formatted_url' => $this->getImageUrlAttribute(),
                'is_main' => true,
                'sort_order' => 0,
                'alt_text' => $this->title
            ]]);
        }
        
        // Форматируем URL для всех изображений
        return $images->map(function($image) {
            $image->formatted_url = $this->formatImageUrl($image->url ?? $image->image_path);
            return $image;
        });
    }

    /**
     * Проверка, есть ли изображения у товара
     */
    public function getHasImagesAttribute()
    {
        if (!empty($this->image)) {
            return true;
        }
        
        if ($this->relationLoaded('images') && $this->images->isNotEmpty()) {
            return true;
        }
        
        return $this->images()->exists();
    }

    /**
     * Геттер для цены со скидкой (с приведением к float)
     */
    public function getDiscountedPriceAttribute()
    {
        $price = (float) $this->price;
        $oldPrice = $this->old_price ? (float) $this->old_price : null;
        
        if ($oldPrice && $oldPrice > $price) {
            return $price;
        }
        
        if ($this->discount > 0) {
            return $price * (1 - $this->discount / 100);
        }
        
        return $price;
    }

    /**
     * Геттер для форматированной цены
     */
    public function getPriceFormattedAttribute()
    {
        return number_format((float) $this->price, 0, ',', ' ') . ' ₽';
    }

    /**
     * Геттер для форматированной старой цены
     */
    public function getOldPriceFormattedAttribute()
    {
        if ($this->old_price && (float) $this->old_price > (float) $this->price) {
            return number_format((float) $this->old_price, 0, ',', ' ') . ' ₽';
        }
        return null;
    }

    /**
     * Проверка, есть ли скидка
     */
    public function getHasDiscountAttribute()
    {
        if ($this->discount > 0) {
            return true;
        }
        
        if ($this->old_price && (float) $this->old_price > (float) $this->price) {
            return true;
        }
        
        return false;
    }

    /**
     * Проверка наличия на складе
     */
    public function getInStockAttribute()
    {
        return $this->stock > 0;
    }

    /**
     * Средний рейтинг товара
     */
    public function getAverageRatingAttribute()
    {
        // Собираем ID всех товаров для расчета рейтинга
        $productIds = $this->getAllProductIdsForRating();
        
        // Рассчитываем средний рейтинг
        $average = ProductReview::whereIn('product_id', $productIds)
            ->where('is_approved', true)
            ->avg('rating');
        
        return $average ? round($average, 1) : 0;
    }


            // В модели Product
        protected $appends = ['average_rating', 'reviews_count'];

        // Или через переопределение метода
        public function toArray()
        {
            $array = parent::toArray();
            $array['average_rating'] = $this->average_rating;
            $array['reviews_count'] = $this->reviews_count;
            return $array;
        }

    /**
     * Количество отзывов
     */
        public function getReviewsCountAttribute()
        {
            $productIds = $this->getAllProductIdsForRating();
            
            return ProductReview::whereIn('product_id', $productIds)
                ->where('is_approved', true)
                ->count();
        }
        protected function getAllProductIdsForRating()
    {
        // Если уже загружены дочерние вариации, используем их
        if ($this->relationLoaded('childVariants')) {
            $productIds = [$this->id];
            if ($this->childVariants->isNotEmpty()) {
                $productIds = array_merge($productIds, $this->childVariants->pluck('id')->toArray());
            }
            return $productIds;
        }
        
        // Если не загружены, делаем запрос к базе
        return Product::where(function($query) {
                $query->where('id', $this->id)
                    ->orWhere('parent_product_id', $this->id);
            })
            ->pluck('id')
            ->toArray();
    }

        public function getRatingDistributionAttribute()
    {
        $productIds = $this->getAllProductIdsForRating();
        
        $distribution = ProductReview::whereIn('product_id', $productIds)
            ->where('is_approved', true)
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->get()
            ->keyBy('rating');
        
        $total = $this->reviews_count;
        $result = [];
        
        for ($i = 5; $i >= 1; $i--) {
            $count = $distribution->has($i) ? $distribution[$i]->count : 0;
            $percentage = $total > 0 ? round(($count / $total) * 100) : 0;
            
            $result[$i] = [
                'rating' => $i,
                'count' => $count,
                'percentage' => $percentage
            ];
        }
        
        return $result;
    }


            /**
         * Отношение отзывов с учетом вариаций
         */
        public function allReviews()
        {
            $productIds = $this->getAllProductIdsForRating();
            
            return ProductReview::whereIn('product_id', $productIds)
                ->where('is_approved', true)
                ->with('user:id,name')
                ->latest();
        }

    /**
     * Проверка, является ли товар основным
     */
    public function isMainProduct()
    {
        return !$this->is_variant && is_null($this->parent_product_id);
    }

    /**
     * Проверка, является ли товар вариацией
     */
    public function isVariant()
    {
        return $this->is_variant || $this->parent_product_id !== null;
    }

    /**
     * Получить все доступные цвета для этого товара
     */
    public function getAvailableColors()
    {
        $colors = collect();
        
        if ($this->isMainProduct()) {
            // Загружаем mainColor если не загружен
            if (!$this->relationLoaded('mainColor') && $this->main_color_id) {
                $this->load('mainColor');
            }
            
            if ($this->mainColor) {
                $colors->push($this->mainColor);
            }
            
            // Загружаем childVariants если не загружены
            if (!$this->relationLoaded('childVariants')) {
                $this->load(['childVariants' => function($query) {
                    $query->with('mainColor');
                }]);
            }
            
            foreach ($this->childVariants as $variant) {
                if ($variant->mainColor && !$colors->contains('id', $variant->main_color_id)) {
                    $colors->push($variant->mainColor);
                }
            }
        } else {
            // Для вариации используем родительский товар
            if (!$this->relationLoaded('parentProduct')) {
                $this->load(['parentProduct' => function($query) {
                    $query->with(['mainColor', 'childVariants.mainColor']);
                }]);
            }
            
            $parent = $this->parentProduct;
            if ($parent) {
                return $parent->getAvailableColors();
            }
        }
        
        return $colors->unique('id')->values();
    }

    // ============== SCOPES ==============
    
    public function scopeMainProducts($query)
    {
        return $query->where('is_variant', false)
                    ->whereNull('parent_product_id');
    }
    
    public function scopeVariants($query)
    {
        return $query->where('is_variant', true);
    }
    
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeInCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }
        // ============== CUSTOM METHODS ==============
    
    /**
     * Получить чистое название без указания цвета в скобках
     * Пример: "Футболка хлопковая (Черный)" → "Футболка хлопковая"
     * 
     * @return string
     */
    public function getCleanTitle(): string
    {
        $title = $this->title;
        
        if (empty($title)) {
            return '';
        }
        
        // Удаляем цвет в скобках в конце строки
        $cleanTitle = preg_replace('/\s*\([^)]*\)$/', '', $title);
        
        return trim($cleanTitle);
    }
    
    /**
     * Получить полное название с цветом (если есть связь с цветом)
     * 
     * @return string
     */
    public function getTitleWithColor(): string
    {
        $cleanTitle = $this->getCleanTitle();
        
        // Используем связанный цвет, если он есть
        if ($this->relationLoaded('mainColor') && $this->mainColor) {
            return $cleanTitle . ' (' . $this->mainColor->name . ')';
        }
        
        // Или используем поле color, если нет связи
        if (!empty($this->color)) {
            return $cleanTitle . ' (' . $this->color . ')';
        }
        
        return $cleanTitle;
    }
    
    /**
     * Accessor для чистого названия (можно использовать как свойство)
     * Использование: $product->clean_title
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function cleanTitle(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->getCleanTitle()
        );
    }
    
    /**
     * Accessor для полного названия с цветом (можно использовать как свойство)
     * Использование: $product->full_title
     * 
     * @return \Illuminate\Database\Eloquent\Casts\Attribute
     */
    public function fullTitle(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn () => $this->getTitleWithColor()
        );
    }
        // ============== DEBUG METHODS ==============
    
    /**
     * Для отладки: показать все данные товара
     */
public function toDebugArray()
{
    return [
        'id' => $this->id,
        'title' => $this->title,
        'is_variant' => $this->is_variant,
        'parent_product_id' => $this->parent_product_id,
        'main_color_id' => $this->main_color_id,
        'mainColor' => $this->mainColor ? [
            'id' => $this->mainColor->id,
            'name' => $this->mainColor->name,
            'hex_code' => $this->mainColor->hex_code,
        ] : null,
        'images_count' => $this->images ? $this->images->count() : 0,
        'images' => $this->images ? $this->images->map(function($image) {
            // Форматируем путь
            $path = $image->image_path ?? ltrim($image->url ?? '', '/');
            $fullPath = $path ? asset($path) : null;
            
            return [
                'id' => $image->id,
                'image_path' => $image->image_path,
                'url' => $image->url,
                'is_main' => $image->is_main,
                'formatted_path' => $path,
                'full_url' => $fullPath,
            ];
        })->toArray() : [],
        'childVariants_count' => $this->childVariants ? $this->childVariants->count() : 0,
        'childVariants' => $this->childVariants ? $this->childVariants->map(function($variant) {
            return [
                'id' => $variant->id,
                'title' => $variant->title,
                'main_color_id' => $variant->main_color_id,
                'mainColor' => $variant->mainColor ? $variant->mainColor->name : null,
                'mainColor_full' => $variant->mainColor ? [
                    'id' => $variant->mainColor->id,
                    'name' => $variant->mainColor->name,
                    'hex_code' => $variant->mainColor->hex_code,
                ] : null,
            ];
        })->toArray() : [],
    ];
}

 public function scopeAvailable($query, $availability = null)
    {
        if ($availability === self::AVAILABILITY_IN_STOCK) {
            return $query->where(function($q) {
                $q->where('stock', '>', 0)
                  ->orWhereHas('childVariants', function($subQuery) {
                      $subQuery->where('stock', '>', 0);
                  });
            });
        }
        
        if ($availability === self::AVAILABILITY_ON_ORDER) {
            return $query->where('availability', self::AVAILABILITY_ON_ORDER);
        }
        
        return $query;
    }

    /**
     * Scope для фильтрации по цене
     */
    public function scopePriceRange($query, $min = null, $max = null)
    {
        if ($min !== null) {
            $query->where('price', '>=', (float)$min);
        }
        
        if ($max !== null) {
            $query->where('price', '<=', (float)$max);
        }
        
        return $query;
    }

    /**
     * Scope для фильтрации по цветам
     */
    public function scopeWithColors($query, array $colorIds)
    {
        if (empty($colorIds)) {
            return $query;
        }
        
        return $query->where(function($q) use ($colorIds) {
            $q->whereIn('main_color_id', $colorIds)
              ->orWhereHas('childVariants', function($subQuery) use ($colorIds) {
                  $subQuery->whereIn('main_color_id', $colorIds);
              });
        });
    }

    /**
     * Scope для фильтрации по размерам
     */
    public function scopeWithSizes($query, array $sizeNames)
    {
        if (empty($sizeNames)) {
            return $query;
        }
        
        return $query->whereHas('sizes', function($q) use ($sizeNames) {
            $q->whereIn('name', $sizeNames)
              ->where('product_sizes.stock', '>', 0);
        });
    }

    /**
     * Scope для фильтрации по рейтингу
     */
    public function scopeMinRating($query, $rating)
    {
        return $query->where('avg_rating', '>=', (float)$rating);
    }

    /**
     * Scope для товаров со скидкой
     */
    public function scopeOnSale($query, $minDiscount = null)
    {
        $query->where(function($q) {
            $q->where('is_on_sale', true)
              ->orWhere('discount', '>', 0)
              ->orWhereNotNull('old_price')
              ->whereColumn('old_price', '>', 'price');
        });
        
        if ($minDiscount !== null) {
            $query->where(function($q) use ($minDiscount) {
                $q->where('discount', '>=', $minDiscount)
                  ->orWhereRaw('(old_price - price) / old_price * 100 >= ?', [$minDiscount]);
            });
        }
        
        return $query;
    }

    /**
     * Получить минимальную и максимальную цену среди всех товаров
     */
    public static function getPriceRange()
    {
        return [
            'min' => self::mainProducts()->min('price') ?? 0,
            'max' => self::mainProducts()->max('price') ?? 100000
        ];
    }

    /**
     * Проверка, доступен ли товар для заказа
     */
    public function isAvailable()
    {
        if ($this->stock > 0) {
            return true;
        }
        
        if ($this->isMainProduct()) {
            return $this->childVariants()
                ->where('stock', '>', 0)
                ->exists();
        }
        
        return false;
    }

    /**
     * Получить статус наличия в читаемом виде
     */
    public function getAvailabilityStatusAttribute()
    {
        if ($this->isAvailable()) {
            return 'В наличии';
        }
        
        if ($this->availability === self::AVAILABILITY_ON_ORDER) {
            return 'Под заказ';
        }
        
        return 'Нет в наличии';
    }

}
