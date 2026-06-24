@extends('layouts.layout')

@section('title')
    @parent Избранное | ONEONE
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="text-white py-5" style="
        background: linear-gradient(135deg, #111111 0%, #1A1A1A 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    ">
        <div class="container py-4">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="mb-4" style="
                        width: 40px;
                        height: 2px;
                        background: #D282A9;
                        margin-bottom: 30px;
                    "></div>
                    <h1 class="fw-light mb-3" style="
                        letter-spacing: -1.5px;
                        font-size: 2.8rem;
                        font-weight: 300;
                    ">Избранное</h1>
                    <p class="lead mb-0" style="
                        color: rgba(255, 255, 255, 0.7);
                        font-weight: 300;
                        font-size: 1.1rem;
                    ">Ваша коллекция любимых товаров</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <span class="d-inline-block px-3 py-2" style="
                        background: rgba(255, 255, 255, 0.1);
                        color: white;
                        font-size: 0.8rem;
                        font-weight: 500;
                        letter-spacing: 1px;
                        border: 1px solid rgba(255, 255, 255, 0.2);
                    ">
                        {{ $wishlistItems->total() }} {{ trans_choice('товар|товара|товаров', $wishlistItems->total()) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="container py-5" style="padding-top: 4rem !important;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show mb-4" role="alert" style="border-radius: 0; border: none;">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if(session('info'))
            <div class="alert alert-info alert-dismissible fade show mb-4" role="alert" style="border-radius: 0; border: none;">
                {{ session('info') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        
        @if($wishlistItems->count() > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <p class="text-uppercase mb-0 small" style="
                            letter-spacing: 1.5px;
                            color: #888;
                        ">ИЗБРАННЫЕ ТОВАРЫ</p>
                        <form action="{{ route('wishlist.clear') }}" method="POST" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm" 
                                    style="
                                        background: transparent;
                                        color: #dc3545;
                                        border: 1px solid #dc3545;
                                        padding: 6px 16px;
                                        border-radius: 0;
                                        font-size: 0.85rem;
                                    "
                                    onclick="return confirm('Очистить всё избранное?')">
                                Очистить всё
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <div class="row g-4">
                @foreach($wishlistItems as $item)
                    @php
                        $product = $item->product;
                        $mainImage = $product->images->firstWhere('is_main', true) ?? $product->images->first();
                        $imageUrl = $mainImage ? asset($mainImage->image_path) : (asset($product->image) ?? asset('images/placeholder.jpg'));
                    @endphp
                    
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="product-card-home position-relative">
                            <!-- Иконка удаления из избранного -->
                            <div class="position-absolute top-0 end-0 p-3" style="z-index: 10;">
                                <form action="{{ route('wishlist.remove', $product->id) }}" method="POST" class="wishlist-remove-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn p-0" 
                                            style="background: none; border: none;"
                                            title="Удалить из избранного">
                                        <i class="bi bi-heart-fill" style="color: #dc3545; font-size: 1.2rem;"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <a href="{{ route('products.show', $product->id) }}" class="product-card-home__image-link">
                                <div class="product-card-home__image-wrapper">
                                    <img src="{{ $imageUrl }}" 
                                         alt="{{ $product->title }}" 
                                         class="product-card-home__image"
                                         onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                                </div>
                            </a>
                            
                            <div class="product-card-home__info">
                                <h3 class="product-card-home__title">
                                    <a href="{{ route('products.show', $product->id) }}" class="product-card-home__title-link">
                                        {{ $product->title }}
                                    </a>
                                </h3>
                                
                                <div class="product-card-home__price">
                                    @if($product->old_price && $product->old_price > $product->price)
                                        <span class="product-card-home__price-old">
                                            {{ number_format($product->old_price, 0, ',', ' ') }} ₽
                                        </span>
                                    @endif
                                    <span class="product-card-home__price-current">
                                        {{ number_format($product->price, 0, ',', ' ') }} ₽
                                    </span>
                                </div>
                                
                                <div class="mt-3 pt-3 border-top">
                                    <a href="{{ route('products.show', $product->id) }}" class="btn btn-sm w-100" 
                                       style="
                                            background: #111;
                                            color: white;
                                            border: none;
                                            border-radius: 0;
                                            padding: 8px 16px;
                                            font-size: 0.85rem;
                                            font-weight: 500;
                                       ">
                                        Подробнее
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <!-- Пагинация -->
            @if($wishlistItems->hasPages())
                <div class="row mt-5">
                    <div class="col-12">
                        <div class="d-flex justify-content-center">
                            {{ $wishlistItems->links() }}
                        </div>
                    </div>
                </div>
            @endif
            
        @else
            <div class="text-center py-5">
                <div class="mb-4">
                    <i class="bi bi-heart" style="font-size: 4rem; color: #ddd;"></i>
                </div>
                <h4 class="fw-light mb-3">Ваше избранное пусто</h4>
                <p class="text-muted mb-4">Добавляйте товары, нажимая на значок ♡ на карточках товаров</p>
                <a href="{{ route('catalog') }}" class="btn" 
                   style="
                        background: #111;
                        color: white;
                        border: none;
                        padding: 12px 32px;
                        border-radius: 0;
                        font-weight: 500;
                        letter-spacing: 1px;
                   ">
                    Перейти в каталог
                </a>
            </div>
        @endif
    </div>
@endsection

@section('scripts')
<script>
    // AJAX удаление из избранного
    document.addEventListener('DOMContentLoaded', function() {
        document.querySelectorAll('.wishlist-remove-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const form = this;
                const url = form.action;
                const method = 'DELETE';
                
                fetch(url, {
                    method: method,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Удаляем карточку товара
                        form.closest('.col-xl-3').remove();
                        
                        // Обновляем счетчик
                        updateWishlistCount(data.count);
                        
                        // Показываем уведомление
                        showToast(data.message, 'success');
                        
                        // Если товаров не осталось, показываем сообщение
                        if (data.count === 0) {
                            setTimeout(() => {
                                location.reload();
                            }, 1000);
                        }
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showToast('Ошибка при удалении из избранного', 'error');
                });
            });
        });
        
        function updateWishlistCount(count) {
            const badge = document.querySelector('.wishlist-badge');
            if (badge) {
                if (count > 0) {
                    badge.textContent = count;
                    badge.style.display = 'flex';
                } else {
                    badge.style.display = 'none';
                }
            }
        }
        
        function showToast(message, type = 'info') {
            // Создаем toast
            const toast = document.createElement('div');
            toast.className = `toast align-items-center text-white bg-${type === 'success' ? 'success' : 'danger'} border-0 position-fixed bottom-0 end-0 m-3`;
            toast.setAttribute('role', 'alert');
            toast.setAttribute('aria-live', 'assertive');
            toast.setAttribute('aria-atomic', 'true');
            
            toast.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body">
                        ${message}
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
                </div>
            `;
            
            document.body.appendChild(toast);
            
            // Показываем toast
            const bsToast = new bootstrap.Toast(toast);
            bsToast.show();
            
            // Удаляем toast после скрытия
            toast.addEventListener('hidden.bs.toast', function () {
                toast.remove();
            });
        }
    });
</script>
@endsection