@extends('layouts.layout')

@section('title')
    @parent Корзина | ONEONE
@endsection

@section('content')
<div class="cart-page-oneone">
    <div class="container py-5 py-lg-6">
        <!-- Хлебные крошки -->
        <div class="breadcrumb-minimal mb-5">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="/">Главная</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Корзина</li>
                </ol>
            </nav>
        </div>

        <!-- Уведомления -->
        <div id="cartNotifications"></div>

        @if($cartItems->count() > 0)
        <div class="row">
            <!-- Список товаров -->
            <div class="col-lg-8">
                <div class="cart-header-minimal mb-5">
                    <h1 class="cart-title-minimal">Корзина</h1>
                    <div class="cart-count-minimal">
                        <span id="cartItemsCount">{{ $cartItems->count() }}</span> товаров
                    </div>
                </div>

                <div class="cart-items-minimal" id="cartItemsContainer">
                    @foreach($cartItems as $item)
                    <div class="cart-item-minimal" data-item-id="{{ $item->id }}">
                        <div class="row align-items-center">
                            <!-- Изображение товара -->
                            <div class="col-md-2">
                                <div class="cart-item-image-minimal">
                                    @if($item->product->images && $item->product->images->first())
                                        @php
                                            $image = $item->product->images->first();
                                            $imageUrl = asset($image->image_path);
                                        @endphp
                                        <img src="{{ $imageUrl }}" 
                                             alt="{{ $item->product->title }}"
                                             class="img-fluid"
                                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                    @elseif($item->product->image)
                                        <img src="{{ asset($item->product->image) }}" 
                                             alt="{{ $item->product->title }}"
                                             class="img-fluid"
                                             onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                    @else
                                        <div class="placeholder-image-minimal">
                                            <span class="placeholder-text">ONEONE</span>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <!-- Информация о товаре -->
                            <div class="col-md-4">
                                <div class="cart-item-info-minimal">
                                    <h3 class="cart-item-title-minimal">
                                        <a href="{{ route('products.show', $item->product->id) }}" class="cart-item-link-minimal">
                                            {{ $item->product->title }}
                                        </a>
                                    </h3>
                                    @if($item->product->color)
                                    <div class="cart-item-detail-minimal">
                                        Цвет: <strong>{{ $item->product->color }}</strong>
                                    </div>
                                    @endif
                                    @if($item->size)
                                    <div class="cart-item-detail-minimal">
                                        Размер: <strong>{{ $item->size }}</strong>
                                    </div>
                                    @endif
                                    <div class="cart-item-price-minimal">
                                        <span class="unit-price" data-price="{{ $item->product->price }}">
                                            {{ number_format($item->product->price, 0, ',', ' ') }} ₽ / шт.
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <!-- Счетчик количества -->
                            <div class="col-md-3">
                                <div class="quantity-control-minimal">
                                    <div class="quantity-input-minimal">
                                        <button type="button" 
                                                class="quantity-btn-minimal quantity-btn-decrease"
                                                {{ $item->quantity <= 1 ? 'disabled' : '' }}
                                                data-item-id="{{ $item->id }}">
                                            <span class="quantity-minus">−</span>
                                        </button>
                                        <span class="quantity-value-minimal" id="quantity-{{ $item->id }}">
                                            {{ $item->quantity }}
                                        </span>
                                        <button type="button" 
                                                class="quantity-btn-minimal quantity-btn-increase"
                                                data-item-id="{{ $item->id }}">
                                            <span class="quantity-plus">+</span>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <!-- Итоговая цена -->
                            <div class="col-md-2">
                                <div class="cart-item-total-minimal" id="total-{{ $item->id }}">
                                    {{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽
                                </div>
                            </div>

                            <!-- Удаление -->
                            <div class="col-md-1">
                                <div class="cart-item-actions-minimal">
                                    <button type="button" class="remove-btn-minimal" data-item-id="{{ $item->id }}">
                                        <span class="remove-icon">×</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Кнопки действий -->
                <div class="cart-actions-minimal mt-5 pt-4 border-top">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <a href="{{ route('catalog') }}" class="btn-minimal btn-minimal-outline w-100">
                                Продолжить покупки
                            </a>
                        </div>
                        <div class="col-md-6">
                            <button type="button" id="clearCartBtn" class="btn-minimal btn-minimal-secondary w-100">
                                Очистить корзину
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Итоги заказа -->
            <div class="col-lg-4">
                <div class="order-summary-minimal">
                    <div class="order-summary-header-minimal">
                        <h2 class="order-summary-title-minimal">Итоги заказа</h2>
                    </div>
                    
                    <div class="order-summary-body-minimal">
                        <div class="summary-row-minimal">
                            <span class="summary-label-minimal">Товары (<span id="totalItemsCount">{{ $totalQuantity }}</span> шт.)</span>
                            <span class="summary-value-minimal" id="subtotalPrice">
                                {{ number_format($subtotal, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                        
                        <div class="summary-row-minimal">
                            <span class="summary-label-minimal">Доставка</span>
                            <span class="summary-value-minimal" id="deliveryPrice">
                                {{ $deliveryPrice == 0 ? 'Бесплатно' : number_format($deliveryPrice, 0, ',', ' ') . ' ₽' }}
                            </span>
                        </div>
                        
                        <div class="summary-divider-minimal"></div>
                        
                        <div class="summary-row-minimal summary-total-minimal">
                            <span class="summary-label-total-minimal">Итого</span>
                            <span class="summary-value-total-minimal" id="totalPrice">
                                {{ number_format($total, 0, ',', ' ') }} ₽
                            </span>
                        </div>
                        
                        <a href="{{ route('orders.create') }}" class="btn-minimal btn-minimal-primary w-100">
                            Перейти к оформлению
                        </a>
                            
                            <div class="security-info-minimal mt-4">
                                <div class="security-item-minimal">
                                    <span class="security-icon">✓</span>
                                    <span>Безопасная оплата</span>
                                </div>
                                <div class="security-item-minimal">
                                    <span class="security-icon">✓</span>
                                    <span>Быстрая доставка</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @else
        <!-- Пустая корзина -->
        <div class="empty-cart-minimal">
            <div class="empty-cart-content-minimal text-center py-5">
                <div class="empty-cart-icon-minimal mb-4">
                    <span class="cart-icon">🛒</span>
                </div>
                <h2 class="empty-cart-title-minimal mb-3">Ваша корзина пуста</h2>
                <p class="empty-cart-text-minimal mb-4">Добавьте товары из каталога, чтобы сделать заказ</p>
                <a href="{{ route('catalog') }}" class="btn-minimal btn-minimal-primary">
                    Перейти в каталог
                </a>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Стили в стиле ONEONE -->
<style>
    /* ==================== */
    /* КОРЗИНА В СТИЛЕ ONEONE */
    /* ==================== */
    
    :root {
        --oneone-black: #111111;
        --oneone-white: #FFFFFF;
        --oneone-gray-light: #F8F8F8;
        --oneone-gray-border: #EAEAEA;
        --oneone-gray-medium: #999999;
        --oneone-accent-sale: #C41E3A;
        --oneone-accent-dark: #1A1A1A;
    }
    
    .cart-page-oneone {
        font-family: 'Montserrat', -apple-system, BlinkMacSystemFont, sans-serif;
        color: var(--oneone-black);
        background-color: var(--oneone-white);
    }
    
    /* Хлебные крошки */
    .breadcrumb-minimal {
        font-size: 0.875rem;
        color: var(--oneone-gray-medium);
    }
    
    .breadcrumb-minimal .breadcrumb-item a {
        color: var(--oneone-gray-medium);
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .breadcrumb-minimal .breadcrumb-item a:hover {
        color: var(--oneone-black);
    }
    
    .breadcrumb-minimal .breadcrumb-item.active {
        color: var(--oneone-black);
    }
    
    /* Уведомления */
    #cartNotifications {
        margin-bottom: 1.5rem;
    }
    
    .cart-notification {
        padding: 1rem 1.5rem;
        border-radius: 4px;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        justify-content: space-between;
        animation: slideIn 0.3s ease;
    }
    
    .cart-notification-success {
        background-color: rgba(40, 167, 69, 0.08);
        border: 1px solid rgba(40, 167, 69, 0.2);
        color: #155724;
    }
    
    .cart-notification-error {
        background-color: rgba(220, 53, 69, 0.08);
        border: 1px solid rgba(220, 53, 69, 0.2);
        color: #721c24;
    }
    
    .cart-notification-content {
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    .cart-notification-icon {
        font-weight: 700;
    }
    
    .cart-notification-close {
        background: none;
        border: none;
        color: inherit;
        cursor: pointer;
        font-size: 1.2rem;
        opacity: 0.7;
        transition: opacity 0.2s ease;
        padding: 0;
        line-height: 1;
    }
    
    .cart-notification-close:hover {
        opacity: 1;
    }
    
    @keyframes slideIn {
        from {
            transform: translateY(-10px);
            opacity: 0;
        }
        to {
            transform: translateY(0);
            opacity: 1;
        }
    }
    
    /* Заголовок корзины */
    .cart-header-minimal {
        margin-bottom: 2rem;
    }
    
    .cart-title-minimal {
        font-family: 'Montserrat', sans-serif;
        font-size: 2rem;
        font-weight: 700;
        color: var(--oneone-black);
        margin-bottom: 0.5rem;
        letter-spacing: -0.5px;
    }
    
    .cart-count-minimal {
        font-size: 1rem;
        color: var(--oneone-gray-medium);
    }
    
    /* Элементы корзины */
    .cart-items-minimal {
        margin-bottom: 2rem;
    }
    
    .cart-item-minimal {
        border: 1px solid var(--oneone-gray-border);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        transition: all 0.3s ease;
    }
    
    .cart-item-minimal:hover {
        border-color: var(--oneone-black);
        box-shadow: 0 4px 12px rgba(17, 17, 17, 0.08);
    }
    
    /* Изображение товара */
    .cart-item-image-minimal {
        width: 100%;
        height: 120px;
        border-radius: 8px;
        overflow: hidden;
        background-color: var(--oneone-gray-light);
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .cart-item-image-minimal img {
        width: 100%;
        height: 100%;
        object-fit: contain;
        padding: 10px;
    }
    
    .placeholder-image-minimal {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: var(--oneone-gray-light);
        color: var(--oneone-gray-medium);
        font-size: 0.9rem;
    }
    
    /* Информация о товаре */
    .cart-item-info-minimal {
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }
    
    .cart-item-title-minimal {
        font-size: 1.1rem;
        font-weight: 600;
        color: var(--oneone-black);
        margin-bottom: 0.5rem;
        line-height: 1.4;
    }
    
    .cart-item-link-minimal {
        color: inherit;
        text-decoration: none;
        transition: color 0.2s ease;
    }
    
    .cart-item-link-minimal:hover {
        color: var(--oneone-gray-medium);
    }
    
    .cart-item-detail-minimal {
        font-size: 0.875rem;
        color: var(--oneone-gray-medium);
        margin-bottom: 0.25rem;
    }
    
    .cart-item-price-minimal {
        font-size: 0.875rem;
        color: var(--oneone-gray-medium);
        margin-top: 0.5rem;
    }
    
    /* Управление количеством */
    .quantity-control-minimal {
        display: flex;
        justify-content: center;
    }
    
    .quantity-input-minimal {
        display: flex;
        align-items: center;
        border: 1px solid var(--oneone-gray-border);
        border-radius: 8px;
        overflow: hidden;
        width: 120px;
        margin: 0 auto;
    }
    
    .quantity-btn-minimal {
        background-color: var(--oneone-white);
        border: none;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
        color: var(--oneone-black);
    }
    
    .quantity-btn-minimal:hover:not(:disabled) {
        background-color: var(--oneone-gray-light);
    }
    
    .quantity-btn-minimal:disabled {
        opacity: 0.3;
        cursor: not-allowed;
    }
    
    .quantity-btn-minimal.loading {
        opacity: 0.5;
        cursor: wait;
    }
    
    .quantity-value-minimal {
        flex: 1;
        text-align: center;
        font-weight: 600;
        font-size: 1rem;
        color: var(--oneone-black);
        min-width: 30px;
    }
    
    .quantity-value-minimal.updating {
        color: var(--oneone-gray-medium);
        animation: pulse 1s infinite;
    }
    
    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.5; }
        100% { opacity: 1; }
    }
    
    /* Итоговая цена */
    .cart-item-total-minimal {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--oneone-black);
        text-align: center;
    }
    
    .cart-item-total-minimal.updating {
        animation: pulse 1s infinite;
    }
    
    /* Кнопка удаления */
    .remove-btn-minimal {
        background: none;
        border: 1px solid var(--oneone-gray-border);
        color: var(--oneone-gray-medium);
        width: 36px;
        height: 36px;
        border-radius: 8px;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s ease;
    }
    
    .remove-btn-minimal:hover {
        background-color: rgba(220, 53, 69, 0.08);
        border-color: #dc3545;
        color: #dc3545;
    }
    
    .remove-btn-minimal.loading {
        opacity: 0.5;
        cursor: wait;
    }
    
    .remove-icon {
        font-weight: 300;
    }
    
    /* Кнопки в стиле ONEONE */
    .btn-minimal {
        font-family: 'Montserrat', sans-serif;
        font-size: 1rem;
        font-weight: 600;
        padding: 1rem 2rem;
        border-radius: 8px;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
        border: 2px solid transparent;
        text-decoration: none;
        min-width: 200px;
    }
    
    .btn-minimal.loading {
        opacity: 0.7;
        cursor: wait;
    }
    
    .btn-minimal-primary {
        background-color: var(--oneone-black);
        color: var(--oneone-white);
        border-color: var(--oneone-black);
    }
    
    .btn-minimal-primary:hover {
        background-color: var(--oneone-accent-dark);
        border-color: var(--oneone-accent-dark);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(17, 17, 17, 0.15);
    }
    
    .btn-minimal-secondary {
        background-color: transparent;
        color: var(--oneone-gray-medium);
        border-color: var(--oneone-gray-border);
    }
    
    .btn-minimal-secondary:hover {
        background-color: var(--oneone-gray-light);
        border-color: var(--oneone-gray-medium);
        transform: translateY(-2px);
    }
    
    .btn-minimal-outline {
        background-color: transparent;
        color: var(--oneone-black);
        border-color: var(--oneone-black);
    }
    
    .btn-minimal-outline:hover {
        background-color: var(--oneone-black);
        color: var(--oneone-white);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(17, 17, 17, 0.15);
    }
    
    /* Блок итогов */
    .order-summary-minimal {
        background-color: var(--oneone-white);
        border: 1px solid var(--oneone-gray-border);
        border-radius: 12px;
        overflow: hidden;
        position: sticky;
        top: 20px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }
    
    .order-summary-header-minimal {
        background-color: var(--oneone-white);
        padding: 1.5rem;
        border-bottom: 1px solid var(--oneone-gray-border);
    }
    
    .order-summary-title-minimal {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.5rem;
        font-weight: 600;
        color: var(--oneone-black);
        margin: 0;
        letter-spacing: -0.5px;
    }
    
    .order-summary-body-minimal {
        padding: 1.5rem;
    }
    
    .summary-row-minimal {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 0;
        border-bottom: 1px solid rgba(17, 17, 17, 0.1);
    }
    
    .summary-row-minimal:last-child {
        border-bottom: none;
    }
    
    .summary-label-minimal {
        font-size: 0.95rem;
        color: var(--oneone-gray-medium);
    }
    
    .summary-value-minimal {
        font-size: 0.95rem;
        font-weight: 500;
        color: var(--oneone-black);
    }
    
    .summary-divider-minimal {
        height: 2px;
        background-color: var(--oneone-black);
        margin: 1rem 0;
    }
    
    .summary-total-minimal {
        margin-top: 0.5rem;
    }
    
    .summary-label-total-minimal {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--oneone-black);
    }
    
    .summary-value-total-minimal {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--oneone-black);
    }
    
    /* Информация о безопасности */
    .security-info-minimal {
        background-color: var(--oneone-gray-light);
        border: 1px solid var(--oneone-gray-border);
        border-radius: 8px;
        padding: 1rem;
        font-size: 0.875rem;
    }
    
    .security-item-minimal {
        display: flex;
        align-items: center;
        margin-bottom: 0.5rem;
        color: var(--oneone-gray-medium);
        gap: 8px;
    }
    
    .security-item-minimal:last-child {
        margin-bottom: 0;
    }
    
    .security-icon {
        font-size: 1rem;
        font-weight: 700;
    }
    
    /* Пустая корзина */
    .empty-cart-minimal {
        min-height: 50vh;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .empty-cart-content-minimal {
        max-width: 400px;
        margin: 0 auto;
    }
    
    .empty-cart-icon-minimal {
        font-size: 4rem;
    }
    
    .cart-icon {
        font-size: 4rem;
        opacity: 0.2;
    }
    
    .empty-cart-title-minimal {
        font-family: 'Montserrat', sans-serif;
        font-size: 1.75rem;
        font-weight: 600;
        color: var(--oneone-black);
        letter-spacing: -0.5px;
    }
    
    .empty-cart-text-minimal {
        font-size: 1rem;
        color: var(--oneone-gray-medium);
        line-height: 1.5;
    }
    
    /* Адаптивность */
    @media (max-width: 768px) {
        .cart-title-minimal {
            font-size: 1.5rem;
        }
        
        .cart-item-minimal {
            padding: 1rem;
        }
        
        .cart-item-image-minimal {
            height: 100px;
        }
        
        .cart-item-title-minimal {
            font-size: 1rem;
        }
        
        .cart-item-total-minimal {
            font-size: 1.1rem;
        }
        
        .quantity-input-minimal {
            width: 100px;
        }
        
        .quantity-btn-minimal {
            width: 32px;
            height: 32px;
        }
        
        .order-summary-title-minimal {
            font-size: 1.25rem;
        }
        
        .summary-label-total-minimal {
            font-size: 1.1rem;
        }
        
        .summary-value-total-minimal {
            font-size: 1.25rem;
        }
        
        .btn-minimal {
            padding: 0.875rem 1.5rem;
            min-width: auto;
        }
    }
    
    @media (max-width: 576px) {
        .cart-item-minimal .row > div {
            margin-bottom: 1rem;
        }
        
        .cart-item-minimal .row > div:last-child {
            margin-bottom: 0;
        }
        
        .cart-item-image-minimal {
            height: 80px;
            margin-bottom: 1rem;
        }
        
        .cart-actions-minimal .row {
            flex-direction: column;
            gap: 1rem;
        }
        
        .btn-minimal {
            width: 100%;
        }
    }

    /* Модальное окно подтверждения */
    #confirmDeleteModal .modal-content {
        border-radius: 16px;
        border: none;
    }

    #confirmDeleteModal .btn {
        border-radius: 8px;
        font-weight: 500;
        font-size: 0.9rem;
        padding: 10px 24px;
        transition: all 0.2s ease;
    }

    #confirmDeleteModal .btn-outline-dark:hover {
        background: #f5f5f5;
    }

    #confirmDeleteModal .btn-dark {
        background: #111;
        border-color: #111;
    }

    #confirmDeleteModal .btn-dark:hover {
        background: #333;
        border-color: #333;
    }

</style>

<!-- JavaScript для корзины -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;
        const cartNotifications = document.getElementById('cartNotifications');
        
        // Функция показа уведомления
        function showNotification(message, type = 'success') {
            const notification = document.createElement('div');
            notification.className = `cart-notification cart-notification-${type}`;
            notification.innerHTML = `
                <div class="cart-notification-content">
                    <span class="cart-notification-icon">${type === 'success' ? '✓' : '!'}</span>
                    <span>${message}</span>
                </div>
                <button class="cart-notification-close">&times;</button>
            `;
            
            cartNotifications.appendChild(notification);
            
            // Автоматически скрываем через 5 секунд
            setTimeout(() => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            }, 5000);
            
            // Закрытие по клику
            notification.querySelector('.cart-notification-close').addEventListener('click', () => {
                notification.style.opacity = '0';
                notification.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    if (notification.parentNode) {
                        notification.remove();
                    }
                }, 300);
            });
        }
        
        // Функция обновления итогов
        function updateCartTotals(data) {
            if (data.totalQuantity !== undefined) {
                document.getElementById('totalItemsCount').textContent = data.totalQuantity;
            }
            
            if (data.subtotal !== undefined) {
                document.getElementById('subtotalPrice').textContent = formatPrice(data.subtotal);
            }
            
            if (data.total !== undefined) {
                document.getElementById('totalPrice').textContent = formatPrice(data.total);
            }
            
            if (data.cartCount !== undefined) {
                document.getElementById('cartItemsCount').textContent = data.cartCount;
                // Обновляем счетчик в шапке (если есть)
                updateHeaderCartCount(data.cartCount);
            }
        }
        
        // Обновление счетчика в шапке
        function updateHeaderCartCount(count) {
            const headerCartCounts = document.querySelectorAll('.cart-count-badge, .cart-count, [data-cart-count]');
            headerCartCounts.forEach(element => {
                if (count > 0) {
                    element.textContent = count;
                    element.style.display = 'inline-block';
                } else {
                    element.style.display = 'none';
                }
            });
        }
        
        // Форматирование цены
        function formatPrice(price) {
            return new Intl.NumberFormat('ru-RU', {
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(price) + ' ₽';
        }
        
        // Обновление количества товара
        async function updateQuantity(itemId, change) {
            const quantityElement = document.getElementById(`quantity-${itemId}`);
            const totalElement = document.getElementById(`total-${itemId}`);
            const itemElement = document.querySelector(`.cart-item-minimal[data-item-id="${itemId}"]`);
            
            // Находим цену за единицу
            const unitPriceText = itemElement.querySelector('.unit-price').dataset.price || 
                                 itemElement.querySelector('.unit-price').textContent.match(/\d+/g).join('');
            const unitPrice = parseFloat(unitPriceText);
            
            // Текущее количество
            let currentQuantity = parseInt(quantityElement.textContent);
            let newQuantity = currentQuantity + change;
            
            // Проверка минимального количества
            if (newQuantity < 1) {
                showNotification('Количество не может быть меньше 1', 'error');
                return;
            }
            
            // Показываем загрузку
            quantityElement.classList.add('updating');
            totalElement.classList.add('updating');
            
            try {
                // Используем FormData вместо JSON
                const formData = new FormData();
                formData.append('_token', csrfToken);
                formData.append('item_id', itemId);
                formData.append('quantity', newQuantity);
                
                const response = await fetch('{{ route("cart.update") }}', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Обновляем отображение
                    quantityElement.textContent = newQuantity;
                    const newTotal = unitPrice * newQuantity;
                    totalElement.textContent = formatPrice(newTotal);
                    
                    // Обновляем итоги
                    if (data.subtotal !== undefined && data.total !== undefined && data.totalQuantity !== undefined) {
                        updateCartTotals(data);
                    }
                    
                    // Обновляем состояние кнопки уменьшения
                    const decreaseBtn = itemElement.querySelector('.quantity-btn-decrease');
                    if (decreaseBtn) {
                        decreaseBtn.disabled = newQuantity <= 1;
                    }
                    
                    // Показываем уведомление
                    const productName = itemElement.querySelector('.cart-item-title-minimal').textContent.trim();
                    showNotification(`Количество "${productName}" обновлено`, 'success');
                } else {
                    showNotification(data.message || 'Ошибка при обновлении количества', 'error');
                    // Возвращаем старое значение
                    quantityElement.textContent = currentQuantity;
                    const currentTotal = unitPrice * currentQuantity;
                    totalElement.textContent = formatPrice(currentTotal);
                }
            } catch (error) {
                console.error('Ошибка:', error);
                showNotification('Ошибка соединения с сервером. Попробуйте обновить страницу.', 'error');
                // Возвращаем старое значение
                quantityElement.textContent = currentQuantity;
                const currentTotal = unitPrice * currentQuantity;
                totalElement.textContent = formatPrice(currentTotal);
            } finally {
                // Убираем загрузку
                quantityElement.classList.remove('updating');
                totalElement.classList.remove('updating');
            }
        }
        
        // Удаление товара из корзины
        async function removeItem(itemId) {
            const itemElement = document.querySelector(`.cart-item-minimal[data-item-id="${itemId}"]`);
            const productName = itemElement.querySelector('.cart-item-title-minimal').textContent.trim();
            
            
            const modalHtml = `
                <div class="modal fade" id="confirmDeleteModal" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered modal-sm">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-body text-center py-4">
                                <div class="mb-3">
                                    <i class="bi bi-exclamation-triangle text-warning" style="font-size: 2.5rem;"></i>
                                </div>
                                <h5 class="mb-3">Удалить товар?</h5>
                                <p class="text-muted mb-4">"${productName}" будет удален из корзины</p>
                                <div class="d-flex gap-2 justify-content-center">
                                    <button type="button" class="btn btn-outline-dark px-4" data-bs-dismiss="modal">Отмена</button>
                                    <button type="button" class="btn btn-dark px-4" id="confirmDeleteBtn">Удалить</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `;
            
            // Удаляем старое модальное окно если есть
            const oldModal = document.getElementById('confirmDeleteModal');
            if (oldModal) oldModal.remove();
            
            // Добавляем новое
            document.body.insertAdjacentHTML('beforeend', modalHtml);
            
            const modal = new bootstrap.Modal(document.getElementById('confirmDeleteModal'));
            modal.show();
            
            // Обработчик подтверждения
            document.getElementById('confirmDeleteBtn').addEventListener('click', async function() {
                modal.hide();
                
                // Показываем загрузку
                const removeBtn = itemElement.querySelector('.remove-btn-minimal');
                removeBtn.classList.add('loading');
                
                try {
                    const response = await fetch(`/cart/remove/${itemId}`, {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        _method: 'DELETE'
                    })
                });
                    
                    const data = await response.json();
                    
                    if (data.success) {
                        // Плавное удаление
                        itemElement.style.opacity = '0';
                        itemElement.style.transform = 'translateX(-20px)';
                        itemElement.style.transition = 'all 0.3s ease';
                        
                        setTimeout(() => {
                            itemElement.remove();
                            
                            if (data.subtotal !== undefined) updateCartTotals(data);
                            
                            const cartItems = document.querySelectorAll('.cart-item-minimal');
                            if (cartItems.length === 0) {
                                setTimeout(() => window.location.reload(), 500);
                            }
                            
                            showNotification(`Товар удален из корзины`, 'success');
                        }, 300);
                    } else {
                        showNotification(data.message || 'Ошибка', 'error');
                    }
                } catch (error) {
                    console.error('Ошибка:', error);
                    showNotification('Ошибка соединения', 'error');
                } finally {
                    removeBtn.classList.remove('loading');
                }
            });
            
            // Удаляем модалку после закрытия
            document.getElementById('confirmDeleteModal').addEventListener('hidden.bs.modal', function() {
                this.remove();
            });
        }
        
        // Очистка корзины
        async function clearCart() {
            if (!confirm('Вы уверены, что хотите очистить корзину?')) {
                return;
            }
            
            const clearBtn = document.getElementById('clearCartBtn');
            clearBtn.classList.add('loading');
            clearBtn.disabled = true;
            
            try {
                const response = await fetch('{{ route("cart.clear") }}', {
                    method: 'DELETE',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                });
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const data = await response.json();
                
                if (data.success) {
                    // Плавное удаление всех элементов
                    const cartItems = document.querySelectorAll('.cart-item-minimal');
                    cartItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '0';
                            item.style.transform = 'translateX(-20px)';
                        }, index * 100);
                    });
                    
                    setTimeout(() => {
                        // Перезагружаем страницу для показа пустой корзины
                        window.location.reload();
                    }, cartItems.length * 100 + 300);
                    
                    showNotification('Корзина очищена', 'success');
                } else {
                    showNotification(data.message || 'Ошибка при очистке корзины', 'error');
                }
            } catch (error) {
                console.error('Ошибка:', error);
                showNotification('Ошибка соединения с сервером. Попробуйте обновить страницу.', 'error');
            } finally {
                clearBtn.classList.remove('loading');
                clearBtn.disabled = false;
            }
        }
        
        // Обработчики событий
        
        // Увеличение количества
        document.querySelectorAll('.quantity-btn-increase').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                updateQuantity(itemId, 1);
            });
        });
        
        // Уменьшение количества
        document.querySelectorAll('.quantity-btn-decrease').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                updateQuantity(itemId, -1);
            });
        });
        
        // Удаление товара
        document.querySelectorAll('.remove-btn-minimal').forEach(button => {
            button.addEventListener('click', function() {
                const itemId = this.dataset.itemId;
                removeItem(itemId);
            });
        });
        
        // Очистка корзины
        const clearCartBtn = document.getElementById('clearCartBtn');
        if (clearCartBtn) {
            clearCartBtn.addEventListener('click', clearCart);
        }
        
        // Проверяем, поддерживает ли сервер AJAX
        console.log('Cart script loaded. Routes:', {
            update: '{{ route("cart.update") }}',
            remove: '/cart/{id}',
            clear: '{{ route("cart.clear") }}'
        });
    });
</script>
@endsection