@extends('layouts.layout')

@section('title')
    @parent {{ $product->title }} | ONEONE
@endsection

@section('content')
@php
    // Добавьте в начале секции content, после других логов
    \Log::info('Related products data:', [
        'is_set' => isset($relatedProducts),
        'count' => isset($relatedProducts) ? $relatedProducts->count() : 0,
        'related_products' => isset($relatedProducts) ? $relatedProducts->toArray() : []
    ]);
    
    // Также проверьте, что секция вообще рендерится
    \Log::info('Rendering similar products section');
@endphp
@php
    \Log::info('Template loaded with reviews data:', [
        'reviews_count' => $reviewsCount ?? 0,
        'reviews_data' => isset($reviews) ? $reviews->toArray() : []
    ]);
@endphp
@php
    // Безопасная инициализация переменных
    $currentVariant = $currentVariant ?? $product;
    $availableColors = $availableColors ?? collect();
    $availableSizes = $availableSizes ?? collect();
    $isInWishlist = $isInWishlist ?? false;
    $relatedProducts = $relatedProducts ?? collect();
    
    // Безопасный доступ к свойствам
    $displayPrice = $currentVariant->price ?? $product->price;
    $displayOldPrice = $currentVariant->old_price ?? $product->old_price;
    $displayStock = $currentVariant->stock ?? $product->stock;
    $displaySku = $currentVariant->sku ?? $product->sku ?? 'MP002XW1XW6';
    $displayHasDiscount = $currentVariant->has_discount ?? $product->has_discount;
    
    // ИНИЦИАЛИЗАЦИЯ ПЕРЕМЕННОЙ $displayMainColor
    $displayMainColor = null;
    if (isset($currentVariant->mainColor) && $currentVariant->mainColor) {
        $displayMainColor = $currentVariant->mainColor;
    } elseif (isset($product->mainColor) && $product->mainColor) {
        $displayMainColor = $product->mainColor;
    }
    
    // ФУНКЦИЯ ДЛЯ ОПРЕДЕЛЕНИЯ ПРАВИЛЬНЫХ ПУТЕЙ К ИЗОБРАЖЕНИЯМ
    function getProductImagePaths($product, $variant) {
        $images = collect();
        
        // Определяем цвет
        $colorId = $variant->main_color_id ?? $product->main_color_id;
        $colorName = '';
        $colorNameEng = '';
        
        // Цвета из базы
        $colors = [
            1 => ['ru' => 'Черный', 'en' => 'black'],
            2 => ['ru' => 'Белый', 'en' => 'white'],
            4 => ['ru' => 'Бежевый', 'en' => 'beige'],
            9 => ['ru' => 'Коричневый', 'en' => 'brown'],
            10 => ['ru' => 'Розовый', 'en' => 'pink'],
            11 => ['ru' => 'Красный', 'en' => 'red']
        ];
        
        if (isset($colors[$colorId])) {
            $colorName = $colors[$colorId]['ru'];
            $colorNameEng = $colors[$colorId]['en'];
        }
        
        // Определяем тип товара
        $title = strtolower($product->title ?? '');
        $categoryId = $product->category_id ?? null;
        
        // Определяем папку и шаблоны изображений
        $folder = '';
        $imagePatterns = [];
        $baseFileName = '';
        
        // 1. ПЛАСТЬЯ
        if (strpos($title, 'платье') !== false || $categoryId == 2) {
            $folder = $colorNameEng . '-dress';
            $baseFileName = $folder;
            $imagePatterns = [
                ['suffix' => 'L', 'alt' => 'Вид сбоку'],
                ['suffix' => 'P', 'alt' => 'Вид спереди'],
                ['suffix' => 'Z', 'alt' => 'Вид сзади']
            ];
        }
        // 2. ДЛИННЫЕ ЮБКИ
        elseif (strpos($title, 'длинная юбка') !== false || 
                (strpos($title, 'юбка') !== false && strpos($title, 'длинн') !== false)) {
            $folder = $colorName . ' long skirt';
            $baseFileName = 'lg-sk-' . $colorNameEng;
            $imagePatterns = [
                ['suffix' => '', 'alt' => 'Вид спереди'],
                ['suffix' => '-back', 'alt' => 'Вид сзади']
            ];
        }
        // 3. ТЕННИСНЫЕ ЮБКИ
        elseif (strpos($title, 'теннисная') !== false || strpos($title, 'теннис') !== false) {
            $folder = $colorName . ' tennis skirt';
            $baseFileName = 'sh-sk-' . $colorNameEng;
            $imagePatterns = [
                ['suffix' => '', 'alt' => 'Вид спереди'],
                ['suffix' => '-left', 'alt' => 'Вид слева']
            ];
        }
        // 4. ЛОНГСЛИВЫ
        elseif (strpos($title, 'лонгслив') !== false || $categoryId == 6) {
            $folder = $colorName . ' long sleeve';
            
            // Бежевый лонгслив
            if ($colorId == 4) {
                $baseFileName = $colorNameEng . '-jac';
                $imagePatterns = [
                    ['prefix' => '', 'suffix' => '', 'alt' => 'Вид спереди'],
                    ['prefix' => 'back-', 'suffix' => '', 'alt' => 'Вид сзади']
                ];
            }
            // Розовый лонгслив
            elseif ($colorId == 10) {
                $baseFileName = $colorNameEng . '-jac';
                $imagePatterns = [
                    ['prefix' => '', 'suffix' => '', 'alt' => 'Вид спереди'],
                    ['prefix' => 'back-', 'suffix' => '', 'alt' => 'Вид сзади']
                ];
            }
            // Красный лонгслив
            elseif ($colorId == 11) {
                $baseFileName = $colorNameEng . '-jac';
                $imagePatterns = [
                    ['prefix' => '', 'suffix' => '', 'alt' => 'Вид спереди'],
                    ['prefix' => 'back-', 'suffix' => '', 'alt' => 'Вид сзади']
                ];
            }
        }
        
        // Генерируем пути к изображениям
        if (!empty($folder) && !empty($imagePatterns)) {
            foreach ($imagePatterns as $index => $pattern) {
                $prefix = $pattern['prefix'] ?? '';
                $suffix = $pattern['suffix'] ?? '';
                
                // Формируем имя файла
                if (strpos($folder, 'dress') !== false) {
                    // Платья: brown-dressL.png
                    $fileName = $baseFileName . $suffix . '.png';
                } elseif (strpos($folder, 'skirt') !== false) {
                    // Юбки: lg-sk-brown.png или lg-sk-brown-back.png
                    $fileName = $baseFileName . $suffix . '.png';
                } elseif (strpos($folder, 'long sleeve') !== false) {
                    // Лонгсливы: beige-jac.png или back-beige-jac.png
                    $fileName = $prefix . $baseFileName . $suffix . '.png';
                } else {
                    continue; // Пропускаем неизвестные типы
                }
                
                $imagePath = 'images/products/' . $folder . '/' . $fileName;
                
                // Проверяем существование файла
                if (file_exists(public_path($imagePath))) {
                    $images->push((object)[
                        'image_path' => $imagePath,
                        'url' => $imagePath,
                        'alt_text' => $product->title . ' - ' . ($pattern['alt'] ?? 'Изображение'),
                        'is_main' => $index === 0,
                        'sort_order' => $index + 1
                    ]);
                } else {
                    \Log::warning("Image file not found: " . public_path($imagePath));
                }
            }
        }
        
        return $images;
    }
    
    // Получаем изображения
    $allImages = $productImages ?? collect();
    
    // Если нет изображений из контроллера, генерируем их
    if ($allImages->isEmpty()) {
        $allImages = getProductImagePaths($product, $currentVariant);
    }
    
    // Если всё равно нет изображений, проверяем связи в продукте
    if ($allImages->isEmpty() && $product->relationLoaded('images') && $product->images->isNotEmpty()) {
        $allImages = $product->images;
    }
    
    // Определяем главное изображение
    $mainImageUrl = null;
    if ($allImages->isNotEmpty()) {
        $mainImage = $allImages->where('is_main', true)->first() ?? $allImages->first();
        $mainImageUrl = $mainImage->image_path ?? $mainImage->url ?? null;
        if ($mainImageUrl) {
            $mainImageUrl = asset(ltrim($mainImageUrl, '/'));
        }
    }
    
    // Безопасная инициализация отзывов
    $reviews = $reviews ?? collect();
    $reviewsCount = $reviewsCount ?? 0;
@endphp

    <!-- Product Detail - Стиль ONEONE -->
    <div class="product-detail-page">
        <!-- Хлебные крошки -->
        <div class="container py-4">
            <nav aria-label="breadcrumb" class="breadcrumb-nav">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
                    @if($product->category)
                        <li class="breadcrumb-item"><a href="{{ route('catalog', ['category' => $product->category->slug]) }}">{{ $product->category->name }}</a></li>
                    @endif
                    <li class="breadcrumb-item active" aria-current="page">{{ \Illuminate\Support\Str::limit($product->title, 50) }}</li>
                </ol>
            </nav>
        </div>

        <!-- Основная информация о товаре -->
        <div class="container py-2 py-lg-4">
            <div class="row g-4 g-lg-5">
                <!-- Левая колонка - миниатюры и изображения -->
                <div class="col-lg-6">
                    <div class="row g-3">
                        @if($allImages->count() > 1)
                        <div class="col-2 d-none d-lg-block">
                            <div class="product-thumbnails-vertical">
                                @foreach($allImages as $key => $image)
                                    @php
                                        $imageUrl = $image->image_path ?? $image->url ?? null;
                                        if ($imageUrl) {
                                            $imageUrl = asset(ltrim($imageUrl, '/'));
                                        }
                                    @endphp
                                    
                                    @if($imageUrl)
                                    <div class="thumbnail-item-vertical mb-3 {{ $key === 0 ? 'active' : '' }}"
                                        onclick="changeMainImage('{{ $imageUrl }}', this)">
                                        <img src="{{ $imageUrl }}" 
                                            alt="{{ $image->alt_text ?? $product->title }}"
                                            class="img-fluid"
                                            onerror="this.style.display='none'; console.error('Thumbnail failed to load: {{ $imageUrl }}');">
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        @endif

                        <div class="col-12 {{ $allImages->count() > 1 ? 'col-lg-10' : 'col-lg-12' }}">
                            <div class="product-image-main-oneone mb-4">
                                <div class="product-image-container-oneone">
                                    @if($mainImageUrl)
                                    <img src="{{ $mainImageUrl }}" 
                                        alt="{{ $product->title }}" 
                                        class="img-fluid product-main-image-oneone"
                                        id="mainProductImageOneone"
                                        onerror="this.src='data:image/svg+xml,%3Csvg xmlns=\"http://www.w3.org/2000/svg\" viewBox=\"0 0 400 400\"%3E%3Crect width=\"100%25\" height=\"100%25\" fill=\"%23f5f5f5\"/%3E%3Ctext x=\"50%25\" y=\"50%25\" font-family=\"Arial\" font-size=\"14\" fill=\"%23999\" text-anchor=\"middle\" dy=\".3em\"%3EНет изображения%3C/text%3E%3C/svg%3E'; console.error(\"Main image failed to load: {{ $mainImageUrl }}\");">
                                    @else
                                    <div class="no-image-placeholder">
                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 400 400">
                                            <rect width="100%" height="100%" fill="#f5f5f5"/>
                                            <text x="50%" y="50%" font-family="Arial" font-size="14" fill="#999" text-anchor="middle" dy=".3em">Нет изображения</text>
                                        </svg>
                                    </div>
                                    @endif
                                </div>
                            </div>

                            @if($allImages->count() > 1)
                            <div class="product-thumbnails-horizontal d-block d-lg-none">
                                <div class="row g-2">
                                    @foreach($allImages as $key => $image)
                                        @php
                                            $imageUrl = $image->image_path ?? $image->url ?? null;
                                            if ($imageUrl) {
                                                $imageUrl = asset(ltrim($imageUrl, '/'));
                                            }
                                        @endphp
                                        
                                        @if($imageUrl)
                                        <div class="col-4 col-sm-3">
                                            <div class="thumbnail-item-horizontal {{ $key === 0 ? 'active' : '' }}"
                                                onclick="changeMainImage('{{ $imageUrl }}', this)">
                                                <img src="{{ $imageUrl }}" 
                                                    alt="{{ $image->alt_text ?? $product->title }}"
                                                    class="img-fluid"
                                                    onerror="this.style.display='none'; console.error('Thumbnail failed to load: {{ $imageUrl }}');">
                                            </div>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>

                <!-- Правая колонка - информация -->
                <div class="col-lg-6">
                    <div class="product-info-oneone">
                        <!-- Бейджи -->
                        <div class="product-badges-oneone mb-3">
                            @if($product->is_new)
                                <span class="badge-oneone badge-oneone--new">NEW</span>
                            @endif
                            @if($displayHasDiscount)
                                @php
                                    $discountPercentage = 0;
                                    if ($displayOldPrice && $displayPrice) {
                                        $discountPercentage = round((($displayOldPrice - $displayPrice) / $displayOldPrice) * 100);
                                    }
                                @endphp
                                <span class="badge-oneone badge-oneone--sale">SALE -{{ $discountPercentage }}%</span>
                            @endif
                            @if($product->is_popular)
                                <span class="badge-oneone badge-oneone--popular">ПОПУЛЯРНОЕ</span>
                            @endif
                            @if($product->is_bestseller)
                                <span class="badge-oneone badge-oneone--bestseller">ХИТ</span>
                            @endif
                        </div>

                        <!-- Заголовок -->
                        <h1 class="product-title-oneone mb-2">{{ $product->getCleanTitle() ?? $product->title }}</h1>
                        
                        <!-- ==================== -->
                        <!-- ВЫБОР ЦВЕТА -->
                        <!-- ==================== -->
                        @if($availableColors && $availableColors->count() > 0)
                        <div class="product-colors-oneone mb-4">
                            <div class="colors-label mb-2">Цвет:</div>
                            <div class="colors-list">
                                @foreach($availableColors as $color)
                                    @php
                                        $isActive = false;
                                        if ($currentVariant->main_color_id == $color->id) {
                                            $isActive = true;
                                        } elseif ($currentVariant->mainColor && $currentVariant->mainColor->id == $color->id) {
                                            $isActive = true;
                                        }
                                    @endphp
                                    <button type="button" 
                                            class="color-option {{ $isActive ? 'active' : '' }}"
                                            data-color-id="{{ $color->id }}"
                                            data-color-name="{{ $color->name }}"
                                            data-color-hex="{{ $color->hex_code ?? '#CCCCCC' }}"
                                            title="{{ $color->name }}">
                                        @if(isset($color->hex_code) && $color->hex_code)
                                            <span class="color-swatch" style="background-color: {{ $color->hex_code }}"></span>
                                        @else
                                            <span class="color-swatch color-swatch-text">{{ substr($color->name, 0, 1) }}</span>
                                        @endif
                                        <span class="color-name">{{ $color->name }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        @endif
                        
                        <!-- Артикул -->
                        <div class="product-sku-oneone mb-3">
                            Артикул: <span class="sku-value">{{ $displaySku }}</span>
                        </div>

                 <!-- Рейтинг -->
                <div class="product-rating-oneone mb-3">
                    <div class="rating-stars d-inline-block">
                        @php
                            $averageRating = $averageRating ?? ($product->average_rating ?? 0);
                            $reviewsCountDisplay = $reviewsCount ?? 0;
                            
                            $fullStars = floor($averageRating);
                            $hasHalfStar = ($averageRating - $fullStars) >= 0.5;
                            $emptyStars = 5 - $fullStars - ($hasHalfStar ? 1 : 0);
                        @endphp
                        
                        @for($i = 0; $i < $fullStars; $i++)
                            <i class="bi bi-star-fill text-warning"></i>
                        @endfor
                        
                        @if($hasHalfStar)
                            <i class="bi bi-star-half text-warning"></i>
                        @endif
                        
                        @for($i = 0; $i < $emptyStars; $i++)
                            <i class="bi bi-star text-warning"></i>
                        @endfor
                    </div>
                    
                    <span class="rating-value ms-2 fw-bold">{{ number_format($averageRating, 1) }}</span>
                    <span class="rating-count ms-2 text-muted">({{ $reviewsCountDisplay }} отзывов)</span>
                </div>

                        <!-- Цена -->
                        <div class="product-price-oneone mb-4">
                            @if($displayHasDiscount && $displayOldPrice)
                                <div class="product-price-old-oneone">
                                    {{ number_format($displayOldPrice, 0, ',', ' ') }} ₽
                                </div>
                            @endif
                            <div class="product-price-current-oneone">
                                {{ number_format($displayPrice, 0, ',', ' ') }} ₽
                            </div>
                        </div>

                        <!-- ==================== -->
                        <!-- КНОПКА ВЫБОРА РАЗМЕРА -->
                        <!-- ==================== -->
                        <div class="product-sizes-selection mb-4">
                            <div class="sizes-selection-label mb-2">Размер:</div>
                            <button type="button" 
                                    class="btn-size-selector btn-oneone btn-oneone--outline w-100 d-flex align-items-center justify-content-between"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#sizeSelectionModal"
                                    id="sizeSelectorButton">
                                <span class="btn-size-selector-text">
                                    <span id="selectedSizeText">Выберите размер</span>
                                </span>
                                <span class="btn-size-selector-icon">
                                    <i class="bi bi-chevron-down"></i>
                                </span>
                            </button>
                            <input type="hidden" id="selectedSizeId" value="">
                            <input type="hidden" id="selectedSizeName" value="">
                        </div>

                        <!-- ==================== -->
                        <!-- ВЫБОР КОЛИЧЕСТВА -->
                        <!-- ==================== -->
                        <div class="product-quantity-oneone mb-4">
                            <div class="quantity-label mb-2">Количество:</div>
                            <div class="quantity-selector">
                                <button type="button" class="quantity-btn quantity-minus" onclick="changeQuantity(-1)">
                                    <i class="bi bi-dash"></i>
                                </button>
                                <input type="number" 
                                    class="quantity-input" 
                                    id="productQuantity"
                                    value="1" 
                                    min="1" 
                                    max="{{ $displayStock }}"
                                    readonly>
                                <button type="button" class="quantity-btn quantity-plus" onclick="changeQuantity(1)">
                                    <i class="bi bi-plus"></i>
                                </button>
                            </div>
                            <div class="quantity-stock-info mt-2">
                                @if($displayStock > 0)
                                    <span class="text-success">
                                        <i class="bi bi-check-circle me-1"></i>В наличии: {{ $displayStock }} шт.
                                    </span>
                                @else
                                    <span class="text-danger">
                                        <i class="bi bi-x-circle me-1"></i>Нет в наличии
                                    </span>
                                @endif
                            </div>
                        </div>

                        <!-- Кнопка избранного -->
                       <div class="wishlist-button-oneone mb-3">
                            @auth
                                <button type="button" 
                                        class="btn-oneone btn-oneone--outline w-100 {{ $isInWishlist ? 'in-wishlist' : '' }}" 
                                        id="wishlistButton"
                                        onclick="toggleWishlist({{ $product->id }}, this)"
                                        title="{{ $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' }}">
                                    <span class="btn-oneone-icon">
                                        <i class="bi {{ $isInWishlist ? 'bi-heart-fill' : 'bi-heart' }} me-2"></i>
                                    </span>
                                    <span class="btn-oneone-text">
                                        {{ $isInWishlist ? 'В избранном' : 'В избранное' }}
                                    </span>
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn-oneone btn-oneone--outline w-100 d-flex align-items-center justify-content-center"
                                style="text-decoration: none;"
                                title="Добавить в избранное">
                                    <span class="btn-oneone-icon">
                                        <i class="bi bi-heart me-2"></i>
                                    </span>
                                    <span class="btn-oneone-text">В избранное</span>
                                </a>
                            @endauth
                        </div>

                        <!-- Кнопка "В корзину" -->
                    <div class="add-to-cart-section-oneone mb-5">
                        <button class="btn-oneone btn-oneone--primary w-100 add-to-cart-oneone"
                        id="addToCartButton"
                        data-product-id="{{ $currentVariant->id ?? $product->id }}"
                            {{ $displayStock <= 0 ? 'disabled' : '' }}>
                            <span class="btn-oneone-text">
                                @if($displayStock <= 0)
                                    Нет в наличии
                                @else
                                    В корзину
                                @endif
                            </span>
                            <span class="btn-oneone-icon">
                                <i class="bi bi-cart"></i>
                            </span>
                        </button>
                        @if($displayStock <= 0)
                            <div class="mt-3">
                                <button class="btn-oneone btn-oneone--outline w-100 notify-when-available"
                                        data-product-id="{{ $product->id }}">
                                    <i class="bi bi-bell me-2"></i>Уведомить о поступлении
                                </button>
                            </div>
                        @endif
                    </div>

                        <!-- Доставка -->
                        <div class="delivery-info-oneone mb-5">
                            <div class="delivery-title-oneone mb-3">
                                <i class="bi bi-truck me-2"></i>Доставим в г.Москва
                            </div>
                            <ul class="delivery-list-oneone list-unstyled">
                                <li class="delivery-item-oneone mb-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    Курьером завтра, бесплатно
                                </li>
                                <li class="delivery-item-oneone mb-2">
                                    <i class="bi bi-check-circle me-2"></i>
                                    В пункте выдачи - завтра, бесплатно
                                </li>
                                <li class="delivery-item-oneone">
                                    <button class="btn-try-before-buy-oneone">
                                        <i class="bi bi-arrow-repeat me-2"></i>Примерка перед покупкой
                                    </button>
                                </li>
                            </ul>
                        </div>

                        <!-- Дополнительная информация -->
                        <div class="product-extra-info-oneone">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="extra-info-item">
                                        <i class="bi bi-arrow-counterclockwise me-2"></i>
                                        <span>Возврат в течение 14 дней</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="extra-info-item">
                                        <i class="bi bi-shield-check me-2"></i>
                                        <span>Гарантия качества</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="extra-info-item">
                                        <i class="bi bi-credit-card me-2"></i>
                                        <span>Оплата при получении</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="extra-info-item">
                                        <i class="bi bi-chat-dots me-2"></i>
                                        <span>Консультация стилиста</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Информация о товаре под фотографиями -->
            <div class="row mt-5">
                <div class="col-12">
                    <!-- Подробное описание -->
                    <div class="product-full-description-oneone mb-5">
                        <h2 class="description-section-title mb-4">О товаре</h2>
                        <div class="description-content">
                            @if($product->description)
                                <div class="description-text mb-4">
                                    {!! nl2br(e($product->description)) !!}
                                </div>
                            @endif
                            
                            <!-- Характеристики -->
                            <div class="product-specs-grid">
                                <div class="row">
                                    @if($product->material)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Материал</div>
                                            <div class="spec-value">{{ $product->material_translated ?? $product->material }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->composition)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Состав</div>
                                            <div class="spec-value">{{ $product->composition }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->lining_material)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Подкладка</div>
                                            <div class="spec-value">{{ $product->lining_material }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->country)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Страна</div>
                                            <div class="spec-value">{{ $product->country }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->season)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Сезон</div>
                                            <div class="spec-value">{{ $product->season }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->style)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Стиль</div>
                                            <div class="spec-value">{{ $product->style }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->product_type)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Тип</div>
                                            <div class="spec-value">{{ $product->product_type_translated ?? $product->product_type }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->fit)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Крой</div>
                                            <div class="spec-value">{{ $product->fit }}</div>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    @if($product->length)
                                    <div class="col-md-6 col-lg-4">
                                        <div class="spec-card">
                                            <div class="spec-label">Длина</div>
                                            <div class="spec-value">{{ $product->length }}</div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                        <!-- Отзывы -->
                        <div class="product-reviews-section-oneone mb-5">
                            <h2 class="reviews-section-title mb-4">Отзывы</h2>
                            <hr class="mb-5">

                            @if($reviewsCount > 0)
                                <div class="reviews-container">
                                    <div class="reviews-header-oneone d-flex justify-content-between align-items-center mb-4">
                                        <div>
                                            <span class="review-count-text">{{ $reviewsCount }} отзывов</span>
                                            @php
                                                $averageRating = $reviews->avg('rating') ?? 0;
                                            @endphp
                                            <div class="average-rating mt-2">
                                                <div class="rating-stars d-inline-block">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <i class="bi bi-star{{ $i <= floor($averageRating) ? '-fill' : ($i <= $averageRating ? '-half' : '') }}"></i>
                                                    @endfor
                                                </div>
                                                <span class="rating-value ms-2">{{ number_format($averageRating, 1) }}</span>
                                            </div>
                                        </div>
                                        
                                        @if($reviewsCount > 2)
                                            <button class="btn-oneone btn-oneone--outline toggle-reviews-btn" id="toggleReviewsBtn">
                                                <span class="btn-oneone-text">Показать все</span>
                                                <span class="btn-oneone-icon">
                                                    <i class="bi bi-chevron-down" id="toggleReviewsIcon"></i>
                                                </span>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="reviews-list-oneone" id="reviewsList">
                                        @foreach($reviews as $index => $review)
                                            <div class="review-item-oneone {{ $index >= 2 ? 'hidden-review' : '' }}" 
                                                data-review-id="{{ $review->id }}">
                                                <div class="review-header-oneone mb-2">
                                                    <div>
                                                        <h4 class="review-author-oneone mb-1">{{ $review->user->name ?? 'Аноним' }}</h4>
                                                        <div class="review-rating-oneone mb-2">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="bi bi-star{{ $i <= ($review->rating ?? 5) ? '-fill' : '' }}"></i>
                                                            @endfor
                                                            <span class="rating-value ms-2">{{ $review->rating ?? 5 }}/5</span>
                                                        </div>
                                                    </div>
                                                    <div class="review-date-oneone text-muted">
                                                        {{ $review->created_at ? $review->created_at->format('d.m.Y') : '' }}
                                                    </div>
                                                </div>
                                                <div class="review-text-oneone mb-2">{{ $review->comment ?? '' }}</div>
                                                
                                                @if($review->is_approved === false)
                                                    <div class="review-status-badge">
                                                        <span class="badge badge-warning">На модерации</span>
                                                    </div>
                                                @endif
                                            </div>
                                            @if($index < $reviewsCount - 1)
                                                <hr class="review-divider {{ $index >= 1 ? 'hidden-review-divider' : '' }}">
                                            @endif
                                        @endforeach
                                    </div>
                                    
                                    <!-- Кнопка внизу для мобильных устройств -->
                                    @if($reviewsCount > 2)
                                        <div class="text-center d-block d-md-none mt-4">
                                            <button class="btn-oneone btn-oneone--outline toggle-reviews-btn-mobile" id="toggleReviewsBtnMobile">
                                                <span class="btn-oneone-text">Показать все отзывы</span>
                                                <span class="btn-oneone-icon">
                                                    <i class="bi bi-chevron-down" id="toggleReviewsIconMobile"></i>
                                                </span>
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            @else
                                <div class="no-reviews text-center py-5">
                                    <div class="no-reviews-icon mb-3">
                                        <i class="bi bi-chat-dots" style="font-size: 3rem; color: #ddd;"></i>
                                    </div>
                                    <div class="no-reviews-text" style="color: #999;">
                                        Пока нет отзывов. Будьте первым!
                                    </div>
                                    @auth
                                        <div class="mt-4">
                                            <button class="btn-oneone btn-oneone--outline" data-bs-toggle="modal" data-bs-target="#addReviewModal">
                                                <i class="bi bi-pencil-square me-2"></i>Написать отзыв
                                            </button>
                                        </div>
                                    @endauth
                                </div>
                            @endif
                        </div>

<!-- ==================== -->
<!-- ПОХОЖИЕ ТОВАРЫ -->
<!-- ==================== -->
 <!-- ОТЛАДКА -->
<div style="display: none; position: fixed; top: 10px; right: 10px; background: white; padding: 10px; border: 1px solid red; z-index: 10000;">
    <strong>Debug Related Products:</strong><br>
    isset: {{ isset($relatedProducts) ? 'YES' : 'NO' }}<br>
    count: {{ isset($relatedProducts) ? $relatedProducts->count() : 0 }}<br>
    <!-- Показываем ID товаров для проверки -->
    @if(isset($relatedProducts) && $relatedProducts->count() > 0)
        Products: 
        @foreach($relatedProducts as $rp)
            {{ $rp->id }} - {{ $rp->title }}<br>
        @endforeach
    @endif
</div> 
@if(isset($relatedProducts) && $relatedProducts->count() > 0)
<div class="similar-products-section-oneone mt-5 pt-5 border-top">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <span class="section-badge">ВЫБОР ПОКУПАТЕЛЕЙ</span>
            <h2 class="section-title mt-3">ПОХОЖИЕ ТОВАРЫ</h2>
            <p class="section-subtitle">Вам также может понравиться</p>
        </div>
    </div>
    
    <div class="row g-4">
        @foreach($relatedProducts as $index => $relatedProduct)
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="product-card-home">
                <a href="{{ route('products.show', $relatedProduct->slug ?? $relatedProduct->id) }}" 
                   class="product-card-home__image-link">
                    <div class="product-card-home__image-wrapper">
                        @php
                            // ИСПРАВЛЕННАЯ ЛОГИКА ПОЛУЧЕНИЯ ИЗОБРАЖЕНИЯ:
                            // 1. Проверяем основное поле image
                            $relatedImageUrl = $relatedProduct->image ? asset($relatedProduct->image) : null;
                            
                            // 2. Если нет, проверяем связанные изображения (images загружены через with)
                            if (!$relatedImageUrl && $relatedProduct->images && $relatedProduct->images->isNotEmpty()) {
                                $firstImage = $relatedProduct->images->first();
                                $relatedImageUrl = $firstImage->image_path ?? $firstImage->url ?? $firstImage->path ?? null;
                                
                                // Преобразуем относительный путь в абсолютный
                                if ($relatedImageUrl && !str_starts_with($relatedImageUrl, 'http')) {
                                    $relatedImageUrl = asset($relatedImageUrl);
                                }
                            }
                        @endphp
                        
                        @if($relatedImageUrl)
                        <img src="{{ $relatedImageUrl }}" 
                             alt="{{ $relatedProduct->title }}" 
                             class="product-card-home__image">
                        @else
                        <div class="product-card-home__image-placeholder">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300">
                                <rect width="100%" height="100%" fill="#fafafa"/>
                            </svg>
                        </div>
                        @endif
                    </div>
                </a>
                
                <div class="product-card-home__info">
                    <h3 class="product-card-home__title">
                        <a href="{{ route('products.show', $relatedProduct->slug ?? $relatedProduct->id) }}" 
                           class="product-card-home__title-link">
                            {{ $relatedProduct->getCleanTitle() ?? $relatedProduct->title }}
                        </a>
                    </h3>
                    
                    <!-- Цена -->
                    <div class="product-card-home__price {{ $relatedProduct->old_price && $relatedProduct->old_price > $relatedProduct->price ? 'product-card-home__price--has-discount' : '' }}">
                        @if($relatedProduct->old_price && $relatedProduct->old_price > $relatedProduct->price)
                        <span class="product-card-home__price-old">
                            {{ number_format($relatedProduct->old_price, 0, ',', ' ') }} ₽
                        </span>
                        @endif
                        <span class="product-card-home__price-current">
                            {{ number_format($relatedProduct->price, 0, ',', ' ') }} ₽
                        </span>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>



<style>
/* ==================== */
/* ШРИФТЫ */
/* ==================== */
@import url('https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&display=swap');

/* ==================== */
/* СТИЛИ КАРТОЧЕК ТОВАРОВ (ТОЧНО КАК НА ГЛАВНОЙ) */
/* ==================== */

.similar-products-section-oneone {
    padding-top: 60px;
    margin-top: 60px;
    border-top: 1px solid #eee;
}

/* Бейдж секции */
.section-badge {
    display: inline-block;
    padding: 8px 20px;
    background: rgba(17, 17, 17, 0.08);
    color: #111;
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 0.875rem;
    font-weight: 600;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 4px;
    margin-bottom: 10px;
}

/* Заголовок секции */
.section-title {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 2.2rem;
    font-weight: 700;
    color: #111;
    margin-bottom: 0.5rem;
    letter-spacing: -0.5px;
}

/* Подзаголовок секции */
.section-subtitle {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-weight: 300;
    color: #999;
    font-size: 1.1rem;
    margin-bottom: 2.5rem;
}

/* КАРТОЧКА ТОВАРА */
.product-card-home {
    background: #fff;
    border: 1px solid #eee;
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

.product-card-home:hover {
    transform: translateY(-8px);
    box-shadow: 0 20px 40px rgba(0, 0, 0, 0.08);
    border-color: transparent;
}

.product-card-home__image-wrapper {
    flex: 1;
    background-color: #f8f8f8;
    padding: 30px;
    display: flex;
    align-items: center;
    justify-content: center;
    min-height: 340px;
    position: relative;
    overflow: hidden;
}

.product-card-home__image {
    width: 100%;
    height: auto;
    max-height: 300px;
    object-fit: contain;
    transition: transform 0.8s ease;
    mix-blend-mode: multiply;
    filter: brightness(0.98);
}

.product-card-home:hover .product-card-home__image {
    transform: scale(1.05);
    filter: brightness(1);
}

.product-card-home__info {
    padding: 22px;
    background: #fff;
    border-top: 1px solid rgba(234, 234, 234, 0.8);
}

/* НАЗВАНИЕ ТОВАРА */
.product-card-home__title {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 13.6px; /* точный размер как на скриншоте */
    font-weight: 400;
    line-height: 1.4;
    color: #444; /* точный цвет как на скриншоте */
    margin-bottom: 8px;
    min-height: 38px; /* для 2 строк */
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.product-card-home__title-link {
    color: inherit;
    text-decoration: none;
    transition: color 0.2s ease;
}

.product-card-home__title-link:hover {
    color: #111;
    text-decoration: underline;
    text-decoration-thickness: 1px;
    text-underline-offset: 2px;
}

/* ЦЕНА */
.product-card-home__price {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}

.product-card-home__price-current {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 18.4px; /* точный размер как на скриншоте */
    font-weight: 600;
    color: #111; /* точный цвет как на скриншоте */
    letter-spacing: -0.5px;
}

.product-card-home__price-old {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
    font-size: 13px;
    color: #999;
    text-decoration: line-through;
    opacity: 0.7;
}

/* Скидочная цена */
.product-card-home__price--has-discount .product-card-home__price-current {
    color: #C41E3A;
    font-weight: 700;
}

/* Скрываем бейджи на карточках */
.product-card-home__badge,
.product-card-home__badge--new,
.product-card-home__badge--sale,
.product-card-home__badge--popular {
    display: none !important;
}

/* Плейсхолдер для изображения */
.image-placeholder {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f8f8f8;
}

.image-placeholder svg {
    width: 100%;
    height: 100%;
}

.image-placeholder text {
    font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
}

/* Адаптивность */
@media (max-width: 1200px) {
    .product-card-home__image-wrapper {
        min-height: 300px;
        padding: 25px;
    }
    
    .product-card-home__image {
        max-height: 270px;
    }
    
    .product-card-home__title {
        font-size: 12.6px;
    }
    
    .product-card-home__price-current {
        font-size: 17px;
    }
}

@media (max-width: 992px) {
    .similar-products-section-oneone {
        padding-top: 40px;
        margin-top: 40px;
    }
    
    .section-title {
        font-size: 2rem;
    }
    
    .section-subtitle {
        font-size: 1rem;
    }
    
    .product-card-home__image-wrapper {
        min-height: 260px;
        padding: 22px;
    }
    
    .product-card-home__image {
        max-height: 220px;
    }
    
    .product-card-home__info {
        padding: 18px;
    }
    
    .product-card-home__title {
        font-size: 12px;
        min-height: 36px;
    }
}

@media (max-width: 768px) {
    .product-card-home__image-wrapper {
        min-height: 220px;
        padding: 20px;
    }
    
    .product-card-home__image {
        max-height: 190px;
    }
    
    .product-card-home__info {
        padding: 15px 18px 16px;
    }
    
    .product-card-home__title {
        font-size: 11.5px;
        min-height: 32px;
    }
    
    .product-card-home__price-current {
        font-size: 16px;
    }
    
    .product-card-home:hover {
        transform: translateY(-6px);
    }
}

@media (max-width: 576px) {
    .product-card-home__image-wrapper {
        min-height: 300px;
        padding: 25px;
    }
    
    .product-card-home__image {
        max-height: 260px;
    }
}
</style>
@endif


<script>
// Функция для добавления похожего товара в корзину
function addSimilarToCart(productId) {
    // AJAX запрос для добавления в корзину
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Показываем уведомление об успешном добавлении
            showNotification('Товар добавлен в корзину!', 'success');
            
            // Обновляем счетчик корзины в шапке (если есть)
            const cartCountElement = document.querySelector('.cart-count');
            if (cartCountElement && data.cart_count !== undefined) {
                cartCountElement.textContent = data.cart_count;
            }
        } else {
            showNotification('Ошибка: ' + data.message, 'error');
        }
    })
    .catch(error => {
        console.error('Error adding to cart:', error);
        showNotification('Произошла ошибка при добавлении в корзину', 'error');
    });
}

// Вспомогательная функция для показа уведомлений
function showNotification(message, type = 'info') {
    // Создаем элемент уведомления
    const notification = document.createElement('div');
    notification.className = `notification notification-${type}`;
    notification.innerHTML = `
        <div style="
            position: fixed;
            top: 20px;
            right: 20px;
            background: ${type === 'success' ? '#4CAF50' : type === 'error' ? '#f44336' : '#2196F3'};
            color: white;
            padding: 15px 25px;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 10000;
            animation: slideIn 0.3s ease;
        ">
            <i class="bi ${type === 'success' ? 'bi-check-circle' : type === 'error' ? 'bi-x-circle' : 'bi-info-circle'} me-2"></i>
            ${message}
        </div>
    `;
    
    document.body.appendChild(notification);
    
    // Удаляем уведомление через 3 секунды
    setTimeout(() => {
        notification.style.animation = 'slideOut 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Стили для анимации уведомлений
const style = document.createElement('style');
style.textContent = `
    @keyframes slideIn {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }
    
    @keyframes slideOut {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);
</script>

    <!-- Модальное окно выбора размера -->
    <div class="modal fade" id="sizeSelectionModal" tabindex="-1" aria-labelledby="sizeSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title fs-4 fw-light" id="sizeSelectionModalLabel">Выберите размер</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-4">
                        <h6 class="mb-3 fw-normal text-muted">Таблица размеров</h6>
                        <div class="table-responsive">
                            <table class="table table-borderless size-guide-table mb-4">
                                <thead>
                                    <tr class="border-bottom">
                                        <th class="text-start fw-normal">Размер</th>
                                        <th class="text-start fw-normal">Грудь (см)</th>
                                        <th class="text-start fw-normal">Талия (см)</th>
                                        <th class="text-start fw-normal">Бедра (см)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr class="border-bottom"><td>XS</td><td>82-84</td><td>60-62</td><td>86-88</td></tr>
                                    <tr class="border-bottom"><td>S</td><td>86-88</td><td>64-66</td><td>90-92</td></tr>
                                    <tr class="border-bottom"><td>M</td><td>90-92</td><td>68-70</td><td>94-96</td></tr>
                                    <tr class="border-bottom"><td>L</td><td>94-96</td><td>72-74</td><td>98-100</td></tr>
                                    <tr class="border-bottom"><td>XL</td><td>98-100</td><td>76-78</td><td>102-104</td></tr>
                                    <tr class="border-bottom"><td>XXL</td><td>102-104</td><td>80-82</td><td>106-108</td></tr>
                                </tbody>
                            </table>
                        </div>
                        
                        <div class="mt-4 pt-2 border-top">
                            <p class="small text-muted mb-2">Как правильно измерить:</p>
                            <ul class="small text-muted ps-3 mb-0">
                                <li>Грудь: измерьте по самой выступающей точке</li>
                                <li>Талия: на 2 см выше пупка</li>
                                <li>Бедра: по самой широкой части</li>
                            </ul>
                        </div>
                    </div>
                    
                    <div class="mt-4 pt-4 border-top">
                        <h6 class="mb-3 fw-normal text-muted">Доступные размеры</h6>
                        <div class="available-sizes-container">
                            <div class="sizes-list-modal">
                                @php
                                    $productSizes = $availableSizes ?? collect();
                                    if ($productSizes->isEmpty() && isset($product->sizes)) {
                                        $productSizes = $product->sizes;
                                    }
                                @endphp
                                
                                @foreach($productSizes as $size)
                                    @php
                                        $sizeStock = $size->pivot->stock ?? $size->stock ?? 0;
                                        $isAvailable = $sizeStock > 0;
                                        $isLowStock = $isAvailable && $sizeStock <= 3;
                                    @endphp
                                    <button type="button" 
                                            class="size-option-modal {{ !$isAvailable ? 'disabled' : '' }} {{ $isLowStock ? 'low-stock' : '' }}"
                                            data-size-id="{{ $size->id }}"
                                            data-size-name="{{ $size->name }}"
                                            data-size-stock="{{ $sizeStock }}"
                                            onclick="selectSize('{{ $size->id }}', '{{ $size->name }}', {{ $sizeStock }})">
                                        <span class="size-value-modal">{{ $size->name }}</span>
                                        <span class="size-info-modal">
                                            @if($isAvailable)
                                                @if($isLowStock)
                                                    <small class="text-warning d-block">Мало на складе</small>
                                                @else
                                                    <small class="text-success d-block">В наличии</small>
                                                @endif
                                            @else
                                                <small class="text-danger d-block">Нет в наличии</small>
                                            @endif
                                        </span>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                        
                        <div class="selected-size-info mt-4 p-3 bg-light rounded d-none" id="selectedSizeInfo">
                            <h6>Выбранный размер: <span id="currentSelectedSize" class="fw-bold"></span></h6>
                            <p class="mb-1" id="sizeStockInfo"></p>
                            <small class="text-muted" id="sizeHelpText"></small>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn-oneone btn-oneone--outline" data-bs-dismiss="modal">Отмена</button>
                    <button type="button" class="btn-oneone btn-oneone--primary" id="confirmSizeBtn" onclick="confirmSizeSelection()" disabled>
                        Выбрать размер
                    </button>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Стили для страницы товара */
        .product-detail-page {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
            color: #111111;
        }

        /* Хлебные крошки */
        .breadcrumb-nav .breadcrumb {
            background: transparent;
            padding: 0;
            font-size: 0.875rem;
        }

        .breadcrumb-item a {
            color: #666;
            text-decoration: none;
            transition: color 0.2s;
        }

        .breadcrumb-item a:hover {
            color: #111;
        }

        .breadcrumb-item.active {
            color: #111;
        }

        .breadcrumb-item + .breadcrumb-item::before {
            color: #999;
        }

        /* Изображения товара */
        .product-image-main-oneone {
            position: relative;
            background: #f8f8f8;
            border-radius: 8px;
            overflow: hidden;
        }

        .product-image-container-oneone {
            position: relative;
            padding-top: 100%;
            overflow: hidden;
        }

        .product-main-image-oneone {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: contain;
            transition: opacity 0.3s ease;
        }

        .no-image-placeholder {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
        }

        /* Миниатюры вертикальные */
        .product-thumbnails-vertical {
            height: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .thumbnail-item-vertical {
            width: 70px;
            height: 70px;
            border: 2px solid transparent;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
        }

        .thumbnail-item-vertical.active {
            border-color: #111;
        }

        .thumbnail-item-vertical:hover {
            border-color: #666;
        }

        .thumbnail-item-vertical img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Миниатюры горизонтальные */
        .product-thumbnails-horizontal {
            margin-top: 20px;
        }

        .thumbnail-item-horizontal {
            border: 2px solid transparent;
            border-radius: 4px;
            overflow: hidden;
            cursor: pointer;
            transition: all 0.2s ease;
            background: #fff;
            height: 80px;
        }

        .thumbnail-item-horizontal.active {
            border-color: #111;
        }

        .thumbnail-item-horizontal:hover {
            border-color: #666;
        }

        .thumbnail-item-horizontal img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        /* Информация о товаре */
        .product-info-oneone {
            padding-left: 20px;
        }

        @media (max-width: 991px) {
            .product-info-oneone {
                padding-left: 0;
                margin-top: 30px;
            }
        }

        /* Бейджи */
        .product-badges-oneone {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
        }

        .badge-oneone {
            display: inline-block;
            padding: 6px 12px;
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            border-radius: 2px;
            line-height: 1;
        }

        .badge-oneone--new {
            background: #111;
            color: white;
        }

        .badge-oneone--sale {
            background: #C41E3A;
            color: white;
        }

        .badge-oneone--popular {
            background: #2C2C2C;
            color: white;
        }

        .badge-oneone--bestseller {
            background: #D4AF37;
            color: white;
        }

        /* Заголовок */
        .product-title-oneone {
            font-size: 1.5rem;
            font-weight: 600;
            line-height: 1.2;
            margin-bottom: 15px;
        }

/* Цвета */
.product-colors-oneone .colors-label {
    font-weight: 500;
    color: #111;
}

.colors-list {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.color-option {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 6px;
    background: none;
    border: none;
    cursor: pointer;
    padding: 4px;
    transition: all 0.25s ease;
    position: relative;
    border-radius: 6px;
}

.color-option:hover {
    transform: translateY(-2px);
    background: rgba(0, 0, 0, 0.02);
}

.color-option:active {
    transform: scale(0.95);
}

.color-swatch {
    width: 34px;
    height: 34px;
    border-radius: 6px;
    border: 2px solid transparent;
    transition: all 0.3s ease;
    position: relative;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
}

.color-option:hover .color-swatch {
    border-color: rgba(0, 0, 0, 0.15);
    transform: scale(1.05);
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

.color-option.active .color-swatch {
    border-color: #111 !important;
    box-shadow: 0 0 0 1px #fff, 0 0 0 3px #111;
    transform: scale(1.08);
}

.color-swatch-text {
    display: flex;
    align-items: center;
    justify-content: center;
    background: #f5f5f5;
    color: #666;
    font-size: 0.75rem;
    font-weight: 600;
    border-radius: 6px;
}

.color-name {
    font-size: 0.7rem;
    color: #888;
    font-weight: 500;
    transition: color 0.2s ease;
}

.color-option.active .color-name {
    color: #111;
    font-weight: 600;
}

.color-option:hover .color-name {
    color: #333;
}

/* Убираем черную полоску снизу */
.color-option.active::after {
    display: none !important;
}

        /* Артикул */
        .product-sku-oneone {
            font-size: 0.875rem;
            color: #666;
        }

        .sku-value {
            color: #111;
            font-weight: 500;
        }

        /* Рейтинг */
        .product-rating-oneone .rating-stars {
            display: flex;
            align-items: center;
        }

        .rating-stars i {
            color: #FFD700;
            font-size: 1rem;
            margin-right: 2px;
        }

        .rating-value {
            font-weight: 600;
            color: #111;
        }

        .rating-count {
            color: #666;
            font-size: 0.875rem;
        }

        /* Цена */
        .product-price-oneone {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .product-price-current-oneone {
            font-size: 1.5rem;
            font-weight: 700;
            color: #111;
        }

        .product-price-old-oneone {
            font-size: 1rem;
            color: #999;
            text-decoration: line-through;
        }

        /* Выбор размера */
        .product-sizes-selection .sizes-selection-label {
            font-weight: 500;
            color: #111;
        }

        .btn-size-selector {
            padding: 12px 16px;
            border: 1px solid #ddd;
            background: white;
            color: #111;
            font-size: 1rem;
            text-align: left;
            transition: all 0.2s;
        }

        .btn-size-selector:hover {
            border-color: #111;
            background: #f8f8f8;
        }

        /* Количество */
        .product-quantity-oneone .quantity-label {
            font-weight: 500;
            color: #111;
        }

        .quantity-selector {
            display: flex;
            align-items: center;
            max-width: 140px;
            border: 1px solid #ddd;
            border-radius: 4px;
            overflow: hidden;
        }

        .quantity-btn {
            width: 40px;
            height: 40px;
            border: none;
            background: #f8f8f8;
            color: #111;
            font-size: 1.25rem;
            cursor: pointer;
            transition: background 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .quantity-btn:hover {
            background: #e8e8e8;
        }

        .quantity-input {
            width: 60px;
            height: 40px;
            border: none;
            text-align: center;
            font-size: 1rem;
            color: #111;
            background: white;
        }

        .quantity-input:focus {
            outline: none;
        }

        .quantity-stock-info {
            font-size: 0.875rem;
        }

        /* Кнопки */
        .wishlist-button-oneone,
        .add-to-cart-section-oneone {
            margin-bottom: 20px;
        }

        .btn-oneone {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 14px 24px;
            font-size: 1rem;
            font-weight: 500;
            border: 1px solid transparent;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.2s ease;
            text-decoration: none;
        }

        .btn-oneone--primary {
            background: #111;
            color: white;
            border-color: #111;
        }

        .btn-oneone--primary:hover {
            background: #333;
            border-color: #333;
        }

        .btn-oneone--outline {
            background: white;
            color: #111;
            border-color: #ddd;
        }

        .btn-oneone--outline:hover {
            background: #f8f8f8;
            border-color: #111;
        }

        .btn-oneone:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        /* Доставка */
        .delivery-info-oneone {
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
        }

        .delivery-title-oneone {
            font-size: 1.125rem;
            font-weight: 500;
            color: #111;
        }

        .delivery-list-oneone .delivery-item-oneone {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #666;
        }

        .btn-try-before-buy-oneone {
            background: none;
            border: none;
            color: #111;
            font-weight: 500;
            cursor: pointer;
            padding: 0;
            transition: color 0.2s;
        }

        .btn-try-before-buy-oneone:hover {
            color: #666;
        }

        /* Дополнительная информация */
        .product-extra-info-oneone {
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .extra-info-item {
            display: flex;
            align-items: center;
            font-size: 0.875rem;
            color: #666;
        }

        /* Описание товара */
        .product-full-description-oneone .description-section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .description-content .description-text {
            font-size: 1rem;
            line-height: 1.6;
            color: #444;
        }

        .product-specs-grid {
            margin-top: 30px;
        }

        .spec-card {
            padding: 20px;
            background: #f8f8f8;
            border-radius: 8px;
            height: 100%;
        }

        .spec-label {
            font-size: 0.875rem;
            color: #666;
            margin-bottom: 8px;
        }

        .spec-value {
            font-size: 1rem;
            color: #111;
            font-weight: 500;
        }

        /* Отзывы */
        .product-reviews-section-oneone .reviews-section-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .review-count-text {
            font-size: 1rem;
            color: #666;
        }

        .review-item-oneone {
            padding: 20px 0;
        }

        .review-header-oneone {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .review-author-oneone {
            font-size: 1rem;
            font-weight: 600;
            margin: 0;
            color: #111;
        }

        .review-rating-oneone i {
            color: #FFD700;
            font-size: 0.875rem;
            margin-right: 2px;
        }

        .review-text-oneone {
            font-size: 0.875rem;
            line-height: 1.5;
            color: #444;
            margin: 10px 0;
        }

        .review-date-oneone {
            font-size: 0.75rem;
        }

        .review-divider {
            margin: 0;
            border-color: #eee;
        }

        .no-reviews {
            padding: 40px 0;
        }

        /* Похожие товары */
        .section-title-oneone {
            font-size: 1.5rem;
            font-weight: 600;
            color: #111;
        }

        /* Модальное окно размеров */
        .size-option-modal {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 100px;
            height: 80px;
            padding: 10px;
            border: 2px solid #eee;
            background: white;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.2s ease;
            margin: 5px;
        }

        .size-option-modal:hover {
            border-color: #ddd;
            background: #f8f8f8;
        }

        .size-option-modal.active {
            border-color: #111;
            background: #111;
            color: white;
        }

        .size-option-modal.disabled {
            opacity: 0.5;
            cursor: not-allowed;
            border-color: #eee;
        }

        .size-option-modal.low-stock {
            border-color: #ffc107;
        }

        .size-value-modal {
            font-size: 1.125rem;
            font-weight: 600;
        }

        .size-info-modal {
            font-size: 0.75rem;
            margin-top: 4px;
        }

        .sizes-list-modal {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .size-guide-table {
            width: 100%;
        }

        .size-guide-table th,
        .size-guide-table td {
            padding: 8px 16px;
            text-align: left;
            border-bottom: 1px solid #eee;
        }

        .size-guide-table th {
            font-weight: 500;
            color: #666;
        }

        .selected-size-info {
            background: #f8f8f8;
            border-radius: 8px;
        }
                /* Отзывы */
        .hidden-review {
            display: none !important;
        }

        .hidden-review-divider {
            display: none !important;
        }

        .review-item-oneone {
            transition: opacity 0.3s ease;
        }

        .review-item-oneone.show {
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .average-rating {
            font-size: 0.9rem;
        }

        .average-rating .rating-stars i {
            font-size: 0.875rem;
            color: #FFD700;
        }

        .review-status-badge {
            margin-top: 10px;
        }

        .review-status-badge .badge {
            font-size: 0.7rem;
            padding: 3px 8px;
        }

        /* Анимация для кнопки */
        .btn-oneone--outline .bi-chevron-down {
            transition: transform 0.3s ease;
        }

        .btn-oneone--outline.show-all .bi-chevron-down {
            transform: rotate(180deg);
        }
        /* ==================== */
        /* ПОХОЖИЕ ТОВАРЫ */
        /* ==================== */

        .similar-products-section-oneone {
            padding-top: 60px;
            margin-top: 60px;
            border-top: 1px solid #eee;
        }

        .similar-section-title {
            font-size: 2rem;
            font-weight: 700;
            color: var(--oneone-black);
            margin-bottom: 0.5rem;
            letter-spacing: -0.5px;
        }

        .similar-section-subtitle {
            font-size: 1.1rem;
            color: var(--oneone-gray-medium);
            max-width: 600px;
        }

        /* Рейтинг для похожих товаров */
        .similar-rating {
            display: flex;
            align-items: center;
            gap: 4px;
            margin-bottom: 8px;
        }

        .similar-rating-stars {
            display: inline-flex;
            align-items: center;
            gap: 1px;
        }

        .similar-rating-stars i {
            font-size: 0.8rem;
            color: #FFD700;
        }

        .similar-rating-value {
            font-size: 0.85rem;
            font-weight: 600;
            color: #111;
        }

        .similar-rating-count {
            font-size: 0.8rem;
            color: #666;
        }

        /* Кнопка "В корзину" для похожих товаров */
        .similar-add-to-cart-btn {
            width: 100%;
            height: 40px;
            background: var(--oneone-black);
            color: var(--oneone-white);
            border: 1px solid var(--oneone-black);
            font-weight: 500;
            font-size: 0.85rem;
            letter-spacing: 0.3px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s ease;
            cursor: pointer;
            border-radius: 4px;
        }

        .similar-add-to-cart-btn:hover:not(:disabled) {
            background: #333;
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }

        .similar-add-to-cart-btn:disabled {
            background: #f5f5f5;
            color: #999;
            border-color: #eee;
            cursor: not-allowed;
        }

        /* Для небольших экранов */
        @media (max-width: 768px) {
            .similar-products-section-oneone {
                padding-top: 40px;
                margin-top: 40px;
            }
            
            .similar-section-title {
                font-size: 1.5rem;
            }
            
            .similar-section-subtitle {
                font-size: 1rem;
            }
        }
       
        /* ЭКСТРЕННЫЙ ФИКС МОДАЛЬНОГО ОКНА РАЗМЕРОВ */
        .modal-open .modal {
            overflow-y: auto !important;
        }

        #sizeSelectionModal {
            background: rgba(0, 0, 0, 0.5) !important;
        }

        #sizeSelectionModal .modal-dialog,
        #sizeSelectionModal .modal-content,
        #sizeSelectionModal .modal-body,
        #sizeSelectionModal .available-sizes-container,
        #sizeSelectionModal .sizes-list-modal {
            pointer-events: auto !important;
            opacity: 1 !important;
            visibility: visible !important;
        }

        #sizeSelectionModal .size-option-modal {
            pointer-events: auto !important;
            cursor: pointer !important;
            opacity: 1 !important;
            z-index: 9999 !important;
        }

        #sizeSelectionModal .size-option-modal:hover {
            border-color: #111 !important;
            background: #f8f8f8 !important;
        }
    </style>

    <script>
    // Функция для сохранения выбранного цвета
    function saveSelectedColor(productId, colorId) {
        const key = `selected_color_${productId}`;
        localStorage.setItem(key, colorId);
    }

    // Функция для получения сохраненного цвета
    function getSelectedColor(productId) {
        const key = `selected_color_${productId}`;
        return localStorage.getItem(key);
    }

    function applySavedColor() {
    const productId = {{ $product->id }};
    
    // 🔥 Сначала проверяем URL-параметр (приоритетнее localStorage)
    const urlParams = new URLSearchParams(window.location.search);
    const colorIdFromUrl = urlParams.get('color');
    
    // Если есть URL-параметр, используем его и НЕ трогаем localStorage
    if (colorIdFromUrl) {
        const colorOption = document.querySelector(`.color-option[data-color-id="${colorIdFromUrl}"]`);
        if (colorOption && !colorOption.classList.contains('active')) {
            setTimeout(() => colorOption.click(), 300);
        }
        return; // Выходим, не проверяя localStorage
    }
    
    // Если URL-параметра нет, проверяем localStorage
    const savedColorId = getSelectedColor(productId);
    if (savedColorId) {
        const colorOption = document.querySelector(`.color-option[data-color-id="${savedColorId}"]`);
        if (colorOption && !colorOption.classList.contains('active')) {
            setTimeout(() => colorOption.click(), 300);
        }
    }
}

    // Функция для обновления URL с параметром цвета
    function updateUrlForColor(colorId) {
        const url = new URL(window.location.href);
        url.searchParams.set('color', colorId);
        window.history.replaceState({}, '', url);
    }

    // Функция для смены главного изображения
    function changeMainImage(imageUrl, element) {
        // Обновляем главное изображение
        const mainImage = document.getElementById('mainProductImageOneone');
        mainImage.src = imageUrl;
        
        // Обновляем активный класс у миниатюр
        const thumbnails = document.querySelectorAll('.thumbnail-item-vertical, .thumbnail-item-horizontal');
        thumbnails.forEach(thumb => thumb.classList.remove('active'));
        element.classList.add('active');
    }

    // Функция для изменения количества
    function changeQuantity(change) {
        const quantityInput = document.getElementById('productQuantity');
        let currentQuantity = parseInt(quantityInput.value);
        const maxQuantity = parseInt(quantityInput.max);
        const minQuantity = parseInt(quantityInput.min);
        
        let newQuantity = currentQuantity + change;
        
        if (newQuantity >= minQuantity && newQuantity <= maxQuantity) {
            quantityInput.value = newQuantity;
        }
    }

    // Функция для выбора размера
    function selectSize(sizeId, sizeName, stock) {
   
    document.getElementById('selectedSizeId').value = sizeId;
    document.getElementById('selectedSizeName').value = sizeName;
    
    // Обновляем текст на кнопке
    document.getElementById('selectedSizeText').textContent = sizeName + (stock > 0 ? '' : ' (Нет в наличии)');
    
    // Активируем кнопку подтверждения
    document.getElementById('confirmSizeBtn').disabled = stock <= 0;
    
    // Обновляем активный класс
    document.querySelectorAll('.size-option-modal').forEach(opt => opt.classList.remove('active'));
    const selectedOption = document.querySelector(`.size-option-modal[data-size-id="${sizeId}"]`);
    if (selectedOption) {
        selectedOption.classList.add('active');
    }
}

    // Функция для подтверждения выбора размера
        function confirmSizeSelection() {
        const sizeName = document.getElementById('selectedSizeName').value;
        if (sizeName) {
            // Закрываем модальное окно
            const modal = bootstrap.Modal.getInstance(document.getElementById('sizeSelectionModal'));
            modal.hide();
        }
    }

    // Функция для переключения отзывов
    function toggleReviews() {
        const reviewsList = document.getElementById('reviewsList');
        const toggleBtn = document.getElementById('toggleReviewsBtn');
        const toggleBtnMobile = document.getElementById('toggleReviewsBtnMobile');
        const toggleIcon = document.getElementById('toggleReviewsIcon');
        const toggleIconMobile = document.getElementById('toggleReviewsIconMobile');
        const hiddenReviews = document.querySelectorAll('.hidden-review');
        const hiddenDividers = document.querySelectorAll('.hidden-review-divider');
        
        if (hiddenReviews.length > 0) {
            // Показываем все отзывы
            hiddenReviews.forEach(review => {
                review.style.display = 'block';
                review.classList.remove('hidden-review');
                review.classList.add('show');
            });
            
            hiddenDividers.forEach(divider => {
                divider.style.display = 'block';
                divider.classList.remove('hidden-review-divider');
            });
            
            // Обновляем кнопки
            if (toggleBtn) {
                toggleBtn.querySelector('.btn-oneone-text').textContent = 'Скрыть';
                toggleBtn.classList.add('show-all');
            }
            
            if (toggleBtnMobile) {
                toggleBtnMobile.querySelector('.btn-oneone-text').textContent = 'Скрыть отзывы';
                toggleBtnMobile.classList.add('show-all');
            }
            
            if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            if (toggleIconMobile) toggleIconMobile.style.transform = 'rotate(180deg)';
            
        } else {
            // Скрываем отзывы, оставляем только первые 2
            const allReviews = document.querySelectorAll('.review-item-oneone');
            const allDividers = document.querySelectorAll('.review-divider');
            
            allReviews.forEach((review, index) => {
                if (index >= 2) {
                    review.style.display = 'none';
                    review.classList.add('hidden-review');
                    review.classList.remove('show');
                }
            });
            
            allDividers.forEach((divider, index) => {
                if (index >= 1) {
                    divider.style.display = 'none';
                    divider.classList.add('hidden-review-divider');
                }
            });
            
            // Обновляем кнопки
            if (toggleBtn) {
                toggleBtn.querySelector('.btn-oneone-text').textContent = 'Показать все';
                toggleBtn.classList.remove('show-all');
            }
            
            if (toggleBtnMobile) {
                toggleBtnMobile.querySelector('.btn-oneone-text').textContent = 'Показать все отзывы';
                toggleBtnMobile.classList.remove('show-all');
            }
            
            if (toggleIcon) toggleIcon.style.transform = 'rotate(0deg)';
            if (toggleIconMobile) toggleIconMobile.style.transform = 'rotate(0deg)';
        }
    }

    // Инициализация при загрузке страницы
    document.addEventListener('DOMContentLoaded', function() {
        // Обработка выбора цвета
        const colorOptions = document.querySelectorAll('.color-option');
        colorOptions.forEach(option => {
            option.addEventListener('click', function() {
                const colorId = this.getAttribute('data-color-id');
                const productId = {{ $product->id }};
                
                // Сохраняем выбранный цвет
                saveSelectedColor(productId, colorId);
                
                // Обновляем URL
                updateUrlForColor(colorId);
                
                // AJAX запрос для получения данных вариации
                fetch('/product/variant-data', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({
                        product_id: productId,
                        color_id: colorId
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Обновляем информацию о товаре
                        const variant = data.variant;
                        
                        // Обновляем цену
                        const priceElement = document.querySelector('.product-price-current-oneone');
                        if (priceElement) {
                            priceElement.textContent = variant.price_formatted;
                        }
                        
                        // Обновляем старую цену
                        const oldPriceElement = document.querySelector('.product-price-old-oneone');
                        if (variant.old_price_formatted) {
                            if (!oldPriceElement) {
                                const priceContainer = document.querySelector('.product-price-oneone');
                                priceContainer.innerHTML = `
                                    <div class="product-price-old-oneone">${variant.old_price_formatted}</div>
                                    <div class="product-price-current-oneone">${variant.price_formatted}</div>
                                `;
                            } else {
                                oldPriceElement.textContent = variant.old_price_formatted;
                            }
                        } else if (oldPriceElement) {
                            oldPriceElement.remove();
                        }
                        
                        // Обновляем наличие
                        const stockInfo = document.querySelector('.quantity-stock-info');
                        if (stockInfo) {
                            stockInfo.innerHTML = variant.in_stock
                                ? `<span class="text-success"><i class="bi bi-check-circle me-1"></i>В наличии: ${variant.stock} шт.</span>`
                                : `<span class="text-danger"><i class="bi bi-x-circle me-1"></i>Нет в наличии</span>`;
                        }
                        
                        // Обновляем кнопку "В корзину"
                        const addToCartBtn = document.getElementById('addToCartButton');
                        if (addToCartBtn) {
                            addToCartBtn.disabled = !variant.in_stock;
                            addToCartBtn.querySelector('.btn-oneone-text').textContent = 
                                variant.in_stock ? 'В корзину' : 'Нет в наличии';
                            addToCartBtn.setAttribute('data-variant-id', variant.id);
                            addToCartBtn.setAttribute('data-product-id', variant.id);
                        }
                        
                        // Обновляем изображения
                        if (data.images && data.images.length > 0) {
                            const mainImage = document.getElementById('mainProductImageOneone');
                            if (mainImage) {
                                mainImage.src = data.images[0].formatted_url;
                                mainImage.alt = data.product_title;
                            }
                            
                            // Обновляем миниатюры
                            updateThumbnails(data.images, data.product_title);
                        }
                        
                        // Обновляем активный класс цвета
                        colorOptions.forEach(opt => opt.classList.remove('active'));
                        this.classList.add('active');
                    }
                })
                .catch(error => {
                    console.error('Error fetching variant data:', error);
                });
            });
        });
        
        // Функция для обновления миниатюр
        function updateThumbnails(images, productTitle) {
            // Обновляем вертикальные миниатюры
            const verticalThumbnails = document.querySelector('.product-thumbnails-vertical');
            const horizontalThumbnails = document.querySelector('.product-thumbnails-horizontal .row');
            
            if (images.length > 1 && verticalThumbnails) {
                verticalThumbnails.innerHTML = '';
                images.forEach((image, index) => {
                    const thumbnail = document.createElement('div');
                    thumbnail.className = `thumbnail-item-vertical mb-3 ${index === 0 ? 'active' : ''}`;
                    thumbnail.innerHTML = `
                        <img src="${image.formatted_url}" 
                            alt="${productTitle}" 
                            class="img-fluid"
                            onclick="changeMainImage('${image.formatted_url}', this.parentElement)">
                    `;
                    verticalThumbnails.appendChild(thumbnail);
                });
            }
            
            if (images.length > 1 && horizontalThumbnails) {
                horizontalThumbnails.innerHTML = '';
                images.forEach((image, index) => {
                    const col = document.createElement('div');
                    col.className = 'col-4 col-sm-3';
                    col.innerHTML = `
                        <div class="thumbnail-item-horizontal ${index === 0 ? 'active' : ''}"
                            onclick="changeMainImage('${image.formatted_url}', this)">
                            <img src="${image.formatted_url}" 
                                alt="${productTitle}" 
                                class="img-fluid">
                        </div>
                    `;
                    horizontalThumbnails.appendChild(col);
                });
            }
        }
        
        // Инициализация Bootstrap модального окна
        const sizeModal = document.getElementById('sizeSelectionModal');
        if (sizeModal) {
            sizeModal.addEventListener('show.bs.modal', function() {
                // Сбрасываем выбор при открытии модального окна
                document.getElementById('selectedSizeInfo').classList.add('d-none');
                document.getElementById('confirmSizeBtn').disabled = true;
                
                const sizeOptions = document.querySelectorAll('.size-option-modal');
                sizeOptions.forEach(option => option.classList.remove('active'));
            });
        }
        
        
       // Обработка добавления в корзину
        const addToCartBtn = document.getElementById('addToCartButton');
        if (addToCartBtn) {
            addToCartBtn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                const sizeName = document.getElementById('selectedSizeName').value;
                const quantity = document.getElementById('productQuantity').value;
                
                // 🔥 ОТЛАДКА
                const activeColor = document.querySelector('.color-option.active');
                const colorId = activeColor ? activeColor.getAttribute('data-color-id') : null;
                
                console.log('=== ДОБАВЛЕНИЕ В КОРЗИНУ ===');
                console.log('productId:', productId);
                console.log('sizeName:', sizeName);
                console.log('quantity:', quantity);
                console.log('activeColor элемент:', activeColor);
                console.log('colorId:', colorId);
                
                // Проверяем выбран ли размер
                if (!sizeName || sizeName.trim() === '') {
                    alert('Выберите размер!');
                    const sizeModal = new bootstrap.Modal(document.getElementById('sizeSelectionModal'));
                    sizeModal.show();
                    return;
                }
                
                const requestData = {
                    product_id: productId,
                    quantity: parseInt(quantity),
                    size: sizeName,
                    color_id: colorId
                };
                
                console.log('Отправляемые данные:', JSON.stringify(requestData));
                
                // Показываем загрузку
                const originalText = this.innerHTML;
                this.disabled = true;
                this.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Добавляем...';
                
                fetch('/cart/add', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(requestData)
                })
                .then(response => response.json())
                .then(data => {
                    console.log('Ответ сервера:', data);
                    if (data.success) {
                        updateCartBadge(data.cart_count);
                        showToast(data.message || 'Товар добавлен в корзину!');
                    } else {
                        showToast(data.message || 'Ошибка', 'error');
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    showToast('Ошибка соединения', 'error');
                })
                .finally(() => {
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
            });
        }


        // Функция для показа уведомлений о корзине
        function showCartNotification(message, type = 'success') {
            // Создаем уведомление
            const notification = document.createElement('div');
            notification.className = `cart-notification cart-notification-${type}`;
            notification.innerHTML = `
                <div class="cart-notification-content">
                    <i class="bi ${type === 'success' ? 'bi-check-circle' : 'bi-x-circle'} me-2"></i>
                    <span>${message}</span>
                </div>
            `;
    
    // Добавляем стили
    if (!document.getElementById('cart-notification-style')) {
        const style = document.createElement('style');
        style.id = 'cart-notification-style';
        style.textContent = `
            .cart-notification {
                position: fixed;
                top: 80px;
                right: 20px;
                min-width: 300px;
                max-width: 400px;
                padding: 15px 20px;
                background: #fff;
                border-radius: 8px;
                box-shadow: 0 4px 20px rgba(0,0,0,0.15);
                z-index: 10000;
                animation: slideInRight 0.3s ease;
                border-left: 4px solid #4CAF50;
            }
            
            .cart-notification-error {
                border-left-color: #f44336;
            }
            
            .cart-notification-content {
                display: flex;
                align-items: center;
                font-size: 14px;
                color: #333;
            }
            
            .cart-notification-content .bi {
                font-size: 18px;
            }
            
            .cart-notification-success .cart-notification-content .bi {
                color: #4CAF50;
            }
            
            .cart-notification-error .cart-notification-content .bi {
                color: #f44336;
            }
            
            @keyframes slideInRight {
                from {
                    transform: translateX(100%);
                    opacity: 0;
                }
                to {
                    transform: translateX(0);
                    opacity: 1;
                }
            }
            
            @keyframes slideOutRight {
                from {
                    transform: translateX(0);
                    opacity: 1;
                }
                to {
                    transform: translateX(100%);
                    opacity: 0;
                }
            }
        `;
        document.head.appendChild(style);
    }
    
    document.body.appendChild(notification);
    
    // Удаляем уведомление через 3 секунды
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.3s ease';
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 300);
    }, 3000);
}

// Функция для обновления счетчика корзины
function updateCartCount(count) {
    // Обновляем все элементы с классом cart-count
    const cartCountElements = document.querySelectorAll('.cart-count, .cart-count-number, [data-cart-count]');
    
    cartCountElements.forEach(element => {
        element.textContent = count;
        
        // Если счетчик был скрыт при 0, показываем его
        if (count > 0 && element.style.display === 'none') {
            element.style.display = 'inline-block';
        } else if (count === 0 && element.classList.contains('hide-when-empty')) {
            element.style.display = 'none';
        }
    });
    
    // Также обновляем в заголовке (если есть шаблонный элемент)
    const headerCartCount = document.querySelector('.nav-cart-count, .cart-badge');
    if (headerCartCount) {
        headerCartCount.textContent = count;
    }
}
        
        // ============ ИНИЦИАЛИЗАЦИЯ ОТЗЫВОВ ============
        // Скрываем отзывы, кроме первых 2 (если их больше 2)
        const allReviews = document.querySelectorAll('.review-item-oneone');
        const allDividers = document.querySelectorAll('.review-divider');
        
        if (allReviews.length > 2) {
            allReviews.forEach((review, index) => {
                if (index >= 2) {
                    review.classList.add('hidden-review');
                    review.style.display = 'none';
                }
            });
            
            allDividers.forEach((divider, index) => {
                if (index >= 1) {
                    divider.classList.add('hidden-review-divider');
                    divider.style.display = 'none';
                }
            });
        }
        
        // Вешаем обработчики на кнопки переключения отзывов
        const toggleBtn = document.getElementById('toggleReviewsBtn');
        const toggleBtnMobile = document.getElementById('toggleReviewsBtnMobile');
        
        if (toggleBtn) {
            toggleBtn.addEventListener('click', toggleReviews);
        }
        
        if (toggleBtnMobile) {
            toggleBtnMobile.addEventListener('click', toggleReviews);
        }
        
        // Автоматически показываем все отзывы, если их мало
        if (allReviews.length <= 5 && allReviews.length > 2) {
            // Если отзывов от 3 до 5, показываем все сразу
            const hiddenReviews = document.querySelectorAll('.hidden-review');
            const hiddenDividers = document.querySelectorAll('.hidden-review-divider');
            
            hiddenReviews.forEach(review => {
                review.classList.remove('hidden-review');
                review.style.display = 'block';
            });
            
            hiddenDividers.forEach(divider => {
                divider.classList.remove('hidden-review-divider');
                divider.style.display = 'block';
            });
            
            // Меняем текст кнопок
            if (toggleBtn) {
                toggleBtn.querySelector('.btn-oneone-text').textContent = 'Скрыть';
                toggleBtn.classList.add('show-all');
            }
            
            if (toggleBtnMobile) {
                toggleBtnMobile.querySelector('.btn-oneone-text').textContent = 'Скрыть отзывы';
                toggleBtnMobile.classList.add('show-all');
            }
            
            const toggleIcon = document.getElementById('toggleReviewsIcon');
            const toggleIconMobile = document.getElementById('toggleReviewsIconMobile');
            if (toggleIcon) toggleIcon.style.transform = 'rotate(180deg)';
            if (toggleIconMobile) toggleIconMobile.style.transform = 'rotate(180deg)';
        } else if (allReviews.length <= 2) {
            // Если отзывов 2 или меньше, скрываем кнопки
            if (toggleBtn) toggleBtn.style.display = 'none';
            if (toggleBtnMobile) toggleBtnMobile.style.display = 'none';
        }

        // Функция для добавления похожего товара в корзину
        function addSimilarToCart(productId) {
            // AJAX запрос для добавления в корзину
            fetch('/cart/add', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({
                    product_id: productId,
                    quantity: 1
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Показываем уведомление об успешном добавлении
                    alert('Товар добавлен в корзину!');
                    
                    // Обновляем счетчик корзины в шапке (если есть)
                    const cartCountElement = document.querySelector('.cart-count');
                    if (cartCountElement && data.cart_count !== undefined) {
                        cartCountElement.textContent = data.cart_count;
                    }
                } else {
                    alert('Ошибка при добавлении в корзину: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error adding to cart:', error);
                alert('Произошла ошибка при добавлении в корзину.');
            });
        }


    });

</script>

<!-- ... весь твой существующий код ... -->

<script>
// ==================== //
// ОБРАБОТКА ИЗБРАННОГО //
// ==================== //

document.addEventListener('DOMContentLoaded', function() {
    // Функция для обновления счетчика избранного
    function updateWishlistCount(count) {
        // Обновляем все элементы с классом wishlist-count
        document.querySelectorAll('.wishlist-count, .wishlist-count-number, #wishlist-count').forEach(element => {
            element.textContent = count;
        });
        
        // Если элемент не найден, проверяем в шапке
        const headerWishlistCount = document.querySelector('.nav-link .badge');
        if (headerWishlistCount && headerWishlistCount.classList.contains('bg-danger')) {
            headerWishlistCount.textContent = count;
        }
    }
    
    // Функция для показа уведомлений
    function showNotification(message, type = 'info') {
        // Используем существующую систему уведомлений или создаем простую
        if (typeof toastr !== 'undefined') {
            toastr[type === 'success' ? 'success' : 'error'](message);
        } else if (typeof alert !== 'undefined') {
            alert(message);
        } else {
            // Создаем простое уведомление
            const notification = document.createElement('div');
            notification.style.cssText = `
                position: fixed;
                top: 20px;
                right: 20px;
                padding: 15px 25px;
                background: ${type === 'success' ? '#4CAF50' : '#f44336'};
                color: white;
                border-radius: 4px;
                z-index: 9999;
                animation: slideIn 0.3s ease;
                box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            `;
            notification.textContent = message;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                notification.style.animation = 'slideOut 0.3s ease';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.parentNode.removeChild(notification);
                    }
                }, 300);
            }, 3000);
            
            // Добавляем стили для анимации
            if (!document.querySelector('#notification-styles')) {
                const style = document.createElement('style');
                style.id = 'notification-styles';
                style.textContent = `
                    @keyframes slideIn {
                        from { transform: translateX(100%); opacity: 0; }
                        to { transform: translateX(0); opacity: 1; }
                    }
                    @keyframes slideOut {
                        from { transform: translateX(0); opacity: 1; }
                        to { transform: translateX(100%); opacity: 0; }
                    }
                `;
                document.head.appendChild(style);
            }
        }
    }
    
    // Добавляем стили для кнопки избранного
    const style = document.createElement('style');
    style.textContent = `
        .btn-oneone.in-wishlist {
            border-color: #dc3545 !important;
            background-color: rgba(220, 53, 69, 0.1) !important;
        }
        
        .btn-oneone.in-wishlist:hover {
            background-color: rgba(220, 53, 69, 0.2) !important;
        }
        
        .bi-spinner {
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    `;
    document.head.appendChild(style);
});

// Альтернативный вариант: добавляем обработчик напрямую на кнопку
// если не хотите использовать форму
function toggleWishlist(productId) {
    if (!productId) {
        console.error('Product ID не указан');
        return;
    }
    
    const button = document.querySelector(`button[data-product-id="${productId}"]`) || 
                   document.getElementById('wishlistButton');
    
    if (!button) {
        console.error('Кнопка избранного не найдена');
        return;
    }
    
    // Показываем загрузку
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-spinner bi-spin me-2"></i>Загрузка...';
    
    // Отправляем AJAX запрос
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId
        })
    })
    .then(response => response.json())
    .then(data => {
        console.log('Ответ от сервера:', data);
        
        if (data.success) {
            // Обновляем интерфейс
            updateWishlistUI(button, data);
            updateWishlistCount(data.count);
            showNotification(data.message, 'success');
        } else {
            showNotification(data.message || 'Произошла ошибка', 'error');
        }
    })
    .catch(error => {
        console.error('Ошибка:', error);
        showNotification('Произошла ошибка соединения', 'error');
    })
    .finally(() => {
        // Восстанавливаем кнопку
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

function updateWishlistUI(button, data) {
    const heartIcon = button.querySelector('.bi');
    
    if (data.in_wishlist) {
        // Товар в избранном
        heartIcon.className = 'bi bi-heart-fill text-danger me-2';
        if (button.querySelector('.btn-oneone-text')) {
            button.querySelector('.btn-oneone-text').textContent = 'В избранном';
        }
        button.classList.add('in-wishlist');
    } else {
        // Товар не в избранном
        heartIcon.className = 'bi bi-heart me-2';
        if (button.querySelector('.btn-oneone-text')) {
            button.querySelector('.btn-oneone-text').textContent = 'В избранное';
        }
        button.classList.remove('in-wishlist');
    }
}

function updateWishlistCount(count) {
    // Обновляем счетчики на странице
    document.querySelectorAll('.wishlist-count').forEach(el => {
        el.textContent = count;
    });
}

function showNotification(message, type) {
    // Простая реализация уведомлений
    const notification = document.createElement('div');
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        padding: 12px 20px;
        background: ${type === 'success' ? '#4CAF50' : '#f44336'};
        color: white;
        border-radius: 4px;
        z-index: 10000;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: fadeIn 0.3s ease;
    `;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.style.opacity = '0';
        notification.style.transition = 'opacity 0.3s ease';
        setTimeout(() => notification.remove(), 300);
    }, 3000);
}

// Добавляем стили для анимации
if (!document.querySelector('#fade-in-styles')) {
    const style = document.createElement('style');
    style.id = 'fade-in-styles';
    style.textContent = `
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    `;
    document.head.appendChild(style);
}
</script>

@endsection