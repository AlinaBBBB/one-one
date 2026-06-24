<div class="product-card h-100">
    <div class="product-image-wrapper position-relative overflow-hidden">
        <!-- Кнопка избранного (всегда видна) -->
        @auth
            @php
                $isInWishlist = auth()->user()->wishlistItems()->where('product_id', $product->id)->exists();
            @endphp
            <button class="wishlist-btn-card position-absolute {{ $isInWishlist ? 'in-wishlist' : '' }}" 
                    onclick="toggleWishlist({{ $product->id }}, this)"
                    title="{{ $isInWishlist ? 'Удалить из избранного' : 'Добавить в избранное' }}"
                    style="z-index: 10;">
                <i class="bi {{ $isInWishlist ? 'bi-heart-fill' : 'bi-heart' }}"></i>
            </button>
        @endauth
        
        <a href="{{ route('products.show', $product->id) }}">
            <img src="{{ $product->main_image_url ?? asset('images/product-placeholder.jpg') }}" 
                 alt="{{ $product->title }}"
                 class="product-image w-100" 
                 loading="lazy">
        </a>
        
        @if($product->has_discount)
        <span class="product-badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
            -{{ $product->discount_percent }}%
        </span>
        @endif
        
        @if($product->has_discount)
        <span class="product-badge bg-dark text-white position-absolute top-0 start-0 m-2 px-2 py-1 small">
            -{{ $product->discount_percent }}%
        </span>
        @endif

        @if($product->is_new)
        <span class="product-badge bg-danger text-white position-absolute top-0 end-0 m-2 px-2 py-1 small" style="margin-top: 50px !important;">
            NEW
        </span>
        @endif
        
        <div class="product-actions position-absolute w-100 text-center pb-3">
            <button class="btn btn-dark btn-sm rounded-pill px-3" 
                    onclick="addToCart({{ $product->id }}, this)">
                <i class="bi bi-bag me-1"></i> В корзину
            </button>
        </div>
    </div>
    
    <div class="product-info p-3">
        <div class="product-category small text-muted mb-1">
            {{ $product->category->name ?? 'Без категории' }}
        </div>
        
        <h3 class="product-title h6 mb-2">
            <a href="{{ route('products.show', $product->id) }}" class="text-decoration-none text-dark">
                {{ $product->title }}
            </a>
        </h3>
        
        <div class="product-price mb-2">
            @if($product->has_discount)
            <span class="text-muted text-decoration-line-through me-2">
                {{ $product->old_price_formatted }}
            </span>
            @endif
            <span class="fw-bold text-dark">
                {{ $product->price_formatted }}
            </span>
        </div>
        
        <div class="product-meta small text-muted">
            <div class="d-flex justify-content-between">
                <span>{{ $product->material_translated }}</span>
                @if($product->color)
                <span>{{ $product->color }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
    .product-card {
        background: white;
        border-radius: 8px;
        overflow: hidden;
        box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        height: 100%;
        margin-bottom: 1.5rem;
    }
    
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 20px rgba(0,0,0,0.1);
    }
    
    .product-image-wrapper {
        height: 300px;
        background-color: #f8f9fa;
    }
    
    .product-image {
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }
    
    .product-card:hover .product-image {
        transform: scale(1.05);
    }
    
    /* КНОПКА ИЗБРАННОГО */
    .wishlist-btn-card {
        top: 8px;
        right: 8px;
        width: 36px;
        height: 36px;
        border-radius: 50%;
        background: rgba(255, 255, 255, 0.9);
        border: 1px solid rgba(0, 0, 0, 0.1);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        padding: 0;
        backdrop-filter: blur(4px);
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }
    
    .wishlist-btn-card:hover {
        background: #fff;
        transform: scale(1.1);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
    
    .wishlist-btn-card i {
        font-size: 1.1rem;
        color: #999;
        transition: all 0.3s ease;
    }
    
    .wishlist-btn-card:hover i {
        color: #dc3545;
    }
    
    /* Когда товар в избранном */
    .wishlist-btn-card.in-wishlist i {
        color: #dc3545 !important;
    }
    
    .wishlist-btn-card.in-wishlist {
        background: rgba(255, 255, 255, 0.95);
        border-color: rgba(220, 53, 69, 0.3);
    }
    
    .wishlist-btn-card.in-wishlist:hover i {
        color: #dc3545 !important;
    }
    
    .product-actions {
        bottom: -50px;
        transition: bottom 0.3s ease;
    }
    
    .product-card:hover .product-actions {
        bottom: 0;
    }
    
    .product-badge {
        font-size: 0.75rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        border-radius: 4px;
        z-index: 2;
    }
    
    .product-title {
        font-weight: 500;
        line-height: 1.4;
        min-height: 2.8em;
        overflow: hidden;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .product-price {
        font-size: 1.1rem;
    }
    
    @media (max-width: 768px) {
        .product-image-wrapper {
            height: 250px;
        }
        
        .wishlist-btn-card {
            top: 6px;
            right: 6px;
            width: 32px;
            height: 32px;
        }
        
        .wishlist-btn-card i {
            font-size: 1rem;
        }
    }
</style>