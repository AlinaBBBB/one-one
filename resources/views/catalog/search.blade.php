@extends('layouts.layout')

@section('title', 'Поиск: ' . ($query ?? ''))

@section('content')

<div class="container py-5">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}" class="text-decoration-none text-dark">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}" class="text-decoration-none text-dark">Каталог</a></li>
            <li class="breadcrumb-item active" aria-current="page">Поиск</li>
        </ol>
    </nav>

    <!-- Заголовок и информация о поиске -->
    <div class="row mb-5">
        <div class="col-12">
            <h1 class="fw-bold mb-3">РЕЗУЛЬТАТЫ ПОИСКА</h1>
            
            <!-- Форма поиска на странице результатов -->
            <form action="{{ route('products.search') }}" method="GET" class="mb-4">
                <div class="input-group" style="max-width: 500px;">
                    <input type="text" 
                           name="q" 
                           class="form-control form-control-lg" 
                           placeholder="Поиск товаров..."
                           value="{{ $query ?? '' }}"
                           style="border: 1px solid #ddd;">
                    <button class="btn btn-dark px-4" type="submit">
                        <i class="bi bi-search"></i> Найти
                    </button>
                </div>
            </form>
            
            <!-- Информация о результате -->
            @if(isset($query) && $query)
                <p class="text-muted">
                    По запросу <strong>"{{ $query }}"</strong> найдено товаров: 
                    <strong class="text-dark">{{ $products->total() }}</strong>
                </p>
            @endif
        </div>
    </div>

    <!-- Результаты поиска -->
    @if($products->count() > 0)
        <!-- Используем существующий partial для сетки товаров -->
        <div class="row row-cols-2 row-cols-md-3 row-cols-lg-4 g-4">
            @foreach($products as $product)
                <div class="col">
                    @include('catalog.partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        @if($products->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                <nav aria-label="Page navigation">
                    <ul class="pagination justify-content-center">
                        {{ $products->appends(['q' => $query])->links('pagination::bootstrap-4') }}
                    </ul>
                </nav>
            </div>
        </div>
        @endif

    @else
        <!-- Товары не найдены -->
        <div class="row">
            <div class="col-12 text-center py-5">
                <i class="bi bi-search" style="font-size: 4rem; color: #ccc;"></i>
                <h3 class="mt-3">Ничего не найдено</h3>
                
                @if(isset($query) && $query)
                    <p class="text-muted mb-4">
                        По вашему запросу <strong>"{{ $query }}"</strong> товаров не найдено
                    </p>
                @endif
                
                <div class="row justify-content-center mb-4">
                    <div class="col-md-6">
                        <div class="bg-light p-4 rounded">
                            <h5 class="mb-3">Рекомендации:</h5>
                            <ul class="list-unstyled text-start">
                                <li class="mb-2">
                                    <i class="bi bi-check2-circle text-success me-2"></i>
                                    Проверьте правильность написания
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check2-circle text-success me-2"></i>
                                    Попробуйте использовать более общие слова
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check2-circle text-success me-2"></i>
                                    Посмотрите товары в <a href="{{ route('catalog') }}" class="text-dark fw-bold">каталоге</a>
                                </li>
                                <li class="mb-2">
                                    <i class="bi bi-check2-circle text-success me-2"></i>
                                    Посмотрите <a href="{{ route('catalog.new') }}" class="text-dark fw-bold">новинки</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
                
                <a href="{{ route('catalog') }}" class="btn btn-outline-dark px-5 py-3">
                    ПЕРЕЙТИ В КАТАЛОГ
                </a>
            </div>
        </div>
    @endif
</div>
@endsection

@push('styles')
<style>
    /* Дополнительные стили для страницы поиска */
    .breadcrumb {
        background: transparent;
        padding: 0;
    }
    
    .breadcrumb-item a {
        color: #666;
        transition: color 0.3s ease;
    }
    
    .breadcrumb-item a:hover {
        color: #000;
    }
    
    .breadcrumb-item.active {
        color: #999;
    }
    
    .form-control:focus {
        box-shadow: none;
        border-color: #000;
    }
    
    .btn-dark {
        background: #000;
        border: none;
        transition: all 0.3s ease;
    }
    
    .btn-dark:hover {
        background: #333;
        transform: translateY(-1px);
    }
    
    /* Адаптивность */
    @media (max-width: 768px) {
        .input-group {
            max-width: 100% !important;
        }
        
        .form-control-lg {
            font-size: 1rem;
            padding: 0.75rem;
        }
        
        .btn-dark {
            padding: 0.75rem 1.5rem;
        }
    }
</style>
@endpush