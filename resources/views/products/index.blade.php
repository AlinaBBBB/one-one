{{-- resources/views/products/index.blade.php --}}
@extends('layouts.layout')

@section('title')
    @parent Каталог товаров - ONEONE
@endsection

@section('content')
    <div class="container py-5">
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="section-title mb-4">КАТАЛОГ ТОВАРОВ</h1>
                <p class="section-subtitle">Минималистичная женская одежда премиум-класса</p>
            </div>
        </div>

        <!-- Фильтры и сортировка -->
        <div class="row mb-5">
            <div class="col-md-8">
                <div class="d-flex flex-wrap gap-3">
                    <!-- Фильтр по категориям -->
                    <div class="dropdown">
                        <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            Категории
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('products.index') }}">Все категории</a></li>
                            @foreach($categories as $category)
                                <li>
                                    <a class="dropdown-item" href="{{ route('products.index', ['category' => $category->id]) }}">
                                        {{ $category->name }} ({{ $category->products_count ?? 0 }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                    <!-- Фильтр по новинкам -->
                    <a href="{{ route('products.index', ['new' => true]) }}" 
                       class="btn btn-outline-dark {{ request('new') ? 'active' : '' }}">
                        Новинки
                    </a>

                    <!-- Фильтр по популярным -->
                    <a href="{{ route('products.index', ['popular' => true]) }}" 
                       class="btn btn-outline-dark {{ request('popular') ? 'active' : '' }}">
                        Популярное
                    </a>

                    <!-- Фильтр по наличию -->
                    <a href="{{ route('products.index', ['in_stock' => true]) }}" 
                       class="btn btn-outline-dark {{ request('in_stock') ? 'active' : '' }}">
                        В наличии
                    </a>
                </div>
            </div>

            <div class="col-md-4 text-end">
                <!-- Сортировка -->
                <div class="dropdown">
                    <button class="btn btn-outline-dark dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        Сортировка: 
                        @switch(request('sort', 'newest'))
                            @case('price_asc') Цена (по возрастанию) @break
                            @case('price_desc') Цена (по убыванию) @break
                            @case('popular') Популярные @break
                            @case('name_asc') Название (А-Я) @break
                            @case('name_desc') Название (Я-А) @break
                            @default Новинки
                        @endswitch
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'newest']) }}">Новинки</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'popular']) }}">Популярные</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'price_asc']) }}">Цена (по возрастанию)</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'price_desc']) }}">Цена (по убыванию)</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'name_asc']) }}">Название (А-Я)</a></li>
                        <li><a class="dropdown-item" href="{{ route('products.index', ['sort' => 'name_desc']) }}">Название (Я-А)</a></li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- Сетка товаров -->
        @if($products->count() > 0)
            <div class="row g-4">
                @foreach($products as $product)
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <!-- Минималистичная карточка товара -->
                        <div class="minimal-product-card">
                            <!-- Бейджи -->
                            @if($product->is_new)
                                <div class="minimal-product-badge new">NEW</div>
                            @endif
                            
                            @if($product->has_discount)
                                <div class="minimal-product-badge sale">-{{ $product->discount_percent }}%</div>
                            @endif
                            
                            @if($product->is_popular)
                                <div class="minimal-product-badge popular">ПОПУЛЯРНОЕ</div>
                            @endif

                    <!-- Изображение -->
                    <a href="{{ route('products.show', $product->id) }}" class="minimal-product-image-link">
                        <div class="minimal-product-image-wrapper">
                      @php
                            // 1. Прямое поле image из таблицы products
                            $imageUrl = $product->image ? asset($product->image) : null;
                            
                            // 2. Если нет, проверяем загруженные изображения
                            if (!$imageUrl && $product->images && $product->images->isNotEmpty()) {
                                $firstImage = $product->images->first();
                                $imageUrl = $firstImage->image_path ?? $firstImage->url ?? $firstImage->path ?? null;
                                if ($imageUrl && !str_starts_with($imageUrl, 'http')) {
                                    $imageUrl = asset($imageUrl);
                                }
                            }
                        @endphp
                            
                            @if($imageUrl)
                            <img src="{{ $imageUrl }}" 
                                alt="{{ $product->title }}"
                                class="minimal-product-image">
                            @else
                            <div class="minimal-product-image-placeholder">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 300 300">
                                    <rect width="100%" height="100%" fill="#fafafa"/>
                                </svg>
                            </div>
                            @endif
                        </div>
                    </a>
                            
                            <!-- Информация -->
                            <div class="minimal-product-info">
                                <a href="{{ route('products.show', $product->id) }}" class="minimal-product-title-link">
                                    <h3 class="minimal-product-title">{{ $product->title }}</h3>
                                </a>
                                
                                <div class="minimal-product-description">
                                    @if($product->short_description)
                                        <p>{{ $product->short_description }}</p>
                                    @elseif($product->description)
                                        <p>{{ Str::limit($product->description, 80) }}</p>
                                    @endif
                                </div>
                                
                                <!-- Цена -->
                                <div class="minimal-product-price">
                                    @if($product->old_price_formatted)
                                        <div class="minimal-price-discount">
                                            <span class="minimal-old-price">{{ $product->old_price_formatted }}</span>
                                        </div>
                                    @endif
                                    
                                    <div class="minimal-current-price">
                                        {{ $product->price_formatted }}
                                    </div>
                                </div>
                                
                                <!-- Кнопка -->
                                <div class="minimal-product-actions">
                                    <button class="minimal-btn-add-to-cart" onclick="addToCart({{ $product->id }})">
                                        В корзину
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Пагинация -->
            @if($products->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                {{ $products->links() }}
                            </ul>
                        </nav>
                    </div>
                </div>
            @endif
        @else
            <div class="text-center py-5">
                <h3 class="mb-3">Товары не найдены</h3>
                <p class="text-muted mb-4">Попробуйте изменить параметры фильтрации</p>
                <a href="{{ route('products.index') }}" class="btn btn-dark">Сбросить фильтры</a>
            </div>
        @endif
    </div>

    <style>
        /* Стили для каталога (можно вынести в отдельный файл) */
        .minimal-product-card {
            background: white;
            border-radius: 0;
            overflow: hidden;
            transition: all 0.3s ease;
            height: 100%;
            position: relative;
            border: 1px solid #f0f0f0;
        }
        
        .minimal-product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
        }
        
        .minimal-product-image-link {
            display: block;
            text-decoration: none;
            overflow: hidden;
        }
        
        .minimal-product-image-wrapper {
            position: relative;
            overflow: hidden;
            height: 300px;
            background: #fff;
        }
        
        .minimal-product-image {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform 0.8s ease;
        }
        
        .minimal-product-card:hover .minimal-product-image {
            transform: scale(1.03);
        }
        
        .minimal-product-image-placeholder {
            width: 100%;
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #ddd;
            background: #fafafa;
        }
        
        .minimal-product-image-placeholder i {
            font-size: 2rem;
        }
        
        .minimal-product-badge {
            position: absolute;
            top: 12px;
            left: 12px;
            padding: 4px 8px;
            font-size: 0.7rem;
            font-weight: 600;
            letter-spacing: 0.5px;
            z-index: 2;
            text-transform: uppercase;
        }
        
        .minimal-product-badge.sale {
            background: #111;
            color: white;
        }
        
        .minimal-product-badge.new {
            background: #D282A9;
            color: white;
        }
        
        .minimal-product-badge.popular {
            background: #FF6B9D;
            color: white;
        }
        
        .minimal-product-info {
            padding: 16px;
            background: white;
        }
        
        .minimal-product-title-link {
            text-decoration: none;
            color: inherit;
            display: block;
            margin-bottom: 8px;
        }
        
        .minimal-product-title {
            font-size: 1rem;
            font-weight: 500;
            color: #111;
            margin: 0 0 4px 0;
            line-height: 1.4;
        }
        
        .minimal-product-description {
            margin-bottom: 12px;
            min-height: 40px;
        }
        
        .minimal-product-description p {
            font-size: 0.875rem;
            color: #666;
            line-height: 1.4;
            margin: 0;
        }
        
        .minimal-product-price {
            margin-bottom: 12px;
        }
        
        .minimal-price-discount {
            margin-bottom: 2px;
        }
        
        .minimal-old-price {
            text-decoration: line-through;
            color: #999;
            font-size: 0.875rem;
            font-weight: 300;
        }
        
        .minimal-current-price {
            font-size: 1.125rem;
            font-weight: 500;
            color: #111;
        }
        
        .minimal-btn-add-to-cart {
            width: 100%;
            height: 36px;
            background: #111;
            color: white;
            border: none;
            font-weight: 500;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            cursor: pointer;
            text-transform: uppercase;
        }
        
        .minimal-btn-add-to-cart:hover {
            background: #333;
        }
        
        .pagination .page-link {
            color: #111;
            border: 1px solid #e5e7eb;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #111;
            border-color: #111;
        }
        
        .btn-outline-dark.active {
            background-color: #111;
            color: white;
        }
    </style>
@endsection

@section('scripts')
<script>
    function addToCart(productId) {
        // Здесь будет AJAX запрос для добавления в корзину
        alert('Товар добавлен в корзину!');
    }
</script>
@endsection