@extends('layouts.layout')

@section('title')
    @parent ONEONE - Минималистичная женская одежда премиум-класса
@endsection

@section('styles')
<style>
/* 🔥 ФОНЫ С АНИМАЦИЕЙ */
.carousel-item:nth-child(1) .poster-wrapper {
    background: linear-gradient(135deg, #1a0a0f 0%, #2d1118 30%, #3a1520 60%, #2d1118 100%);
    background-size: 400% 400%;
    animation: bgShimmer1 8s ease infinite;
}
.poster-sale {
    background: linear-gradient(135deg, #1A1A1A 0%, #3A1111 50%, #1A1A1A 100%);
    background-size: 200% 200%;
    animation: bgPulse 4s ease-in-out infinite alternate;
}
.poster-premium {
    background: linear-gradient(135deg, #1a0a0a 0%, #2d1111 30%, #3a1a1a 60%, #2d1111 100%);
    background-size: 400% 400%;
    animation: bgShimmer2 10s ease infinite;
}

@keyframes bgShimmer1 {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}
@keyframes bgPulse {
    0% { background-position: 0% 50%; }
    100% { background-position: 100% 50%; }
}
@keyframes bgShimmer2 {
    0% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
    100% { background-position: 0% 50%; }
}

/* Свечение заголовка SALE */
.poster-sale-title {
    animation: saleGlow 2s ease-in-out infinite alternate;
}
@keyframes saleGlow {
    from { text-shadow: 0 0 30px rgba(255,71,87,0.3), 0 0 60px rgba(255,71,87,0.1); }
    to { text-shadow: 0 0 50px rgba(255,71,87,0.5), 0 0 100px rgba(255,71,87,0.2); }
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
                
                // 🔥 ОТПРАВКА НА СЕРВЕР
                fetch('{{ route("subscribe") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({ email: emailValue })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        submitBtn.textContent = '✓ Подписано!';
                        submitBtn.classList.add('btn-success');
                        emailInput.value = '';
                        showToast(data.message);
                    } else {
                        showError(emailInput, data.message || 'Ошибка');
                        submitBtn.textContent = originalText;
                        submitBtn.disabled = false;
                        isSubmitting = false;
                    }
                })
                .catch(error => {
                    console.error('Ошибка:', error);
                    showError(emailInput, 'Ошибка соединения');
                    submitBtn.textContent = originalText;
                    submitBtn.disabled = false;
                    isSubmitting = false;
                });
                
                // Восстановление через 3 секунды
                setTimeout(() => {
                    if (isSubmitting) {
                        submitBtn.textContent = originalText;
                        submitBtn.classList.remove('btn-success');
                        submitBtn.disabled = false;
                        isSubmitting = false;
                    }
                }, 3000);
            });
        }

        function showError(input, message) {
            input.classList.add('is-invalid');
            const errorElement = input.parentElement.querySelector('.invalid-feedback');
            if (errorElement) {
                errorElement.textContent = message;
            } else {
                const error = document.createElement('div');
                error.className = 'invalid-feedback';
                error.textContent = message;
                input.parentElement.appendChild(error);
            }
            input.focus();
        }

        function clearError(input) {
            input.classList.remove('is-invalid');
            const errorElement = input.parentElement.querySelector('.invalid-feedback');
            if (errorElement) errorElement.remove();
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        
        // ФИКС для выпадающего меню профиля (добавлено)
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            dropdown.style.zIndex = '9999';
        });
    });
</script>
@endsection