@extends('layouts.layout')

@section('title')
    @parent ONEONE - Минималистичная женская одежда премиум-класса
@endsection

@section('styles')
<style>
/* ==================== */
/* СТИЛИ ДЛЯ ГЛАВНОЙ СТРАНИЦЫ */
/* ==================== */

/* Основные переменные */
:root {
    --oneone-black: #111111;
    --oneone-white: #FFFFFF;
    --oneone-gray-light: #FAFAFA;
    --oneone-gray-border: #EEEEEE;
    --oneone-gray-medium: #666666;
}

/* ОБЩИЕ СТИЛИ СЕКЦИЙ */
.bg-light-subtle {
    background-color: var(--oneone-gray-light) !important;
}

.section-badge {
    display: inline-block;
    padding: 10px 24px;
    background: rgba(17, 17, 17, 0.05);
    color: var(--oneone-black);
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 2px;
    text-transform: uppercase;
    border-radius: 0;
    margin-bottom: 15px;
    border: 1px solid rgba(17, 17, 17, 0.1);
}

.section-title {
    font-size: 2.5rem;
    font-weight: 300;
    color: var(--oneone-black);
    margin-bottom: 0.75rem;
    letter-spacing: -1px;
}

.section-subtitle {
    color: var(--oneone-gray-medium);
    font-size: 1.1rem;
    font-weight: 300;
    margin-bottom: 3rem;
    max-width: 500px;
    margin-left: auto;
    margin-right: auto;
}

/* ==================== */
/* КАРТОЧКИ ТОВАРОВ */
/* ==================== */

.product-card-home {
    background: var(--oneone-white);
    border: 1px solid var(--oneone-gray-border);
    border-radius: 8px;
    overflow: hidden;
    height: 100%;
    display: flex;
    flex-direction: column;
    transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
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
}

.product-card-home:hover .product-card-home__image {
    transform: scale(1.05);
}

.product-card-home__info {
    padding: 22px;
    background: var(--oneone-white);
    border-top: 1px solid rgba(234, 234, 234, 0.8);
}

.product-card-home__title {
    font-size: 0.9rem;
    font-weight: 400;
    line-height: 1.4;
    color: #444;
    margin-bottom: 8px;
    min-height: 2.8em;
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

.product-card-home__price {
    display: flex;
    align-items: baseline;
    flex-wrap: wrap;
    gap: 8px;
    margin-top: 4px;
}

.product-card-home__price-current {
    font-size: 1.15rem;
    font-weight: 600;
    color: var(--oneone-black);
}

.product-card-home__price-old {
    font-size: 0.8rem;
    color: var(--oneone-gray-medium);
    text-decoration: line-through;
    opacity: 0.7;
}

/* Скрываем бейджи на карточках */
.product-card-home__badge,
.product-card-home__badge--new,
.product-card-home__badge--sale,
.product-card-home__badge--popular {
    display: none !important;
}

/* ==================== */
/* БЛОК ПРЕИМУЩЕСТВ */
/* ==================== */

.feature-card {
    text-align: center;
    padding: 2.5rem 1.5rem;
    background: #fff;
    border-radius: 2px;
    height: 100%;
    transition: all 0.4s ease;
    border: 1px solid #f0f0f0;
    position: relative;
    overflow: hidden;
}

.feature-card:hover {
    transform: translateY(-8px);
    border-color: #e0e0e0;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 1px;
    background: #111;
    transform: scaleX(0);
    transition: transform 0.4s ease;
}

.feature-card:hover::before {
    transform: scaleX(1);
}

.feature-icon {
    width: 70px;
    height: 70px;
    background: #f8f8f8;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 1.5rem;
    color: #111;
    font-size: 1.5rem;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon {
    background: #111;
    color: #fff;
    transform: scale(1.05);
}

.feature-title {
    font-size: 1.25rem;
    font-weight: 400;
    color: #111;
    margin-bottom: 1rem;
    letter-spacing: -0.3px;
}

.feature-description {
    color: #666;
    font-size: 0.95rem;
    line-height: 1.6;
    margin-bottom: 1.5rem;
}

.feature-divider {
    width: 40px;
    height: 1px;
    background: #eee;
    margin: 1.5rem auto;
}

.feature-tag {
    display: inline-block;
    padding: 4px 12px;
    background: #f8f8f8;
    color: #666;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 1px;
    text-transform: uppercase;
    border-radius: 2px;
}

/* ==================== */
/* СТИЛЕВЫЕ ГИДЫ */
/* ==================== */

.style-guide-container {
    margin-bottom: 5rem;
    padding-bottom: 3rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
}

.style-guide-container:last-child {
    border-bottom: none;
    margin-bottom: 0;
    padding-bottom: 0;
}

.style-guide-header {
    margin-bottom: 2.5rem;
}

.style-guide-header .style-title {
    font-size: 1.8rem;
    font-weight: 600;
    color: #111;
    letter-spacing: -0.5px;
    margin-bottom: 1rem;
}

.style-guide-header .style-description {
    font-size: 1.05rem;
    line-height: 1.6;
    color: #555;
    max-width: 600px;
}

.style-guide-btn {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    background: #111;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    font-weight: 500;
    font-size: 0.95rem;
    border-radius: 4px;
    transition: all 0.3s ease;
    text-decoration: none;
}

.style-guide-btn:hover {
    background: #333;
    color: white;
    transform: translateX(5px);
}

/* ==================== */
/* АДАПТИВНОСТЬ */
/* ==================== */

@media (max-width: 1200px) {
    .section-title {
        font-size: 2.2rem;
    }
    
    .product-card-home__image-wrapper {
        min-height: 300px;
        padding: 25px;
    }
    
    .product-card-home__image {
        max-height: 270px;
    }
    
    .feature-card {
        padding: 2rem 1.25rem;
    }
}

@media (max-width: 992px) {
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
        font-size: 0.82rem;
    }
    
    .feature-title {
        font-size: 1.15rem;
    }
    
    .style-guide-header .style-title {
        font-size: 1.5rem;
    }
    
    .style-guide-header .style-description {
        font-size: 1rem;
    }
}

@media (max-width: 768px) {
    .section-title {
        font-size: 1.8rem;
    }
    
    .section-badge {
        padding: 8px 20px;
        font-size: 0.7rem;
    }
    
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
        font-size: 0.8rem;
        min-height: 2.2em;
    }
    
    .product-card-home:hover {
        transform: translateY(-6px);
    }
    
    .feature-card {
        margin-bottom: 1rem;
    }
    
    .style-guide-header {
        margin-bottom: 2rem;
    }
    
    .style-guide-header .style-title {
        font-size: 1.3rem;
    }
    
    .style-guide-header .style-description {
        font-size: 0.95rem;
    }
    
    .style-guide-btn {
        padding: 8px 20px;
        font-size: 0.9rem;
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

/* Классы для inline-стилей */
.bg-light-gray {
    background-color: #f9f9f9 !important;
}

.bg-email-section {
    background: linear-gradient(to bottom, #ffffff 0%, #fafafa 100%) !important;
}

.email-card {
    background: #fff;
    border-radius: 2px;
    border: 1px solid #eee;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.02);
}

.email-icon {
    color: #1a1a1a;
    opacity: 0.9;
}

.email-input {
    border-color: #ddd;
    border-radius: 2px 0 0 2px;
    padding-left: 20px;
}

.email-button {
    border-radius: 0 2px 2px 0;
    font-weight: 400;
    letter-spacing: 0.5px;
}

.benefits-title {
    letter-spacing: 1.5px;
    color: #888;
}

.benefit-item {
    font-size: 0.9rem;
}

.poster-carousel {
    position: relative;
    z-index: 1;
    height: 85vh;
    min-height: 600px;
    max-height: 900px;
    overflow: hidden;
}

.poster-carousel .carousel,
.poster-carousel .carousel-inner,
.poster-carousel .carousel-item {
    height: 100%;
}

.poster-wrapper {
    height: 100%;
    display: flex;
    align-items: center;
    position: relative;
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}

.poster-wrapper::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(
        to right,
        rgba(0, 0, 0, 0.65) 0%,
        rgba(0, 0, 0, 0.2) 100%
    );
    z-index: 1;
}

.poster-wrapper .container {
    position: relative;
    z-index: 2;
}

.carousel-item:nth-child(1) .poster-wrapper {
    background-color: #1a1a1a;
    background-image: 
        radial-gradient(circle at 20% 50%, rgba(255,255,255,0.03) 0%, transparent 50%),
        radial-gradient(circle at 80% 20%, rgba(255,255,255,0.02) 0%, transparent 50%);
}

.poster-sale {
    background: linear-gradient(135deg, #1a1a1a 0%, #2d1111 50%, #1a1a1a 100%) !important;
}

.poster-premium {
    background: linear-gradient(135deg, #1a1a1a 0%, #1a1a0f 50%, #1a1a1a 100%) !important;
    background-image: 
        linear-gradient(135deg, #1a1a1a 0%, #1a1a0f 50%, #1a1a1a 100%),
        radial-gradient(circle at 70% 30%, rgba(212, 175, 55, 0.05) 0%, transparent 50%) !important;
}

.poster-badge {
    display: inline-block;
    padding: 10px 24px;
    background: rgba(255, 255, 255, 0.1);
    backdrop-filter: blur(10px);
    border: 1px solid rgba(255, 255, 255, 0.2);
    color: #fff;
    font-size: 0.75rem;
    font-weight: 500;
    letter-spacing: 3px;
    text-transform: uppercase;
    margin-bottom: 25px;
    border-radius: 2px;
}

.poster-title {
    font-size: 4.5rem;
    font-weight: 300;
    line-height: 1.1;
    margin-bottom: 20px;
    letter-spacing: -2px;
    color: #fff;
}

.poster-subtitle {
    font-size: 1.25rem;
    font-weight: 300;
    opacity: 0.85;
    margin-bottom: 30px;
    max-width: 450px;
    line-height: 1.6;
    color: #ccc;
}

.poster-divider {
    width: 60px;
    height: 2px;
    background: rgba(255, 255, 255, 0.4);
    margin-bottom: 20px;
}

.poster-sale-title {
    font-size: 8rem;
    font-weight: 200;
    letter-spacing: -4px;
    line-height: 1;
    color: #dc3545;
    text-shadow: 0 0 80px rgba(220, 53, 69, 0.3);
}

.sale-timer {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 30px;
}

.timer-item {
    text-align: center;
}

.timer-number {
    font-size: 3.5rem;
    font-weight: 200;
    line-height: 1;
    color: #fff;
    font-variant-numeric: tabular-nums;
}

.timer-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 2px;
    color: rgba(255, 255, 255, 0.6);
    margin-top: 8px;
}

.timer-separator {
    font-size: 3rem;
    color: rgba(255, 255, 255, 0.4);
    font-weight: 200;
}

.carousel-control-prev,
.carousel-control-next {
    width: 60px;
    z-index: 10;
}

.carousel-control-prev-icon,
.carousel-control-next-icon {
    width: 40px;
    height: 40px;
    background-size: 20px;
}

.carousel-indicators {
    bottom: 30px;
    z-index: 10;
}

.carousel-indicators button {
    width: 40px;
    height: 2px;
    border: none;
    background: rgba(255, 255, 255, 0.4);
    margin: 0 5px;
    transition: all 0.3s ease;
}

.carousel-indicators button.active {
    width: 60px;
    background: #fff;
}

@media (max-width: 1200px) {
    .poster-title { font-size: 3.5rem; }
    .poster-sale-title { font-size: 6rem; }
}

@media (max-width: 768px) {
    .poster-carousel { height: 60vh; min-height: 400px; }
    .poster-title { font-size: 2.5rem; }
    .poster-sale-title { font-size: 4rem; }
    .timer-number { font-size: 2rem; }
    .poster-badge { padding: 8px 16px; font-size: 0.7rem; }
}

@media (max-width: 576px) {
    .poster-carousel { height: 50vh; min-height: 350px; }
    .poster-title { font-size: 2rem; }
    .poster-sale-title { font-size: 3rem; }
}
</style>
@endsection

@section('content')
    <!-- Карусель плакатов -->
    <section class="poster-carousel">
        <div id="posterCarousel" class="carousel slide carousel-fade" 
             data-bs-ride="carousel" 
             data-bs-interval="5000"
             data-bs-pause="hover"
             data-bs-wrap="true">
            <!-- Индикаторы -->
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#posterCarousel" data-bs-slide-to="0" class="active"></button>
                <button type="button" data-bs-target="#posterCarousel" data-bs-slide-to="1"></button>
                <button type="button" data-bs-target="#posterCarousel" data-bs-slide-to="2"></button>
            </div>
            
            <!-- Слайды -->
            <div class="carousel-inner">
                <!-- Плакат 1: Новая коллекция -->
                <div class="carousel-item active">
                    <div class="poster-wrapper">
                        <div class="container h-100">
                            <div class="row align-items-center h-100">
                                <div class="col-lg-6 text-white">

                                    <div class="poster-badge">НОВАЯ КОЛЛЕКЦИЯ</div>
                                    <h1 class="poster-title">
                                        <span class="text-white">СУЩЕСТВЕННАЯ ЭЛЕГАНТНОСТЬ</span>
                                    </h1>
                                    <p class="poster-subtitle">
                                        Минимализм, который говорит громче слов.
                                    </p>
                                    
                                    <!-- Полоска над кнопкой -->
                                    <div class="poster-divider mb-3"></div>
                                    
                                    <!-- Кнопка с обводкой -->
                                    <a href="/catalog" class="btn btn-outline-light btn-lg px-5 py-3">
                                        ОТКРЫТЬ КОЛЛЕКЦИЮ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
              <!-- Плакат 2: Осенняя распродажа -->
                <div class="carousel-item">
                    <div class="poster-wrapper poster-sale">
                        <div class="container h-100">
                            <div class="row align-items-center h-100 justify-content-center"> <!-- Добавлен justify-content-center -->
                                <div class="col-lg-10 col-xl-8 text-center text-white" style="display: flex; flex-direction: column; align-items: center;"> <!-- Изменен размер колонки -->
                                    <div class="poster-badge">ОСЕННЯЯ РАСПРОДАЖА</div>
                                    <h2 class="poster-sale-title">-40%</h2>
                                    <p class="poster-subtitle" style="text-align: center !important; margin: 0 auto 30px !important; width: 100% !important; display: block !important;">
                                        На пальто, костюмы и верхнюю одежду
                                    </p>
                                    
                                    <div class="sale-timer d-flex justify-content-center gap-4 mt-5 mb-4">
                                        <div class="timer-item">
                                            <div class="timer-number" id="hours">24</div>
                                            <div class="timer-label">часов</div>
                                        </div>
                                        <div class="timer-separator">:</div>
                                        <div class="timer-item">
                                            <div class="timer-number" id="minutes">00</div>
                                            <div class="timer-label">минут</div>
                                        </div>
                                        <div class="timer-separator">:</div>
                                        <div class="timer-item">
                                            <div class="timer-number" id="seconds">00</div>
                                            <div class="timer-label">секунд</div>
                                        </div>
                                    </div>
                                    
                                    <a href="/catalog?category=sale" class="btn btn-outline-light btn-lg px-5 py-3 mt-4">
                                        СМОТРЕТЬ ТОВАРЫ СО СКИДКОЙ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Плакат 3: Премиум качество -->
                <div class="carousel-item">
                    <div class="poster-wrapper poster-premium">
                        <div class="container h-100">
                            <div class="row align-items-center h-100">
                                <div class="col-lg-6 ms-auto text-white">
                                    <div class="poster-badge">ПРЕМИУМ КАЧЕСТВО</div>
                                    <h2 class="poster-title">ЗА<br>ПРЕДЕЛАМИ<br>СОВЕРШЕНСТВА</h2>
                                    <p class="poster-subtitle">
                                        Итальянские ткани, безупречный крой,
                                        внимание к каждой детали.
                                    </p>
                                    <div class="poster-divider"></div>
                                    
                                    <a href="/catalog" class="btn btn-outline-light btn-lg px-5 py-3 mt-4">
                                        ПОСМОТРЕТЬ ПРЕМИУМ
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Кнопки навигации -->
            <button class="carousel-control-prev" type="button" data-bs-target="#posterCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Предыдущий</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#posterCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Следующий</span>
            </button>
        </div>
    </section>

    <!-- Популярные товары -->
    <section class="py-5 py-lg-7 bg-light-subtle">
        <div class="container">
            <div class="row mb-5">
                <div class="col-12 text-center">
                    <span class="section-badge">В ТРЕНДЕ</span>
                    <h2 class="section-title mt-3">ПОПУЛЯРНОЕ</h2>
                    <p class="section-subtitle">Самые востребованные модели этой недели</p>
                </div>
            </div>
            
           @if(isset($featuredProducts) && $featuredProducts->count() > 0)
            <div class="row g-4">
                @foreach($featuredProducts->take(4) as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    @include('catalog.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
            
            <div class="row mt-5 pt-4">
                <div class="col-12 text-center">
                    <a href="/catalog" class="btn btn-dark btn-lg px-5 py-3">
                        Смотреть все товары <i class="bi bi-arrow-right ms-2"></i>
                    </a>
                </div>
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">Популярные товары появятся скоро</p>
            </div>
            @endif
        </div>
    </section>

<!-- Стилевые гиды -->
<section class="py-5 py-lg-7 bg-light-gray">
    <div class="container">
        <div class="row mb-6">
            <div class="col-12 text-center">
                <span class="section-badge">ЭКСПЕРТНЫЕ ГИДЫ</span>
                <h2 class="section-title mt-4">СТИЛЬ ДЛЯ РАЗНЫХ СИТУАЦИЙ</h2>
                <p class="section-subtitle">Подборки от наших стилистов с готовыми решениями</p>
            </div>
        </div>

        <!-- Гид 1: Минимализм -->
        <div class="style-guide-container">
            <div class="style-guide-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="style-title">МИНИМАЛИЗМ</h3>
                        <p class="style-description">
                            Чистые линии, базовые формы, вневременная элегантность. Фундамент гардероба для повседневной носки.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="/catalog?style=minimalism" class="style-guide-btn">
                            Вся коллекция <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Товары для минимализма -->
          @php
    $minimalismProducts = App\Models\Product::with('images')
        ->where('style', 'like', '%Минимализм%')
        ->where('stock', '>', 0)
        ->where('is_variant', 1) // ← ДОБАВЬТЕ ЭТУ СТРОЧКУ
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();
@endphp
            
            @if($minimalismProducts->count() > 0)
            <div class="row g-4">
                @foreach($minimalismProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    @include('catalog.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">Товары для этого стиля появятся скоро</p>
            </div>
            @endif
        </div>

        <!-- Гид 2: Деловой стиль -->
        <div class="style-guide-container">
            <div class="style-guide-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="style-title">ДЕЛОВОЙ СТИЛЬ</h3>
                        <p class="style-description">
                            Идеальные костюмы и элегантные решения для офиса и важных встреч. Профессиональный образ, который впечатляет.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="/catalog?style=business" class="style-guide-btn">
                            Вся коллекция <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Товары для делового стиля -->
                @php
    $businessProducts = App\Models\Product::with('images')
        ->where('style', 'like', '%Офисный%')
        ->where('stock', '>', 0)
        ->where('is_variant', 1) // ← Берем только вариации (у них есть image)
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();
@endphp
            
            @if($businessProducts->count() > 0)
            <div class="row g-4">
                @foreach($businessProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    @include('catalog.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">Товары для этого стиля появятся скоро</p>
            </div>
            @endif
        </div>

        <!-- Гид 3: Вечерний стиль -->
        <div class="style-guide-container">
            <div class="style-guide-header">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h3 class="style-title">ВЕЧЕРНИЙ СТИЛЬ</h3>
                        <p class="style-description">
                            Элегантность для особых случаев и торжественных мероприятий. Создайте незабываемое впечатление.
                        </p>
                    </div>
                    <div class="col-md-4 text-md-end">
                        <a href="/catalog?style=evening" class="style-guide-btn">
                            Вся коллекция <i class="bi bi-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Товары для вечернего стиля -->
           @php
    $eveningProducts = App\Models\Product::with('images')
        ->where('style', 'like', '%Вечерний%')
        ->where('stock', '>', 0)
        ->where('is_variant', 1) // ← ДОБАВЬТЕ ЭТУ СТРОЧКУ
        ->orderBy('created_at', 'desc')
        ->take(4)
        ->get();
@endphp
            
            @if($eveningProducts->count() > 0)
            <div class="row g-4">
                @foreach($eveningProducts as $product)
                <div class="col-xl-3 col-lg-4 col-md-6">
                    @include('catalog.partials.product-card', ['product' => $product])
                </div>
                @endforeach
            </div>
            @else
            <div class="text-center py-5">
                <p class="text-muted">Товары для этого стиля появятся скоро</p>
            </div>
            @endif
        </div>

        <!-- Кнопка внизу -->
        <div class="row mt-6 pt-3">
            <div class="col-12 text-center">
                <a href="/catalog" class="btn btn-outline-dark btn-lg px-5 py-3">
                    <i class="bi bi-grid me-2"></i> Смотреть все коллекции
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Преимущества -->
<section class="py-6 py-lg-8" style="margin-top: 60px;"> <!-- ← ДОБАВЬТЕ ЭТУ СТРОЧКУ -->
    <div class="container">
        <div class="row mb-6">
            <div class="col-12 text-center">
                <span class="section-badge">ФИЛОСОФИЯ БРЕНДА</span>
                <h2 class="section-title mt-4">ПРИНЦИПЫ ONEONE</h2>
                <p class="section-subtitle">Основа, на которой строится каждое изделие</p>
            </div>
        </div>
        
        <div class="row g-4 g-lg-5">
            <div class="col-xl-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-award"></i>
                    </div>
                    <h4 class="feature-title">Бескомпромиссное качество</h4>
                    <p class="feature-description">Итальянские ткани, идеальный крой и внимание к каждой детали — основа нашей работы.</p>
                    <div class="feature-divider"></div>
                    <span class="feature-tag bg-dark text-white">Качество</span>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-scissors"></i>
                    </div>
                    <h4 class="feature-title">Совершенство в простоте</h4>
                    <p class="feature-description">Вневременные силуэты и чистые линии, которые остаются актуальными из сезона в сезон.</p>
                    <div class="feature-divider"></div>
                    <span class="feature-tag bg-dark text-white">Минимализм</span>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-heart"></i>
                    </div>
                    <h4 class="feature-title">Осознанное производство</h4>
                    <p class="feature-description">Используем натуральные материалы и создаем одежду, уважая природу и будущее.</p>
                    <div class="feature-divider"></div>
                    <span class="feature-tag bg-dark text-white">Устойчивость</span>
                </div>
            </div>
            
            <div class="col-xl-3 col-md-6">
                <div class="feature-card">
                    <div class="feature-icon">
                        <i class="bi bi-truck"></i>
                    </div>
                    <h4 class="feature-title">Забота о клиенте</h4>
                    <p class="feature-description">Бесплатная доставка от 15 000 ₽ и индивидуальный сервис на каждом этапе покупки.</p>
                    <div class="feature-divider"></div>
                    <span class="feature-tag bg-dark text-white">Сервис</span>
                </div>
            </div>
        </div>
        
        <div class="row mt-6 pt-5">
            <div class="col-12 text-center">
                <p class="text-dark small text-uppercase" style="letter-spacing: 3px; font-weight: 600;">Less but better</p>
            </div>
        </div>
    </div>
</section>

<!-- Email рассылка -->
<section class="py-6 py-lg-8 bg-email-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10 col-xl-8">
                <div class="text-center p-4 p-lg-5 email-card">
                    <div class="mb-1">
                        <i class="bi bi-envelope-open display-4 email-icon"></i>
                    </div>
                    <h2 class="h3 fw-normal mt-4 mb-3">Присоединитесь к нашему сообществу</h2>
                    <p class="text-muted mb-4 mx-auto">
                        Получайте эксклюзивный доступ к ранним релизам коллекций, приватным мероприятиям и редакционным материалам о стиле.
                    </p>
                    
                    <form id="subscribeForm" class="row g-3 justify-content-center mt-4">
                        @csrf
                        <div class="col-lg-8 col-md-10">
                            <div class="input-group">
                                <input type="email" 
                                       class="form-control form-control-lg border-end-0 email-input" 
                                       placeholder="Ваш email адрес"
                                       required>
                                <button class="btn btn-dark px-4 email-button" type="submit">
                                    Подписаться
                                </button>
                            </div>
                            <div class="form-text text-start mt-2 small">
                                <i class="bi bi-shield-check me-1"></i> Конфиденциальность гарантирована. Отписка в один клик.
                            </div>
                        </div>
                    </form>
                    
                    <div class="mt-5 pt-4 border-top">
                        <p class="small text-uppercase mb-3 benefits-title">Ваши преимущества</p>
                        <div class="row justify-content-center g-3">
                            <div class="col-auto">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2 benefit-item"></i>
                                    <span class="small">Первые уведомления о скидках</span>
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2 benefit-item"></i>
                                    <span class="small">Гид по стилю от ONEONE</span>
                                </span>
                            </div>
                            <div class="col-auto">
                                <span class="d-inline-flex align-items-center">
                                    <i class="bi bi-check-circle me-2 benefit-item"></i>
                                    <span class="small">Приглашения на события</span>
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const carouselElement = document.getElementById('posterCarousel');
        const CAROUSEL_INTERVAL = 4000;
        
        if (carouselElement) {
            const carousel = new bootstrap.Carousel(carouselElement, {
                interval: CAROUSEL_INTERVAL,
                wrap: true,
                pause: 'hover',
                touch: true,
                ride: 'carousel'
            });
        }
        
        // ТАЙМЕР ОБРАТНОГО ОТСЧЕТА (добавлено)

        function startSaleTimer() {
            const endTime = new Date();
            endTime.setHours(endTime.getHours() + 24);
            
            function updateTimer() {
                const now = new Date();
                const timeLeft = endTime - now;
                
                if (timeLeft <= 0) {
                    document.getElementById('hours').textContent = '00';
                    document.getElementById('minutes').textContent = '00';
                    document.getElementById('seconds').textContent = '00';
                    return;
                }
                const hours = Math.floor((timeLeft % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                const minutes = Math.floor((timeLeft % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((timeLeft % (1000 * 60)) / 1000);

                document.getElementById('hours').textContent = hours.toString().padStart(2, '0');
                document.getElementById('minutes').textContent = minutes.toString().padStart(2, '0');
                document.getElementById('seconds').textContent = seconds.toString().padStart(2, '0');
            }
            updateTimer();
            setInterval(updateTimer, 1000);
        }
        
        // Запускаем таймер если есть элементы таймера на странице
        if (document.getElementById('hours') && document.getElementById('minutes') && document.getElementById('seconds')) {
            startSaleTimer();
        }
        
        const subscribeForm = document.getElementById('subscribeForm');
        const emailInput = subscribeForm?.querySelector('input[type="email"]');
        let isSubmitting = false;
        
        function isValidEmail(email) {
            const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return re.test(email);
        }
        
        if (subscribeForm) {
            subscribeForm.addEventListener('submit', function(e) {
                e.preventDefault();
                
                if (isSubmitting) return;
                
                const emailValue = emailInput?.value.trim();
                const submitBtn = subscribeForm.querySelector('button[type="submit"]');
                
                if (!emailValue) {
                    showError(emailInput, 'Введите email адрес');
                    return;
                }
                
                if (!isValidEmail(emailValue)) {
                    showError(emailInput, 'Введите корректный email');
                    return;
                }
                
                clearError(emailInput);
                isSubmitting = true;
                const originalText = submitBtn.textContent;
                submitBtn.disabled = true;
                
                submitBtn.textContent = 'Подписываем...';
                submitBtn.classList.remove('btn-success');
                
                setTimeout(() => {
                    submitBtn.textContent = 'Подписано!';
                    submitBtn.classList.add('btn-success');
                    emailInput.value = '';
                    
                    setTimeout(() => {
                        submitBtn.textContent = originalText;
                        submitBtn.classList.remove('btn-success');
                        submitBtn.disabled = false;
                        isSubmitting = false;
                    }, 3000);
                }, 1000);
            });
            
            function showError(input, message) {
                input.classList.add('is-invalid');
                const errorElement = input.parentElement.querySelector('.invalid-feedback');
                if (errorElement) {
                    errorElement.textContent = message;
                }
                input.focus();
            }
            
            function clearError(input) {
                input.classList.remove('is-invalid');
            }
        }
        
        // ФИКС для выпадающего меню профиля (добавлено)
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            dropdown.style.zIndex = '9999';
        });
    });
</script>
@endsection