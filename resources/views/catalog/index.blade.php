@extends('layouts.layout')

@section('title')
    Каталог товаров | ONEONE
@endsection

@section('content')
<div class="catalog-page">
    <!-- Заголовок -->
    <div class="catalog-header py-4">
        <div class="container">
            <h1 class="display-6 fw-bold mb-2">КАТАЛОГ</h1>
            <p class="text-muted mb-0" id="productsCount">Найдено товаров: {{ $products->total() }}</p>
        </div>
    </div>

    <!-- Основная область -->
    <div class="catalog-main-area py-4">
        <div class="container">
            <!-- Верхняя панель фильтров (свернутая по умолчанию) -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-funnel me-2"></i>Фильтры
                        </h5>
                        <button type="button" 
                                class="btn btn-link btn-sm text-decoration-none p-0 d-flex align-items-center" 
                                data-bs-toggle="collapse" 
                                data-bs-target="#topFiltersCollapse"
                                aria-expanded="false"
                                id="toggleFiltersBtn">
                            <span>Показать фильтры</span>
                            <i class="bi bi-chevron-down ms-1"></i>
                        </button>
                    </div>
                    
                   <!-- Свернутые фильтры -->
                    <div class="collapse" id="topFiltersCollapse">
                        <div class="pt-3 border-top">
                            <div class="row g-3">
                                
                                <!-- Категории -->
                                <div class="col-md-3">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-grid me-1"></i>Категории
                                        </label>
                                        <div class="category-filter-scroll" style="max-height: 200px; overflow-y: auto;">
                                            @foreach($categories->where('parent_id', null) as $mainCategory)
                                                @if($mainCategory->products_count > 0)
                                                <div class="mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input category-checkbox" 
                                                            type="checkbox" 
                                                            id="cat_{{ $mainCategory->id }}"
                                                            value="{{ $mainCategory->slug }}"
                                                            {{ in_array($mainCategory->slug, (array)request('categories', [])) ? 'checked' : '' }}>
                                                        <label class="form-check-label w-100 d-flex justify-content-between small" 
                                                            for="cat_{{ $mainCategory->id }}">
                                                            <span>{{ $mainCategory->name }}</span>
                                                            <span class="badge bg-light text-dark">{{ $mainCategory->products_count }}</span>
                                                        </label>
                                                    </div>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Цвета -->
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-palette me-1"></i>Цвет
                                        </label>
                                        <div class="color-filter-scroll" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($colors) && count($colors) > 0)
                                                @foreach($colors as $color)
                                                <div class="mb-1">
                                                    <div class="form-check">
                                                        <input class="form-check-input color-checkbox" 
                                                            type="checkbox" 
                                                            id="color_{{ $color->id }}"
                                                            value="{{ $color->id }}"
                                                            {{ in_array($color->id, explode(',', request('colors', ''))) ? 'checked' : '' }}>
                                                        <label class="form-check-label d-flex align-items-center small" for="color_{{ $color->id }}">
                                                            <span class="color-preview me-2" style="width: 14px; height: 14px; background: {{ $color->hex_code }}; border-radius: 50%; border: 1px solid #ddd;"></span>
                                                            {{ $color->name }}
                                                        </label>
                                                    </div>
                                                </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted small">Нет доступных цветов</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Размеры -->
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-rulers me-1"></i>Размер
                                        </label>
                                        <div class="size-filter-scroll" style="max-height: 200px; overflow-y: auto;">
                                            @if(isset($sizes) && count($sizes) > 0)
                                                @foreach($sizes as $size)
                                                    @php
                                                        $sizeName = is_object($size) ? ($size->name ?? '') : $size;
                                                    @endphp
                                                    <div class="mb-1">
                                                        <div class="form-check">
                                                            <input class="form-check-input size-checkbox" 
                                                                type="checkbox" 
                                                                id="size_{{ $sizeName }}"
                                                                value="{{ $sizeName }}"
                                                                {{ in_array($sizeName, explode(',', request('sizes', ''))) ? 'checked' : '' }}>
                                                            <label class="form-check-label small" for="size_{{ $sizeName }}">
                                                                {{ $sizeName }}
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <p class="text-muted small">Нет доступных размеров</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Цена и Рейтинг -->
                                <div class="col-md-3">
                                    <!-- Цена -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-currency-dollar me-1"></i>Цена
                                        </label>
                                        <div class="price-range">
                                            <div class="row g-2 mb-2">
                                                <div class="col">
                                                    <input type="number" 
                                                        class="form-control form-control-sm" 
                                                        id="price_min" 
                                                        placeholder="От" 
                                                        value="{{ request('price_min', '') }}"
                                                        min="0">
                                                </div>
                                                <div class="col">
                                                    <input type="number" 
                                                        class="form-control form-control-sm" 
                                                        id="price_max" 
                                                        placeholder="До" 
                                                        value="{{ request('price_max', '') }}"
                                                        min="0">
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-between small text-muted">
                                                <span>{{ number_format($minPrice ?? 0, 0, ',', ' ') }} ₽</span>
                                                <span>{{ number_format($maxPrice ?? 0, 0, ',', ' ') }} ₽</span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Рейтинг -->
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-star me-1"></i>Рейтинг
                                        </label>
                                        <div class="rating-filter">
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="rating_5"
                                                    {{ request('rating') == '5' ? 'checked' : '' }}>
                                                <label class="form-check-label d-flex align-items-center small" for="rating_5">
                                                    <div class="text-warning me-2">
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                    </div>
                                                    <span>5 звезд</span>
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="rating_4"
                                                    {{ request('rating') == '4' ? 'checked' : '' }}>
                                                <label class="form-check-label d-flex align-items-center small" for="rating_4">
                                                    <div class="text-warning me-2">
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star-fill small"></i>
                                                        <i class="bi bi-star small"></i>
                                                    </div>
                                                    <span>4+ звезды</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Наличие и быстрые фильтры -->
                                <div class="col-md-2">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-check-circle me-1"></i>Наличие
                                        </label>
                                        <div class="availability-filter">
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" 
                                                    type="radio" 
                                                    name="availability" 
                                                    id="availability_all"
                                                    value=""
                                                    {{ !request('availability') ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="availability_all">
                                                    Все товары
                                                </label>
                                            </div>
                                            <div class="form-check mb-2">
                                                <input class="form-check-input" 
                                                    type="radio" 
                                                    name="availability" 
                                                    id="inStock"
                                                    value="in_stock"
                                                    {{ request('availability') == 'in_stock' ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="inStock">
                                                    В наличии
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                    type="radio" 
                                                    name="availability" 
                                                    id="onOrder"
                                                    value="on_order"
                                                    {{ request('availability') == 'on_order' ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="onOrder">
                                                    Под заказ
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-percent me-1"></i>Скидка
                                        </label>
                                        <div class="discount-range">
                                            <select class="form-select form-select-sm" id="discount_range">
                                                <option value="">Любая скидка</option>
                                                <option value="10" {{ request('discount_min') == '10' ? 'selected' : '' }}>От 10%</option>
                                                <option value="20" {{ request('discount_min') == '20' ? 'selected' : '' }}>От 20%</option>
                                                <option value="30" {{ request('discount_min') == '30' ? 'selected' : '' }}>От 30%</option>
                                                <option value="40" {{ request('discount_min') == '40' ? 'selected' : '' }}>От 40%</option>
                                                <option value="50" {{ request('discount_min') == '50' ? 'selected' : '' }}>От 50%</option>
                                            </select>
                                        </div>
                                    </div>
                                    
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold small mb-2">
                                            <i class="bi bi-lightning me-1"></i>Быстрые фильтры
                                        </label>
                                        <div class="quick-filters">
                                            <div class="form-check mb-1">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="is_new"
                                                    {{ request('is_new') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="is_new">
                                                    Новинки
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" 
                                                    type="checkbox" 
                                                    id="is_on_sale"
                                                    {{ request('is_on_sale') == '1' ? 'checked' : '' }}>
                                                <label class="form-check-label small" for="is_on_sale">
                                                    Со скидкой
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <!-- Кнопки управления фильтрами -->
                                   <div class="d-flex gap-2 mt-2">
                                        <button type="button" 
                                                class="btn btn-dark btn-sm" 
                                                onclick="applyFilters()">
                                            <i class="bi bi-check-lg me-1"></i>Применить
                                        </button>
                                        <button type="button" 
                                                class="btn btn-outline-secondary btn-sm" 
                                                onclick="resetFilters()">
                                            <i class="bi bi-x-lg me-1"></i>Сбросить
                                        </button>
                                    </div>
                                </div>
                                
                            </div><!-- /row g-3 -->
                            
                            <!-- Активные фильтры -->
                            @if(request()->anyFilled(['categories', 'colors', 'sizes', 'price_min', 'price_max', 'is_new', 'is_on_sale', 'availability', 'rating', 'discount_min']))
                            <div class="active-filters mt-3 pt-3 border-top">
                                <div class="d-flex flex-wrap align-items-center gap-2">
                                    <span class="small fw-semibold">Активные фильтры:</span>
                                    <div id="activeFiltersList" class="d-flex flex-wrap gap-2"></div>
                                    <button type="button" 
                                            class="btn btn-link btn-sm text-decoration-none p-0 ms-auto"
                                            onclick="clearAllFilters()">
                                        Очистить все
                                    </button>
                                </div>
                            </div>
                            @endif
                            
                        </div><!-- /pt-3 -->
                    </div><!-- /collapse -->
                </div>
            </div>

            <!-- Панель сортировки и вида -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="small text-muted">
                                <span id="filteredCount">{{ $products->total() }}</span> товаров
                            </span>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-md-end align-items-center">
                                <span class="me-2 small text-muted">Сортировка:</span>
                                <select class="form-select form-select-sm w-auto" id="sortSelect">
                                    <option value="newest" {{ request('sort', 'newest') == 'newest' ? 'selected' : '' }}>
                                        Сначала новинки
                                    </option>
                                    <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                                        По возрастанию цены
                                    </option>
                                    <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                                        По убыванию цены
                                    </option>
                                    <option value="popular" {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                        По популярности
                                    </option>
                                    <option value="discount_desc" {{ request('sort') == 'discount_desc' ? 'selected' : '' }}>
                                        По размеру скидки
                                    </option>
                                    <option value="rating_desc" {{ request('sort') == 'rating_desc' ? 'selected' : '' }}>
                                        По рейтингу
                                    </option>
                                </select>
                                <div class="btn-group ms-2" role="group">
                                    <button type="button" class="btn btn-outline-secondary btn-sm active" 
                                            onclick="changeView('grid')">
                                        <i class="bi bi-grid-3x3-gap"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm" 
                                            onclick="changeView('list')">
                                        <i class="bi bi-list"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Контент -->
            <div class="catalog-content">
                <!-- Индикатор загрузки -->
                <div id="loadingIndicator" class="d-none text-center py-5">
                    <div class="spinner-border text-dark" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Загрузка...</span>
                    </div>
                    <p class="mt-3">Загрузка товаров...</p>
                </div>

                <!-- Товары -->
                <div id="productsContainer">
                    @include('catalog.partials.products-grid')
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Стили для фильтров -->
<style>
    /* Анимация для раскрытия/скрытия фильтров */
    #topFiltersCollapse {
        transition: all 0.3s ease;
    }
    
    /* Стили для активных фильтров */
    .active-filters .badge {
        font-size: 0.75rem;
        padding: 4px 8px;
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
    }
    
    .active-filters .badge .btn-close {
        font-size: 0.6rem;
        padding: 0.5rem;
    }
    
    /* Стили для чекбоксов */
    .form-check-input:checked {
        background-color: #111;
        border-color: #111;
    }
    
    /* Прокрутка для категорий */
    .category-filter-scroll::-webkit-scrollbar {
        width: 4px;
    }
    
    .category-filter-scroll::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 2px;
    }
    
    .category-filter-scroll::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 2px;
    }
    
    .category-filter-scroll::-webkit-scrollbar-thumb:hover {
        background: #555;
    }
    
    /* Кнопка вида */
    .btn-view-active {
        background-color: #6c757d;
        color: white;
    }
    
    /* Иконки звезд */
    .text-warning i {
        font-size: 0.9rem;
    }
    
    /* Отзывчивость */
    @media (max-width: 768px) {
        .active-filters .badge {
            font-size: 0.7rem;
            padding: 3px 6px;
        }
        
        .btn-group .btn {
            padding: 0.25rem 0.5rem;
        }
    }
</style>

<script>
    // Текущие настройки
    let currentView = 'grid';
    let isFiltersExpanded = false;
    
    // Обновление текста кнопки раскрытия
    function updateToggleButtonText() {
        const btn = document.getElementById('toggleFiltersBtn');
        if (btn) {
            const icon = btn.querySelector('i');
            const text = btn.querySelector('span');
            if (isFiltersExpanded) {
                text.textContent = 'Скрыть фильтры';
                icon.className = 'bi bi-chevron-up ms-1';
            } else {
                text.textContent = 'Показать фильтры';
                icon.className = 'bi bi-chevron-down ms-1';
            }
        }
    }
    
    // Обработка раскрытия/скрытия фильтров
    document.getElementById('topFiltersCollapse').addEventListener('show.bs.collapse', function () {
        isFiltersExpanded = true;
        updateToggleButtonText();
    });
    
    document.getElementById('topFiltersCollapse').addEventListener('hide.bs.collapse', function () {
        isFiltersExpanded = false;
        updateToggleButtonText();
    });
    
    // 🔥 Функция применения фильтров (просто вызывает updateFilters)
    function applyFilters() {
        updateFilters();
    }
    
    // 🔥 Функция ПОЛНОГО сброса фильтров и возврата ко всем товарам
    function resetFilters() {
        // Очищаем URL параметры
        if (window.history && window.history.pushState) {
            const url = new URL(window.location.href);
            url.search = ''; // Убираем все параметры
            window.history.pushState({}, '', url);
        }
        
        // Перезагружаем страницу без параметров
        window.location.href = window.location.pathname;
    }
    
    // 🔥 Функция очистки всех фильтров (то же что и resetFilters)
    function clearAllFilters() {
        resetFilters();
    }
    
    // Функция удаления одного фильтра
    function removeFilter(type, value = null) {
        switch(type) {
            case 'categories':
                const checkbox = document.querySelector(`.category-checkbox[value="${value}"]`);
                if (checkbox) checkbox.checked = false;
                break;
            case 'colors':
                const colorCheckbox = document.querySelector(`.color-checkbox[value="${value}"]`);
                if (colorCheckbox) colorCheckbox.checked = false;
                break;
            case 'sizes':
                const sizeCheckbox = document.querySelector(`.size-checkbox[value="${value}"]`);
                if (sizeCheckbox) sizeCheckbox.checked = false;
                break;
            case 'price':
                document.getElementById('price_min').value = '';
                document.getElementById('price_max').value = '';
                break;
            case 'availability':
                document.getElementById('availability_all').checked = true;
                break;
            case 'is_new':
                document.getElementById('is_new').checked = false;
                break;
            case 'is_on_sale':
                document.getElementById('is_on_sale').checked = false;
                break;
            case 'discount':
                document.getElementById('discount_range').value = '';
                break;
            case 'rating':
                document.getElementById('rating_5').checked = false;
                document.getElementById('rating_4').checked = false;
                break;
        }
        updateFilters();
    }
    
    // Основная функция обновления фильтров
    let filterTimeout;
    function updateFilters() {
        clearTimeout(filterTimeout);
        
        filterTimeout = setTimeout(() => {
            const params = new URLSearchParams();
            
            // Категории
            const categoryCheckboxes = document.querySelectorAll('.category-checkbox:checked');
            if (categoryCheckboxes.length > 0) {
                const categories = Array.from(categoryCheckboxes).map(cb => cb.value);
                params.set('categories', categories.join(','));
            }
            
            // Цвета
            const colorCheckboxes = document.querySelectorAll('.color-checkbox:checked');
            if (colorCheckboxes.length > 0) {
                const colors = Array.from(colorCheckboxes).map(cb => cb.value);
                params.set('colors', colors.join(','));
            }
            
            // Размеры
            const sizeCheckboxes = document.querySelectorAll('.size-checkbox:checked');
            if (sizeCheckboxes.length > 0) {
                const sizes = Array.from(sizeCheckboxes).map(cb => cb.value);
                params.set('sizes', sizes.join(','));
            }
            
            // Цена
            const priceMin = document.getElementById('price_min').value;
            const priceMax = document.getElementById('price_max').value;
            if (priceMin) params.set('price_min', priceMin);
            if (priceMax) params.set('price_max', priceMax);
            
            // Наличие
            const availability = document.querySelector('input[name="availability"]:checked');
            if (availability && availability.value) {
                params.set('availability', availability.value);
            }
            
            // Быстрые фильтры
            if (document.getElementById('is_new')?.checked) params.set('is_new', '1');
            if (document.getElementById('is_on_sale')?.checked) params.set('is_on_sale', '1');
            
            // Скидка
            const discountRange = document.getElementById('discount_range').value;
            if (discountRange) params.set('discount_min', discountRange);
            
            // Рейтинг
            if (document.getElementById('rating_5')?.checked) {
                params.set('rating', '5');
            } else if (document.getElementById('rating_4')?.checked) {
                params.set('rating', '4');
            }
            
            // Сортировка
            const sort = document.getElementById('sortSelect').value;
            if (sort && sort !== 'newest') {
                params.set('sort', sort);
            }
            
            // Показываем индикатор загрузки
            showLoading();
            
            // Отправляем AJAX запрос
            fetch(`/catalog/filter?${params.toString()}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateProducts(data.html);
                        updateProductsCount(data.total);
                        updateActiveFilters(params);
                        
                        // Обновляем URL
                        const url = new URL(window.location.href);
                        url.search = params.toString();
                        window.history.pushState({}, '', url);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    window.location.href = `/catalog?${params.toString()}`;
                })
                .finally(() => {
                    hideLoading();
                });
        }, 300); // Уменьшил задержку до 300мс
    }
    
    // Обновление активных фильтров
    function updateActiveFilters(params = null) {
        if (!params) {
            params = new URLSearchParams(window.location.search);
        }
        
        const filtersList = document.getElementById('activeFiltersList');
        if (!filtersList) return;
        
        filtersList.innerHTML = '';
        
        // Категории
        if (params.get('categories')) {
            const categories = params.get('categories').split(',');
            categories.forEach(cat => {
                filtersList.innerHTML += `
                    <span class="badge bg-light text-dark border d-flex align-items-center">
                        ${cat}
                        <button type="button" class="btn-close btn-close-sm ms-2" 
                                onclick="removeFilter('categories', '${cat}')"></button>
                    </span>
                `;
            });
        }
        
        // Цвета
        if (params.get('colors')) {
            const colors = params.get('colors').split(',');
            colors.forEach(colorId => {
                filtersList.innerHTML += `
                    <span class="badge bg-light text-dark border d-flex align-items-center">
                        Цвет #${colorId}
                        <button type="button" class="btn-close btn-close-sm ms-2" 
                                onclick="removeFilter('colors', '${colorId}')"></button>
                    </span>
                `;
            });
        }
        
        // Размеры
        if (params.get('sizes')) {
            const sizes = params.get('sizes').split(',');
            sizes.forEach(size => {
                filtersList.innerHTML += `
                    <span class="badge bg-light text-dark border d-flex align-items-center">
                        Размер: ${size}
                        <button type="button" class="btn-close btn-close-sm ms-2" 
                                onclick="removeFilter('sizes', '${size}')"></button>
                    </span>
                `;
            });
        }
        
        // Цена
        if (params.get('price_min') || params.get('price_max')) {
            const min = params.get('price_min') || '0';
            const max = params.get('price_max') || '∞';
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    Цена: ${min} - ${max} ₽
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('price')"></button>
                </span>
            `;
        }
        
        // Наличие
        if (params.get('availability')) {
            const availabilityText = params.get('availability') === 'in_stock' ? 'В наличии' : 'Под заказ';
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    ${availabilityText}
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('availability')"></button>
                </span>
            `;
        }
        
        // Новинки
        if (params.get('is_new') === '1') {
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    Новинки
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('is_new')"></button>
                </span>
            `;
        }
        
        // Со скидкой
        if (params.get('is_on_sale') === '1') {
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    Со скидкой
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('is_on_sale')"></button>
                </span>
            `;
        }
        
        // Скидка %
        if (params.get('discount_min')) {
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    Скидка от ${params.get('discount_min')}%
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('discount')"></button>
                </span>
            `;
        }
        
        // Рейтинг
        if (params.get('rating')) {
            const ratingText = params.get('rating') === '5' ? '5 звезд' : '4+ звезды';
            filtersList.innerHTML += `
                <span class="badge bg-light text-dark border d-flex align-items-center">
                    ${ratingText}
                    <button type="button" class="btn-close btn-close-sm ms-2" 
                            onclick="removeFilter('rating')"></button>
                </span>
            `;
        }
    }
    
    // Смена вида (сетка/список)
    function changeView(view) {
        currentView = view;
        document.querySelectorAll('.btn-group .btn').forEach(btn => {
            btn.classList.remove('active', 'btn-view-active');
        });
        const activeBtn = document.querySelector(`.btn-group .btn:nth-child(${view === 'grid' ? 1 : 2})`);
        if (activeBtn) {
            activeBtn.classList.add('active', 'btn-view-active');
        }
        updateFilters();
    }
    
    // Вспомогательные функции
    function showLoading() {
        document.getElementById('loadingIndicator').classList.remove('d-none');
    }
    
    function hideLoading() {
        document.getElementById('loadingIndicator').classList.add('d-none');
    }
    
    function updateProductsCount(total) {
        document.getElementById('productsCount').textContent = `Найдено товаров: ${total}`;
        document.getElementById('filteredCount').textContent = total;
    }
    
    function updateProducts(html) {
        document.getElementById('productsContainer').innerHTML = html;
    }
    
    // Обработчики при загрузке
    document.addEventListener('DOMContentLoaded', function() {
        // Проверяем активные фильтры
        const hasActiveFilters = {{ json_encode(request()->anyFilled(['categories', 'colors', 'sizes', 'price_min', 'price_max', 'is_new', 'is_on_sale', 'availability', 'rating', 'discount_min'])) }};
        
        if (hasActiveFilters) {
            const collapse = new bootstrap.Collapse(document.getElementById('topFiltersCollapse'), {
                toggle: true
            });
        }
        
        updateActiveFilters();
        
        // Обработчики
        document.getElementById('price_min')?.addEventListener('input', updateFilters);
        document.getElementById('price_max')?.addEventListener('input', updateFilters);
        document.getElementById('sortSelect')?.addEventListener('change', updateFilters);
        
        document.querySelectorAll('.category-checkbox, .color-checkbox, .size-checkbox, input[type="checkbox"], input[type="radio"], select').forEach(element => {
            element.addEventListener('change', updateFilters);
        });
    });
</script>
@endsection