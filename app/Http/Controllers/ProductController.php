<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\Color;
use App\Models\Size; 
use App\Models\WishlistItem;
use App\Models\ProductReview; 
use Illuminate\Support\Facades\Log;
use App\Traits\Filterable;

class ProductController extends Controller
{
    use Filterable;
    /**
     * Главная страница с популярными товарами и новинками
     */
    public function index()
    {
        // Популярные товары - ОСНОВНЫЕ товары с флагом is_popular
        $featuredProducts = Product::where(function($query) {
                $query->where('is_popular', true)
                      ->orWhere('is_bestseller', true);
            })
            ->with(['category', 'images', 'mainColor'])
            ->where('stock', '>', 0)
            ->mainProducts() // Только основные товары
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Новинки - ОСНОВНЫЕ товары с флагом is_new
        $newProducts = Product::where('is_new', true)
            ->with(['category', 'images', 'mainColor'])
            ->where('stock', '>', 0)
            ->mainProducts() // Только основные товары
            ->orderBy('created_at', 'desc')
            ->limit(8)
            ->get();

        // Получаем категории для главной страницы
        $categories = Category::withCount('products')->get();

        return view('home', compact('featuredProducts', 'newProducts', 'categories'));
    }

    /**
     * Страница каталога с фильтрацией и сортировкой
     */
   /**
 * Страница каталога с фильтрацией и сортировкой
 */
public function catalog(Request $request)
{
    // Начинаем с основных товаров
    $query = Product::with(['category', 'images', 'mainColor'])
        ->mainProducts();
    
    // Применяем фильтры из трейта Filterable
    $query = $this->applyFilters($query, $request);
    
    // Применяем сортировку из трейта Filterable
    $query = $this->applySorting($query, $request->get('sort', 'newest'));
    
    // Получаем данные для фильтров
    $categories = Category::getFilterTree();
    $colors = Color::getAvailableColors();
    $sizes = Size::getUniqueSizes();  
    $priceRange = Product::getPriceRange();
    $minPrice = $priceRange['min'];
    $maxPrice = $priceRange['max'];
    
    // Пагинация
    $perPage = $request->get('per_page', 12);
    $products = $query->paginate($perPage)->appends($request->except('page'));
    
    return view('catalog.index', compact(
        'products', 
        'categories', 
        'colors', 
        'sizes', 
        'minPrice', 
        'maxPrice'
    ));
}

/**
 * AJAX фильтрация для каталога
 */
public function filter(Request $request)
{
    $query = Product::with(['category', 'images', 'mainColor'])
        ->mainProducts();
    
    // Используем трейт Filterable
    $query = $this->applyFilters($query, $request);
    $query = $this->applySorting($query, $request->get('sort', 'newest'));
    
    $products = $query->paginate($request->get('per_page', 12));
    
    return response()->json([
        'success' => true,
        'html' => view('catalog.partials.products-grid', compact('products'))->render(),
        'total' => $products->total(),
        'from' => $products->firstItem(),
        'to' => $products->lastItem(),
        'current_page' => $products->currentPage(),
        'last_page' => $products->lastPage()
    ]);
}

    /**
     * Страница отдельного товара с поддержкой вариаций
     */
    /**
 * Страница отдельного товара с поддержкой вариаций
 */
public function show($id)
{
    \Log::info('=== PRODUCT SHOW ===');
    
    try {
        // Находим товар по ID (это может быть основной или вариация)
        $product = Product::with([
            'category',
            'images',
            'mainColor',
            'sizes',
            'parentProduct.images',
            'childVariants' => function($query) {
                $query->with(['mainColor', 'images']);
            }
        ])->findOrFail($id);
        
        \Log::info('Product loaded', [
            'id' => $product->id,
            'title' => $product->title,
            'is_variant' => $product->is_variant,
            'parent_id' => $product->parent_product_id
        ]);
        
        // Определяем основной товар
        if ($product->is_variant && $product->parentProduct) {
            $mainProduct = $product->parentProduct;
            $currentVariant = $product;
        } else {
            $mainProduct = $product;
            $currentVariant = $product->childVariants->first() ?? $product;
        }
        
        // ============ ОБНОВЛЕННАЯ ЛОГИКА ОТЗЫВОВ И РЕЙТИНГА ============
        // Используем методы из модели для расчета рейтинга
        $reviewsCount = $mainProduct->reviews_count;
        $averageRating = $mainProduct->average_rating;
        $ratingDistribution = $mainProduct->rating_distribution;
        
        // Получаем отзывы через метод allReviews() из модели
        $reviews = $mainProduct->allReviews()->get();
        
        \Log::info('Reviews and rating data loaded', [
            'main_id' => $mainProduct->id,
            'reviews_count' => $reviewsCount,
            'average_rating' => $averageRating,
            'distribution' => $ratingDistribution
        ]);
        
        // ============ ДОБАВЛЯЕМ ТЕСТОВЫЕ ОТЗЫВЫ ЕСЛИ НЕТ РЕАЛЬНЫХ ============
        if ($reviewsCount === 0) {
            \Log::info('No real reviews found, creating sample reviews for demonstration');
            
            // Создаем коллекцию тестовых отзывов
            $reviews = collect([
                (object)[
                    'id' => 9991,
                    'product_id' => $mainProduct->id,
                    'user_id' => 1,
                    'user' => (object)[
                        'id' => 1,
                        'name' => 'Анна Петрова'
                    ],
                    'rating' => 5,
                    'comment' => 'Отличный товар! Очень качественный материал и прекрасная посадка.',
                    'is_approved' => true,
                    'created_at' => now()->subDays(5),
                    'updated_at' => now()->subDays(5)
                ],
                (object)[
                    'id' => 9992,
                    'product_id' => $mainProduct->id,
                    'user_id' => 2,
                    'user' => (object)[
                        'id' => 2,
                        'name' => 'Мария Смирнова'
                    ],
                    'rating' => 4,
                    'comment' => 'Хороший товар, но немного маломерит. Рекомендую брать на размер больше.',
                    'is_approved' => true,
                    'created_at' => now()->subDays(10),
                    'updated_at' => now()->subDays(10)
                ],
                (object)[
                    'id' => 9993,
                    'product_id' => $mainProduct->id,
                    'user_id' => 3,
                    'user' => (object)[
                        'id' => 3,
                        'name' => 'Елена Иванова'
                    ],
                    'rating' => 5,
                    'comment' => 'Супер качество! Заказываю уже второй раз. Быстрая доставка.',
                    'is_approved' => true,
                    'created_at' => now()->subDays(15),
                    'updated_at' => now()->subDays(15)
                ]
            ]);
            
            $reviewsCount = $reviews->count();
            
            // Пересчитываем средний рейтинг для тестовых отзывов
            $averageRating = $reviews->avg('rating') ?? 0;
            $averageRating = round($averageRating, 1);
            
            \Log::info('Sample reviews created', [
                'count' => $reviewsCount,
                'average_rating' => $averageRating
            ]);
        }
        // ============ КОНЕЦ ИСПРАВЛЕНИЙ ============
        
        // Загружаем дополнительные данные для основного товара
        $mainProduct->load(['images', 'childVariants.mainColor']);
        
        // Получаем все изображения
        $productImages = collect();
        
        // 1. Проверяем изображения текущей вариации
        if ($currentVariant->images && $currentVariant->images->isNotEmpty()) {
            $productImages = $currentVariant->images;
            \Log::info('Using variant images', ['count' => $productImages->count()]);
        }
        // 2. Если нет, проверяем изображения основного товара
        elseif ($mainProduct->images && $mainProduct->images->isNotEmpty()) {
            $productImages = $mainProduct->images;
            \Log::info('Using main product images', ['count' => $productImages->count()]);
        }
        // 3. Если нет изображений в таблице, используем поле image
        elseif ($currentVariant->image) {
            $productImages = collect([(object)[
                'id' => 9999,
                'image_path' => $currentVariant->image,
                'url' => $this->formatImageUrl($currentVariant->image),
                'formatted_url' => $this->formatImageUrl($currentVariant->image),
                'is_main' => true,
                'sort_order' => 0,
                'alt_text' => $currentVariant->title
            ]]);
            \Log::info('Using variant image field');
        }
        // 4. Последний fallback - поле image основного товара
        elseif ($mainProduct->image) {
            $productImages = collect([(object)[
                'id' => 9998,
                'image_path' => $mainProduct->image,
                'url' => $this->formatImageUrl($mainProduct->image),
                'formatted_url' => $this->formatImageUrl($mainProduct->image),
                'is_main' => true,
                'sort_order' => 0,
                'alt_text' => $mainProduct->title
            ]]);
            \Log::info('Using main product image field');
        }
        
        // Получаем все доступные цвета (из основных и дочерних товаров)
        $availableColors = $mainProduct->getAvailableColors();
        
        // Получаем размеры (из текущей вариации)
        $availableSizes = $currentVariant->sizes ?? collect();
        
        // Проверяем избранное
        $isInWishlist = false;
        if (auth()->check()) {
            $isInWishlist = WishlistItem::where('user_id', auth()->id())
                ->where('product_id', $mainProduct->id)
                ->exists();
        }
        
        // Похожие товары (другие основные товары из той же категории)
        $relatedProducts = Product::with(['category', 'images', 'mainColor'])
            ->where('category_id', $mainProduct->category_id)
            ->where('id', '!=', $mainProduct->id)
            ->mainProducts()
            ->limit(4)
            ->get();

        \Log::info('Related products query details:', [
            'category_id' => $mainProduct->category_id,
            'main_product_id' => $mainProduct->id,
            'related_count' => $relatedProducts->count(),
            'related_ids' => $relatedProducts->pluck('id')->toArray(),
            'related_titles' => $relatedProducts->pluck('title')->toArray(),
            
            // Проверяем scope mainProducts
            'main_products_sql' => Product::where('category_id', $mainProduct->category_id)
                ->where('id', '!=', $mainProduct->id)
                ->mainProducts()
                ->toSql()
        ]);    

        \Log::info('Product data prepared successfully', [
            'main_id' => $mainProduct->id,
            'variant_id' => $currentVariant->id,
            'images_count' => $productImages->count(),
            'reviews_count' => $reviewsCount,
            'average_rating' => $averageRating,
            'colors_count' => $availableColors->count(),
            'sizes_count' => $availableSizes->count(),
            'related_count' => $relatedProducts->count()
        ]);

        return view('products.show', [
            'product' => $mainProduct,
            'currentVariant' => $currentVariant,
            'productImages' => $productImages,
            'reviews' => $reviews,
            'reviewsCount' => $reviewsCount,
            'averageRating' => $averageRating, // ← ДОБАВЛЕНО
            'ratingDistribution' => $ratingDistribution, // ← ДОБАВЛЕНО
            'availableColors' => $availableColors,
            'availableSizes' => $availableSizes,
            'isInWishlist' => $isInWishlist,
            'relatedProducts' => $relatedProducts
        ]);

    } catch (\Exception $e) {
        \Log::error('Product show error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        abort(404, 'Товар не найден');
    }
}

    /**
     * AJAX: Получить данные вариации по цвету
     */
    public function getVariantData(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'color_id' => 'required|exists:colors,id'
        ]);
        
        \Log::info('=== GET VARIANT DATA ===');
        \Log::info('Request data:', $request->all());
        
        // Находим основной продукт
        $mainProduct = Product::with([
            'images',
            'mainColor',
            'childVariants' => function($query) use ($request) {
                $query->where('main_color_id', $request->color_id)
                      ->with(['images', 'sizes', 'mainColor']);
            }
        ])->find($request->product_id);
        
        if (!$mainProduct) {
            \Log::error('Product not found: ' . $request->product_id);
            return response()->json([
                'success' => false,
                'message' => 'Товар не найден'
            ], 404);
        }
        
        \Log::info('Main product loaded:', [
            'id' => $mainProduct->id,
            'title' => $mainProduct->title,
            'child_variants_count' => $mainProduct->childVariants->count(),
            'main_color_id' => $mainProduct->main_color_id
        ]);
        
        // Ищем дочернюю вариацию с нужным цветом
        $variant = $mainProduct->childVariants->first();
        
        // Если нет дочерней вариации, проверяем сам основной товар
        if (!$variant) {
            if ($mainProduct->main_color_id == $request->color_id) {
                $variant = $mainProduct;
                $variant->load(['images', 'sizes', 'mainColor']);
                \Log::info('Using main product as variant');
            } else {
                \Log::error('Variant not found for color: ' . $request->color_id);
                return response()->json([
                    'success' => false,
                    'message' => 'Вариация с таким цветом не найдена'
                ], 404);
            }
        }
        
        \Log::info('Variant found:', [
            'id' => $variant->id,
            'title' => $variant->title,
            'main_color_id' => $variant->main_color_id,
            'images_count' => $variant->images ? $variant->images->count() : 0,
            'is_variant' => $variant->is_variant
        ]);
        
        // Получаем изображения
        $images = collect();
        
        // 1. Сначала берем изображения вариации
        if ($variant->images && $variant->images->count() > 0) {
            $images = $variant->images;
            \Log::info('Using variant images');
        }
        // 2. Если нет, берем изображения основного товара
        elseif ($mainProduct->images && $mainProduct->images->count() > 0) {
            $images = $mainProduct->images;
            \Log::info('Using main product images');
        }
        // 3. Используем поле image вариации
        elseif ($variant->image) {
            $images = collect([(object)[
                'id' => 9999,
                'image_path' => $variant->image,
                'url' => $this->formatImageUrl($variant->image),
                'is_main' => true,
                'sort_order' => 0,
                'alt_text' => $variant->title
            ]]);
            \Log::info('Using variant image field');
        }
        // 4. Используем поле image основного товара
        elseif ($mainProduct->image) {
            $images = collect([(object)[
                'id' => 9998,
                'image_path' => $mainProduct->image,
                'url' => $this->formatImageUrl($mainProduct->image),
                'is_main' => true,
                'sort_order' => 0,
                'alt_text' => $mainProduct->title
            ]]);
            \Log::info('Using main product image field');
        }
        
        // Форматируем изображения для ответа
        $formattedImages = $images->map(function($image) {
            $url = $image->formatted_url ?? $this->formatImageUrl($image->image_path ?? $image->url);
            return [
                'id' => $image->id ?? null,
                'image_path' => $image->image_path ?? $image->url,
                'url' => $url,
                'formatted_url' => $url,
                'is_main' => $image->is_main ?? false,
                'alt_text' => $image->alt_text ?? null
            ];
        })->toArray();
        
        // Подготавливаем данные вариации
        $variantData = [
            'id' => $variant->id,
            'product_id' => $variant->id,
            'color_id' => $variant->main_color_id,
            'color_name' => $variant->mainColor ? $variant->mainColor->name : 'Не указан',
            'color_hex' => $variant->mainColor ? $variant->mainColor->hex_code : null,
            'price' => (float) $variant->price,
            'price_formatted' => number_format($variant->price, 0, ',', ' ') . ' ₽',
            'old_price' => $variant->old_price ? (float) $variant->old_price : null,
            'old_price_formatted' => $variant->old_price ? number_format($variant->old_price, 0, ',', ' ') . ' ₽' : null,
            'stock' => (int) $variant->stock,
            'in_stock' => $variant->stock > 0,
            'sizes' => $variant->sizes ? $variant->sizes->map(function($size) {
                return [
                    'id' => $size->id,
                    'name' => $size->name,
                    'stock' => (int) ($size->pivot->stock ?? $size->stock ?? 0)
                ];
            })->toArray() : [],
            'image' => $variant->image ? $this->formatImageUrl($variant->image) : null,
            'image_url' => $variant->image ? $this->formatImageUrl($variant->image) : null,
            'has_discount' => (bool) ($variant->old_price && $variant->old_price > $variant->price),
            'slug' => $variant->slug ?? null,
            'title' => $variant->title,
            'is_variant' => (bool) $variant->is_variant
        ];
        
        \Log::info('Response prepared:', [
            'variant_id' => $variantData['id'],
            'images_count' => count($formattedImages),
            'color_name' => $variantData['color_name'],
            'price' => $variantData['price_formatted']
        ]);
        
        return response()->json([
            'success' => true,
            'variant' => $variantData,
            'images' => $formattedImages,
            'product_title' => $variant->title
        ]);
    }

    /**
     * Товары по категории (по slug)
     */
    public function byCategory($slug)
    {
        // Находим категорию по slug
        $category = Category::where('slug', $slug)->firstOrFail();
        
        // Получаем товары этой категории - ТОЛЬКО ОСНОВНЫЕ
        $products = Product::with(['category', 'images', 'mainColor'])
            ->where('category_id', $category->id)
            ->mainProducts() // Только основные товары
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $categories = Category::all();
        
        return view('catalog.category', compact('products', 'category', 'categories'));
    }

    /**
     * Поиск товаров
     */
    /**
 * Поиск товаров
 */
public function search(Request $request)
{
    $query = $request->get('q');
    
    // Загружаем основные товары С ВАРИАЦИЯМИ
    $products = Product::with([
            'category', 
            'images', 
            'mainColor',
            'childVariants' => function($q) {
                $q->whereNotNull('image')  // Только вариации с изображениями
                  ->with('mainColor')
                  ->select('id', 'parent_product_id', 'title', 'image', 'price', 'old_price', 'main_color_id', 'stock');
            }
        ])
        ->mainProducts() // Только основные товары
        ->where(function($q) use ($query) {
            $q->where('title', 'LIKE', "%{$query}%")
              ->orWhere('description', 'LIKE', "%{$query}%")
              ->orWhere('short_description', 'LIKE', "%{$query}%")
              ->orWhere('material', 'LIKE', "%{$query}%");
        })
        ->orderBy('created_at', 'desc')
        ->paginate(12);
    
    // 🔥 ВАЖНО: Подтягиваем изображение из вариации, если у родителя его нет
    foreach ($products as $product) {
        if (empty($product->image) && $product->childVariants->isNotEmpty()) {
            // Находим первую вариацию с изображением
            $variantWithImage = $product->childVariants->firstWhere('image', '!=', null);
            if ($variantWithImage) {
                $product->image = $variantWithImage->image;
                // Также можно установить цену и другие данные из вариации
                if (empty($product->price) && $variantWithImage->price) {
                    $product->price = $variantWithImage->price;
                }
            }
        }
    }
        
    $categories = Category::all();
    
    return view('catalog.search', compact('products', 'query', 'categories'));
}

    /**
     * Популярные товары (отдельная страница)
     */
    public function popular()
    {
        $featuredProducts = Product::with(['category', 'images', 'mainColor'])
            ->mainProducts() // Только основные товары
            ->where(function($q) {
                $q->where('is_popular', true)
                  ->orWhere('is_bestseller', true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('catalog.popular', compact('featuredProducts'));
    }

    /**
     * Товары со скидкой (отдельная страница)
     */
    public function sale()
    {
        $saleProducts = Product::with(['category', 'images', 'mainColor'])
            ->mainProducts() // Только основные товары
            ->where(function($query) {
                $query->where('is_on_sale', true)
                      ->orWhere('discount', '>', 0)
                      ->orWhereNotNull('old_price')
                      ->whereColumn('old_price', '>', 'price');
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('catalog.sale', compact('saleProducts'));
    }

    /**
     * Новинки (отдельная страница)
     */
    public function new()
    {
        $newProducts = Product::with(['category', 'images', 'mainColor'])
            ->mainProducts() // Только основные товары
            ->where('is_new', true)
            ->orderBy('created_at', 'desc')
            ->paginate(12);

        return view('catalog.new', compact('newProducts'));
    }
    
    /**
     * Товары по цвету
     */
    public function byColor($colorId)
    {
        $color = Color::findOrFail($colorId);
        
        $products = Product::with(['category', 'images', 'mainColor'])
            ->mainProducts() // Только основные товары
            ->where(function($q) use ($colorId) {
                $q->where('main_color_id', $colorId)
                  ->orWhereHas('childVariants', function($query) use ($colorId) {
                      $query->where('main_color_id', $colorId);
                  });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(12);
            
        $categories = Category::all();
        
        return view('catalog.color', compact('products', 'color', 'categories'));
    }

/**
 * Форматирование URL изображения (помощник)
 */
private function formatImageUrl($url)
{
    if (empty($url)) {
        return null;
    }
    
    // Удаляем начальный слеш если есть
    $url = ltrim($url, '/');
    
    // Если это уже полный URL
    if (filter_var($url, FILTER_VALIDATE_URL)) {
        return $url;
    }
    
    // Если путь начинается с 'images/', используем asset()
    if (str_starts_with($url, 'images/')) {
        return asset($url);
    }
    
    // Если нет, добавляем 'images/' и используем asset()
    return asset('images/' . $url);
}
}