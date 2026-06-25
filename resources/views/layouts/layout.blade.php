<!doctype html>
<html lang="ru">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="ONEONE - минималистичная женская одежда премиум-класса. Черно-белая эстетика, качественные материалы, безупречный крой.">
    <meta name="author" content="ONEONE - премиум бренд женской одежды">
    <meta name="generator" content="Hugo 0.84.0">
    <link rel="icon" href="{{ asset('images/logo.svg') }}" type="image/svg+xml">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@section('title') @show | ONEONE</title>

    <!-- Bootstrap core CSS -->
    <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    
    <!-- Google Fonts - Только Montserrat -->
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Custom css -->
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <meta name="theme-color" content="#111111">
    
<style>
        :root {
            --oneone-black: #111111;
            --oneone-white: #ffffff;
            --oneone-gray: #f5f5f5;
            --oneone-dark-gray: #333333;
            --oneone-light-gray: #e0e0e0;
            --oneone-gradient: linear-gradient(135deg, #111111 0%, #333333 100%);
            --oneone-gradient-light: linear-gradient(135deg, #f5f5f5 0%, #ffffff 100%);
        }
        
        body {
            font-family: 'Montserrat', sans-serif;
            background-color: var(--oneone-white);
            color: var(--oneone-dark-gray);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        main {
            flex: 1;
        }
        
        .oneone-gradient {
            background: var(--oneone-gradient);
        }
        
        .oneone-gradient-light {
            background: var(--oneone-gradient-light);
        }
        
        .text-oneone-black {
            color: var(--oneone-black);
        }
        
        .bg-oneone-gray {
            background-color: var(--oneone-gray);
        }
        
        .border-oneone {
            border-color: var(--oneone-light-gray);
        }
        
        /* Навигация */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
            background-color: rgba(255, 255, 255, 0.95) !important;
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border-bottom: 1px solid var(--oneone-light-gray);
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.03);
        }
        
        .navbar-brand {
            font-weight: 800;
            letter-spacing: -0.5px;
            font-size: 1.8rem;
        }
        
        .nav-link {
            font-weight: 500;
            transition: all 0.3s ease;
            color: var(--oneone-dark-gray) !important;
        }
        
        .nav-link:hover {
            color: var(--oneone-black) !important;
            transform: translateY(-1px);
        }
        
        .navbar-toggler {
            border: none;
        }
        
        .navbar-toggler:focus {
            box-shadow: none;
        }
        
        /* Иконки в навигации */
        .nav-icon {
            position: relative;
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
            cursor: pointer;
        }
        
        .nav-icon:hover {
            background-color: var(--oneone-gray);
        }
        
        .icon-badge {
            position: absolute;
            top: -5px;
            right: -5px;
            background: var(--oneone-gradient);
            color: white;
            font-size: 0.7rem;
            min-width: 18px;
            height: 18px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        /* Бургер для каталога - ТРИ ЧЕТКИЕ ГОРИЗОНТАЛЬНЫЕ ПОЛОСКИ С РАССТОЯНИЕМ */
        .catalog-burger {
            margin-right: 10px;
            padding: 12px 10px;
            border: none;
            background: none;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: center;
            width: 40px;
            height: 40px;
            transition: all 0.3s ease;
            border-radius: 4px;
            position: relative;
        }
        
        .catalog-burger:hover {
            background-color: var(--oneone-gray);
        }
        
        .catalog-burger span {
            display: block;
            width: 20px;
            height: 2px;
            background-color: var(--oneone-black);
            transition: all 0.3s ease;
            border-radius: 0;
        }
        
        /* Первая полоска (верхняя) */
        .catalog-burger span:nth-child(1) {
            margin-top: 0;
        }
        
        /* Вторая полоска (средняя) */
        .catalog-burger span:nth-child(2) {
            margin: 6px 0;
        }
        
        /* Третья полоска (нижняя) */
        .catalog-burger span:nth-child(3) {
            margin-bottom: 0;
        }
        
        .catalog-burger:hover span {
            background-color: var(--oneone-black);
        }
        
        /* Анимация превращения в крестик при открытии */
        .catalog-burger.active span:nth-child(1) {
            transform: rotate(45deg) translate(5px, 5px);
            width: 20px;
        }
        
        .catalog-burger.active span:nth-child(2) {
            opacity: 0;
            transform: translateX(-10px);
        }
        
        .catalog-burger.active span:nth-child(3) {
            transform: rotate(-45deg) translate(7px, -6px);
            width: 20px;
        }
        
        /* Sidebar каталога */
        .catalog-sidebar {
            position: fixed;
            top: 0;
            left: -400px;
            width: 350px;
            height: 100vh;
            background: white;
            box-shadow: 2px 0 20px rgba(0,0,0,0.1);
            z-index: 1100;
            transition: left 0.3s ease;
            overflow-y: auto;
            padding: 20px 0;
        }
        
        .catalog-sidebar.open {
            left: 0;
        }
        
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1099;
            display: none;
        }
        
        .sidebar-overlay.show {
            display: block;
        }
        
        .sidebar-header {
            padding: 0 25px 20px;
            border-bottom: 1px solid var(--oneone-light-gray);
            margin-bottom: 20px;
        }
        
        .sidebar-header h3 {
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .close-sidebar {
            position: absolute;
            right: 20px;
            top: 20px;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--oneone-dark-gray);
            width: 40px;
            height: 40px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: all 0.3s ease;
        }
        
        .close-sidebar:hover {
            background-color: var(--oneone-gray);
            color: var(--oneone-black);
        }
        
        .sidebar-category {
            padding: 0 25px;
            margin-bottom: 15px;
        }
        
        .sidebar-category h4 {
            font-weight: 600;
            font-size: 0.95rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 10px;
            color: var(--oneone-black);
        }
        
        .sidebar-category ul {
            list-style: none;
            padding-left: 15px;
            margin-bottom: 0;
        }
        
        .sidebar-category li {
            margin-bottom: 8px;
        }
        
        .sidebar-category a {
            color: var(--oneone-dark-gray);
            text-decoration: none;
            font-weight: 500;
            font-size: 0.95rem;
            transition: color 0.3s ease;
            display: block;
            padding: 5px 0;
        }
        
        .sidebar-category a:hover {
            color: var(--oneone-black);
            padding-left: 5px;
        }
        
        .category-divider {
            border-top: 1px solid var(--oneone-light-gray);
            margin: 20px 25px;
        }
        
        /* Профиль dropdown */
        .profile-dropdown {
            position: relative;
            z-index: 1200; /* Увеличен с 1000 */
        }
        
        .dropdown-menu-profile {
            min-width: 220px;
            padding: 0;
            border: 1px solid var(--oneone-light-gray);
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
             z-index: 1201 !important; /* Добавлено !important */
        }
        
        .dropdown-menu-profile .dropdown-item {
            padding: 10px 15px;
            border-bottom: 1px solid var(--oneone-gray);
            transition: all 0.2s ease;
        }
        
        .dropdown-menu-profile .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-menu-profile .dropdown-item:hover {
            background-color: var(--oneone-gray);
        }
        
        .dropdown-header {
            background-color: var(--oneone-gray);
            padding: 10px 15px;
            font-weight: 600;
            border-bottom: 1px solid var(--oneone-light-gray);
        }
        
        /* Стили для поискового окна */
        .search-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1101;
            display: none;
            backdrop-filter: blur(2px);
        }
        
        .search-overlay.show {
            display: block;
        }
        
        .search-modal {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            z-index: 1102;
            transform: translateY(-100%);
            transition: transform 0.3s ease;
            padding: 15px 0;
            border-bottom: 1px solid var(--oneone-light-gray);
        }
        
        .search-modal.show {
            transform: translateY(0);
        }
        
        .search-modal-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 20px;
        }
        
        .search-form {
            display: flex;
            gap: 10px;
            align-items: center;
        }
        
        .search-input {
            flex: 1;
            border: 1px solid var(--oneone-light-gray);
            border-radius: 4px;
            padding: 12px 20px;
            font-size: 1rem;
            font-family: 'Montserrat', sans-serif;
            transition: all 0.3s ease;
            background: var(--oneone-gray);
        }
        
        .search-input:focus {
            outline: none;
            border-color: var(--oneone-black);
            background: white;
            box-shadow: 0 0 0 2px rgba(17, 17, 17, 0.1);
        }
        
        .search-input::placeholder {
            color: #888;
            font-weight: 500;
        }
        
        .search-submit {
            background: var(--oneone-gradient);
            border: none;
            color: white;
            font-size: 1.2rem;
            cursor: pointer;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 4px;
            transition: all 0.3s ease;
        }
        
        .search-submit:hover {
            opacity: 0.9;
            transform: translateY(-1px);
        }
        
        /* Плавная прокрутка */
        html {
            scroll-behavior: smooth;
        }
        
        /* Адаптивность */
        @media (max-width: 768px) {
            .catalog-sidebar {
                width: 300px;
                left: -300px;
            }
            
            .nav-icon {
                width: 35px;
                height: 35px;
            }
            
            .navbar-brand {
                font-size: 1.5rem;
            }
            
            .catalog-burger {
                width: 35px;
                height: 35px;
                padding: 10px 8px;
                margin-right: 5px;
            }
            
            .catalog-burger span {
                width: 18px;
                height: 2px;
            }
            
            .catalog-burger span:nth-child(2) {
                margin: 5px 0;
            }
            
            .search-modal {
                padding: 10px 0;
            }
            
            .search-input {
                padding: 10px 15px;
                font-size: 0.95rem;
            }
            
            .search-submit {
                width: 45px;
                height: 45px;
                font-size: 1.1rem;
            }
        }
        
        @media (max-width: 576px) {
            .catalog-sidebar {
                width: 280px;
                left: -280px;
            }
            
            .catalog-burger {
                width: 32px;
                height: 32px;
                padding: 8px 6px;
            }
            
            .catalog-burger span {
                width: 16px;
                height: 2px;
            }
            
            .catalog-burger span:nth-child(2) {
                margin: 4px 0;
            }
            
            .search-submit {
                width: 40px;
                height: 40px;
                font-size: 1rem;
            }
        }
        /* ФИКС: Выпадающее меню профиля поверх всего */
    .profile-dropdown .dropdown-menu {
        z-index: 9999 !important;
        position: absolute !important;
    }

    /* Навигация должна быть выше карусели */
    .navbar {
        z-index: 1000 !important;
        position: relative !important;
    }

    /* Уменьшаем z-index для всей карусели и её элементов */
    .poster-carousel,
    #posterCarousel,
    .carousel,
    .carousel-inner,
    .carousel-item,
    .poster-wrapper {
        position: relative !important;
        z-index: 1 !important;
    }

    /* Оверлей карусели должен быть низким */
    .poster-wrapper::before {
        z-index: 1 !important;
    }

    /* Для мобильных - то же самое */
    @media (max-width: 768px) {
        .profile-dropdown .dropdown-menu {
            z-index: 9999 !important;
        }
        
        .navbar {
            z-index: 1000 !important;
        }
    }

    /* Модальное окно выбора размера из карточки */
.sizes-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.size-option-btn {
    padding: 10px 18px;
    border: 2px solid #e0e0e0;
    background: white;
    border-radius: 8px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
    font-size: 0.95rem;
}

.size-option-btn:hover:not(.disabled) {
    border-color: #111;
    background: #f8f8f8;
}

.size-option-btn.selected {
    border-color: #111;
    background: #111;
    color: white;
}

.size-option-btn.disabled {
    opacity: 0.4;
    cursor: not-allowed;
    text-decoration: line-through;
}

.size-value {
    font-size: 1rem;
    font-weight: 600;
}

</style>
</head>

<body>
    <!-- Overlay для затемнения фона при поиске -->
    <div class="search-overlay" id="searchOverlay"></div>
    
    <!-- Модальное окно поиска -->
    <div class="search-modal" id="searchModal">
        <div class="search-modal-container">
            <form class="search-form" id="searchForm">
                <input 
                    type="text" 
                    class="search-input" 
                    id="searchInput"
                    placeholder="ПОИСК ТОВАРОВ..."
                    autocomplete="off"
                >
                <button type="submit" class="search-submit" id="searchSubmit">
                    <i class="bi bi-search"></i>
                </button>
            </form>
        </div>
    </div>
    
   <!-- Sidebar каталога -->
    <div class="catalog-sidebar" id="catalogSidebar">
        <button class="close-sidebar" id="closeSidebar">
            <i class="bi bi-x-lg"></i>
        </button>
        
        <div class="sidebar-header">
            <h3>КАТАЛОГ</h3>
        </div>
        
        <!-- ВЕРХНЯЯ ОДЕЖДА -->
<div class="sidebar-category">
    <h4>ВЕРХНЯЯ ОДЕЖДА</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=outerwear">Все модели</a></li>
        <li><a href="{{ route('catalog') }}?categories=outerwear">Пальто</a></li>
        <li><a href="{{ route('catalog') }}?categories=outerwear">Куртки</a></li>
        <li><a href="{{ route('catalog') }}?categories=outerwear">Плащи</a></li>
    </ul>
</div>

<!-- ПЛАТЬЯ -->
<div class="sidebar-category">
    <h4>ПЛАТЬЯ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=dresses">Все платья</a></li>
        <li><a href="{{ route('catalog') }}?categories=dresses">Вечерние</a></li>
        <li><a href="{{ route('catalog') }}?categories=dresses">Повседневные</a></li>
        <li><a href="{{ route('catalog') }}?categories=dresses">Офисные</a></li>
    </ul>
</div>

<!-- БЛЕЙЗЕРЫ -->
<div class="sidebar-category">
    <h4>БЛЕЙЗЕРЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=blazers">Все блейзеры</a></li>
        <li><a href="{{ route('catalog') }}?categories=blazers">Классические</a></li>
        <li><a href="{{ route('catalog') }}?categories=blazers">Оверсайз</a></li>
    </ul>
</div>

<!-- Разделитель -->
<div class="category-divider"></div>

<!-- РУБАШКИ/БЛУЗЫ -->
<div class="sidebar-category">
    <h4>РУБАШКИ/БЛУЗЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=shirts-blouses">Все модели</a></li>
        <li><a href="{{ route('catalog') }}?categories=shirts-blouses">Классические рубашки</a></li>
        <li><a href="{{ route('catalog') }}?categories=shirts-blouses">Шелковые блузы</a></li>
    </ul>
</div>

<!-- БРЮКИ -->
<div class="sidebar-category">
    <h4>БРЮКИ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=pants">Все брюки</a></li>
        <li><a href="{{ route('catalog') }}?categories=pants">Широкие</a></li>
        <li><a href="{{ route('catalog') }}?categories=pants">Классические</a></li>
    </ul>
</div>

<!-- ТОПЫ -->
<div class="sidebar-category">
    <h4>ТОПЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=tops">Все топы</a></li>
        <li><a href="{{ route('catalog') }}?categories=tops">Кроп-топы</a></li>
    </ul>
</div>

<!-- Разделитель -->
<div class="category-divider"></div>

<!-- ЮБКИ/ШОРТЫ -->
<div class="sidebar-category">
    <h4>ЮБКИ/ШОРТЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=skirts-shorts">Все модели</a></li>
        <li><a href="{{ route('catalog') }}?categories=skirts">Юбки миди</a></li>
        <li><a href="{{ route('catalog') }}?categories=skirts-shorts">Шорты</a></li>
    </ul>
</div>

<!-- ДЖИНСЫ -->
<div class="sidebar-category">
    <h4>ДЖИНСЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=jeans">Все джинсы</a></li>
        <li><a href="{{ route('catalog') }}?categories=jeans">Скинни</a></li>
        <li><a href="{{ route('catalog') }}?categories=jeans">Прямые</a></li>
    </ul>
</div>

<!-- ОБУВЬ -->
<div class="sidebar-category">
    <h4>ОБУВЬ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=shoes">Вся обувь</a></li>
        <li><a href="{{ route('catalog') }}?categories=shoes">Ботинки</a></li>
        <li><a href="{{ route('catalog') }}?categories=shoes">Туфли</a></li>
    </ul>
</div>

<!-- Разделитель -->
<div class="category-divider"></div>

<!-- СУМКИ -->
<div class="sidebar-category">
    <h4>СУМКИ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=bags">Все сумки</a></li>
        <li><a href="{{ route('catalog') }}?categories=bags">Тотесы</a></li>
        <li><a href="{{ route('catalog') }}?categories=bags">Клатчи</a></li>
    </ul>
</div>

<!-- АКСЕССУАРЫ -->
<div class="sidebar-category">
    <h4>АКСЕССУАРЫ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=accessories">Все аксессуары</a></li>
        <li><a href="{{ route('catalog') }}?categories=accessories">Украшения</a></li>
        <li><a href="{{ route('catalog') }}?categories=accessories">Ремни</a></li>
    </ul>
</div>

<!-- Разделитель -->
<div class="category-divider"></div>

<!-- НИЖНЕЕ БЕЛЬЕ -->
<div class="sidebar-category">
    <h4>НИЖНЕЕ БЕЛЬЕ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?categories=lingerie">Все белье</a></li>
        <li><a href="{{ route('catalog') }}?categories=lingerie">Для дома</a></li>
    </ul>
</div>

<!-- РАСПРОДАЖА -->
<div class="sidebar-category">
    <h4>РАСПРОДАЖА</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?is_on_sale=1">Все товары со скидкой</a></li>
        <li><a href="{{ route('catalog') }}?categories=dresses&is_on_sale=1">Платья</a></li>
        <li><a href="{{ route('catalog') }}?categories=tops&is_on_sale=1">Топы</a></li>
    </ul>
</div>

<!-- НОВИНКИ -->
<div class="sidebar-category">
    <h4>НОВИНКИ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?is_new=1">Все новинки</a></li>
    </ul>
</div>

<!-- ПОПУЛЯРНОЕ -->
<div class="sidebar-category">
    <h4>ПОПУЛЯРНОЕ</h4>
    <ul>
        <li><a href="{{ route('catalog') }}?sort=popular">Популярные товары</a></li>
        <li><a href="{{ route('catalog') }}?sort=rating_desc">По рейтингу</a></li>
    </ul>
</div>
    </div>
    
    <!-- Overlay для закрытия sidebar -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <header>
        <!-- Основная навигационная панель -->
        <nav class="navbar navbar-expand-lg navbar-light bg-white py-3">
            <div class="container">
                <!-- Логотип слева -->
                <a href="{{ route('home') }}" class="navbar-brand text-oneone-black me-4">
                    <strong>ONEONE</strong>
                </a>
                
                <!-- Бургер каталога справа (для мобильных) -->
                <button class="catalog-burger d-lg-none" id="catalogToggleMobile">
                    <span></span>
                    <span></span>
                    <span></span>
                </button>

                <!-- Правые иконки -->
                <div class="nav-icons-container d-flex align-items-center ms-auto">
                    <!-- Поиск -->
                    <div class="nav-icon me-3 position-relative search-icon" id="searchIcon">
                        <i class="bi bi-search text-oneone-black"></i>
                    </div>
                    
                <!-- Избранное -->
                <div class="nav-icon me-3 position-relative">
                    <a href="{{ route('wishlist.index') }}" class="text-decoration-none text-oneone-black d-flex align-items-center justify-content-center">
                        <i class="bi bi-heart"></i>
                        @auth
                            @php
                                $wishlistCount = auth()->user()->wishlistItems()->count();
                            @endphp
                            @if($wishlistCount > 0)
                                <span class="icon-badge wishlist-badge">{{ $wishlistCount }}</span>
                            @endif
                        @endauth
                    </a>
                </div>
                    
                    <!-- Корзина -->
                    <div class="nav-icon me-3 position-relative">
                        <a href="{{ route('cart.index') }}" class="text-decoration-none text-oneone-black d-flex align-items-center justify-content-center">
                            <i class="bi bi-bag"></i>
                            @php
                                $cartCount = \App\Helpers\CartHelper::getCartCount();
                            @endphp
                            @if($cartCount > 0)
                                <span class="icon-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </div>
                    
                    <!-- Бургер каталога для десктопов - ТРИ ГОРИЗОНТАЛЬНЫЕ ЧЕРНЫЕ ПОЛОСКИ -->
                    <button class="catalog-burger me-3 d-none d-lg-block" id="catalogToggleDesktop">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>
                    
                    <!-- Профиль dropdown -->
                    <div class="profile-dropdown">
                        <div class="nav-icon" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-person text-oneone-black"></i>
                        </div>
                        
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-profile" aria-labelledby="profileDropdown">
                            @auth
                                <li class="dropdown-header">
                                    Привет, {{ auth()->user()->name }}!
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('profile') }}">
                                        <i class="bi bi-person-circle me-2"></i>Профиль
                                    </a>
                                </li>
                                <li>
                                        <a class="dropdown-item" href="{{ route('orders.history') }}">
                                            <i class="bi bi-box-seam me-2"></i>Мои заказы
                                        </a>
                                </li>
                                <li>
                                   <!-- Избранное -->
                                <div class="nav-icon me-3 position-relative">
                                    <a href="{{ route('wishlist.index') }}" class="text-decoration-none text-oneone-black d-flex align-items-center justify-content-center">
                                        <i class="bi bi-heart"></i>
                                        @auth
                                            @php
                                                $wishlistCount = auth()->user()->wishlistItems()->count();
                                            @endphp
                                            @if($wishlistCount > 0)
                                                <span class="icon-badge wishlist-badge">{{ $wishlistCount }}</span>
                                            @endif
                                        @endauth
                                    </a>
                                </div>
                                </li>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('logout') }}">
                                        <i class="bi bi-box-arrow-right me-2"></i>Выйти
                                    </a>
                                </li>
                            @else
                                <li class="dropdown-header">
                                    Войдите в аккаунт
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('login') }}">
                                        <i class="bi bi-box-arrow-in-right me-2"></i>Войти
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus me-2"></i>Регистрация
                                    </a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>

                
            </div>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <!-- Футер -->
    @include('layouts.footer')

     <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    
    <script>
        function openCatalogSidebar() {
            document.getElementById('catalogSidebar').classList.add('open');
            document.getElementById('sidebarOverlay').classList.add('show');
            document.body.style.overflow = 'hidden';
            document.getElementById('catalogToggleDesktop').classList.add('active');
            document.getElementById('catalogToggleMobile').classList.add('active');
        }
        function closeCatalogSidebar() {
            document.getElementById('catalogSidebar').classList.remove('open');
            document.getElementById('sidebarOverlay').classList.remove('show');
            document.body.style.overflow = '';
            document.getElementById('catalogToggleDesktop').classList.remove('active');
            document.getElementById('catalogToggleMobile').classList.remove('active');
        }
        
        // Открытие поискового окна
        function openSearchModal() {
            const searchModal = document.getElementById('searchModal');
            const searchOverlay = document.getElementById('searchOverlay');
            const searchInput = document.getElementById('searchInput');
            
            searchModal.classList.add('show');
            searchOverlay.classList.add('show');
            document.body.style.overflow = 'hidden';
            
            // Фокус на поле ввода
            setTimeout(() => {
                searchInput.focus();
            }, 300);
        }
        
        // Закрытие поискового окна
        function closeSearchModal() {
            const searchModal = document.getElementById('searchModal');
            const searchOverlay = document.getElementById('searchOverlay');
            
            searchModal.classList.remove('show');
            searchOverlay.classList.remove('show');
            document.body.style.overflow = '';
            
            // Очищаем поле ввода
            document.getElementById('searchInput').value = '';
        }
        
        // Обработчики для открытия sidebar
        document.getElementById('catalogToggleDesktop').addEventListener('click', openCatalogSidebar);
        document.getElementById('catalogToggleMobile').addEventListener('click', openCatalogSidebar);
        
        // Обработчики для закрытия sidebar
        document.getElementById('closeSidebar').addEventListener('click', closeCatalogSidebar);
        document.getElementById('sidebarOverlay').addEventListener('click', closeCatalogSidebar);
        
        // Открытие поиска при клике на иконку поиска
        document.getElementById('searchIcon').addEventListener('click', openSearchModal);
        
        // Закрытие поиска при клике на overlay
        document.getElementById('searchOverlay').addEventListener('click', closeSearchModal);
        
        // Обработка формы поиска при отправке
        document.getElementById('searchForm').addEventListener('submit', function(e) {
            e.preventDefault();
            performSearch();
        });
        
        // Обработка клика на кнопку поиска (лупу)
        document.getElementById('searchSubmit').addEventListener('click', function(e) {
            e.preventDefault();
            performSearch();
        });
        
        // Функция выполнения поиска
        function performSearch() {
            const query = document.getElementById('searchInput').value.trim();
            
            if (query) {
                // Закрываем поиск
                closeSearchModal();
                
                // Переходим на страницу поиска
                window.location.href = '/products/search?q=' + encodeURIComponent(query);
            }
        }
        
        // Быстрый поиск при нажатии Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                const query = this.value.trim();
                
                if (query) {
                    closeSearchModal();
                    window.location.href = '/products/search?q=' + encodeURIComponent(query);
                }
            }
        });
        
        // Закрытие sidebar и поиска при нажатии Escape
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeCatalogSidebar();
                closeSearchModal();
            }
        });
        
        // Закрытие dropdown при клике вне
        document.addEventListener('click', function(e) {
            const dropdown = document.querySelector('.profile-dropdown');
            const dropdownMenu = document.querySelector('.dropdown-menu-profile');
            const dropdownToggle = document.getElementById('profileDropdown');
            
            if (!dropdown.contains(e.target) && dropdownMenu.classList.contains('show')) {
                const bsDropdown = bootstrap.Dropdown.getInstance(dropdownToggle);
                if (bsDropdown) {
                    bsDropdown.hide();
                }
            }
        });


        // Закрытие sidebar при клике на ссылки в каталоге
        document.querySelectorAll('.catalog-sidebar a').forEach(link => {
            link.addEventListener('click', function() {
                closeCatalogSidebar();
            });
        });

    </script>

    <script>
    // ==================== //
    // ГЛОБАЛЬНЫЕ ФУНКЦИИ  //
    // ==================== //

    // ==================== //
// ГЛОБАЛЬНЫЕ ФУНКЦИИ  //
// ==================== //

// Функция обновления счетчика корзины в навбаре
function updateCartBadge(count) {
    // Ищем ВСЕ бейджи корзины (рядом с иконкой bag)
    document.querySelectorAll('.nav-icon').forEach(icon => {
        const link = icon.querySelector('a[href*="cart"]');
        if (link) {
            const badge = icon.querySelector('.icon-badge');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    // Создаем бейдж если его нет
                    const newBadge = document.createElement('span');
                    newBadge.className = 'icon-badge';
                    newBadge.textContent = count;
                    newBadge.style.display = 'flex';
                    icon.appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        }
    });
}

// Функция обновления счетчика избранного в навбаре
function updateWishlistBadge(count) {
    document.querySelectorAll('.nav-icon').forEach(icon => {
        const link = icon.querySelector('a[href*="wishlist"]');
        if (link) {
            let badge = icon.querySelector('.wishlist-badge, .icon-badge');
            if (count > 0) {
                if (badge) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    const newBadge = document.createElement('span');
                    newBadge.className = 'icon-badge wishlist-badge';
                    newBadge.textContent = count;
                    newBadge.style.display = 'flex';
                    icon.appendChild(newBadge);
                }
            } else {
                if (badge) {
                    badge.style.display = 'none';
                }
            }
        }
    });
}


// Добавление в корзину
function addToCart(productId, buttonElement = null) {
    const button = buttonElement || (event && event.target ? event.target.closest('button') : null);
    if (!button) return;
    
    // 🔥 Открываем модальное окно выбора размера
    showSizeSelectionModal(productId, button);
}

// 🔥 Функция показа модального окна выбора размера
function showSizeSelectionModal(productId, button) {
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    
    fetch(`/product/${productId}/sizes`)
        .then(response => response.json())
        .then(data => {
            button.disabled = false;
            button.innerHTML = originalHtml;
            
            if (data.success && data.sizes && data.sizes.length > 0) {
                const sizeOptions = data.sizes.map(size => {
                    const available = size.stock > 0;
                    return `
                        <button type="button" 
                            class="size-option-btn ${!available ? 'disabled' : ''}"
                            data-size-name="${size.name}"
                            data-stock="${size.stock}"
                            onclick="selectCartSize('${size.name}', this)">
                            <span class="size-value">${size.name}</span>
                        </button>
                    `;
                }).join('');
                
                const modalHtml = `
                    <div class="modal fade" id="sizeSelectionCartModal" tabindex="-1">
                        <div class="modal-dialog modal-dialog-centered modal-sm">
                            <div class="modal-content border-0 shadow">
                                <div class="modal-header border-0">
                                    <h5 class="modal-title">Выберите размер</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body text-center">
                                    <p class="text-muted small mb-3">${data.product_title || ''}</p>
                                    <div class="sizes-grid justify-content-center">
                                        ${sizeOptions}
                                    </div>
                                    <p id="sizeCartError" class="text-danger small mt-2 d-none">Выберите размер</p>
                                </div>
                                <div class="modal-footer border-0 justify-content-center">
                                    <button type="button" class="btn btn-outline-dark btn-sm" data-bs-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-dark btn-sm" id="confirmCartSizeBtn" disabled
                                        onclick="confirmCartSize(${productId}, this)">
                                        В корзину
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
                
                const oldModal = document.getElementById('sizeSelectionCartModal');
                if (oldModal) oldModal.remove();
                
                document.body.insertAdjacentHTML('beforeend', modalHtml);
                const modal = new bootstrap.Modal(document.getElementById('sizeSelectionCartModal'));
                modal.show();
                
                window._cartSelectedSize = null;
                
                document.getElementById('sizeSelectionCartModal').addEventListener('hidden.bs.modal', function() {
                    this.remove();
                    window._cartSelectedSize = null;
                });
            } else {
                // Если размеров нет — добавляем без выбора
                addToCartDirect(productId, button);
            }
        })
        .catch(error => {
            console.error('Ошибка:', error);
            button.disabled = false;
            button.innerHTML = originalHtml;
            addToCartDirect(productId, button);
        });
}

// Выбор размера в модальном окне корзины
function selectCartSize(sizeName, element) {
    document.querySelectorAll('#sizeSelectionCartModal .size-option-btn').forEach(b => b.classList.remove('selected'));
    element.classList.add('selected');
    window._cartSelectedSize = sizeName;
    document.getElementById('confirmCartSizeBtn').disabled = false;
    document.getElementById('sizeCartError').classList.add('d-none');
}

// Подтверждение и добавление в корзину
function confirmCartSize(productId, button) {
    const sizeName = window._cartSelectedSize;
    
    if (!sizeName) {
        document.getElementById('sizeCartError').classList.remove('d-none');
        return;
    }
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('sizeSelectionCartModal'));
    modal.hide();
    
    const cartButton = document.querySelector(`button[onclick*="addToCart(${productId}"]`);
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({
            product_id: productId,
            quantity: 1,
            size: sizeName
        })
    })
    .then(r => r.json())
    .then(data => {
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
    });
}

// Прямое добавление без размера
function addToCartDirect(productId, button) {
    const originalHtml = button.innerHTML;
    button.disabled = true;
    button.innerHTML = '<i class="bi bi-hourglass-split"></i>';
    
    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json'
        },
        body: JSON.stringify({ product_id: productId, quantity: 1 })
    })
    .then(r => r.json())
    .then(data => {
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
        button.disabled = false;
        button.innerHTML = originalHtml;
    });
}

// Добавление в избранное
function toggleWishlist(productId, buttonElement = null) {
    const button = buttonElement || (event && event.target ? event.target.closest('.wishlist-btn-card, button') : null);
    if (!button) return;
    
    const icon = button.querySelector('i');
    const originalClass = icon.className;
    icon.className = 'bi bi-hourglass-split';
    
    fetch('/wishlist/toggle', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ product_id: productId })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.in_wishlist) {
                icon.className = 'bi bi-heart-fill';
                button.classList.add('in-wishlist');
                button.title = 'Удалить из избранного';
            } else {
                icon.className = 'bi bi-heart';
                button.classList.remove('in-wishlist');
                button.title = 'Добавить в избранное';
            }
            if (data.count !== undefined) {
                updateWishlistBadge(data.count);
            }
            showToast(data.message);
        } else {
            showToast(data.message || 'Ошибка', 'error');
            if (data.auth_url) window.location.href = data.auth_url;
        }
    })
    .catch(error => {
        console.error('Ошибка избранного:', error);
        icon.className = originalClass;
        showToast('Ошибка соединения', 'error');
    });
}

// Уведомления
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.style.cssText = `
        position: fixed;
        bottom: 20px;
        right: 20px;
        padding: 15px 25px;
        background: ${type === 'success' ? '#111' : '#dc3545'};
        color: white;
        border-radius: 4px;
        z-index: 10000;
        font-family: 'Montserrat', sans-serif;
        font-size: 0.9rem;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        animation: toastIn 0.3s ease;
    `;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Стили для анимации
if (!document.getElementById('toast-animations')) {
    const style = document.createElement('style');
    style.id = 'toast-animations';
    style.textContent = `
        @keyframes toastIn { from { transform: translateY(100px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
        @keyframes toastOut { from { transform: translateY(0); opacity: 1; } to { transform: translateY(100px); opacity: 0; } }
    `;
    document.head.appendChild(style);
}
    </script>
    @yield('scripts')

    

    </script>
</body>
</html>