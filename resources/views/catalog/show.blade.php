@extends('layouts.layout')

@section('title')
    {{ $category->name }} | ONEONE
@endsection

@section('content')
<!-- Хлебные крошки -->
<nav class="breadcrumb-nav py-3">
    <div class="container">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('catalog') }}">Каталог</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </div>
</nav>

<div class="category-page py-5">
    <div class="container">
        <!-- Заголовок категории -->
        <div class="row mb-5">
            <div class="col-12">
                <h1 class="display-6 fw-bold mb-3">{{ $category->name }}</h1>
                @if($category->description)
                    <p class="lead">{{ $category->description }}</p>
                @endif
                <p class="text-muted">Найдено товаров: {{ $products->total() }}</p>
            </div>
        </div>

        <!-- Товары -->
        @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
                <div class="col-xl-3 col-md-4 col-sm-6 mb-4">
                    @include('partials.product-card', ['product' => $product])
                </div>
            @endforeach
        </div>

        <!-- Пагинация -->
        @if($products->hasPages())
        <div class="row mt-5">
            <div class="col-12">
                {{ $products->links() }}
            </div>
        </div>
        @endif
        @else
        <div class="text-center py-5">
            <i class="bi bi-search display-1 text-muted mb-3"></i>
            <h4>В этой категории пока нет товаров</h4>
            <p class="text-muted">Посмотрите другие категории</p>
            <a href="{{ route('catalog') }}" class="btn btn-dark mt-3">
                Вернуться в каталог
            </a>
        </div>
        @endif
    </div>
</div>

<style>
    .category-page {
        background-color: #f9f9f9;
    }
    
    .breadcrumb-nav {
        background-color: #f5f5f5;
        border-bottom: 1px solid #e0e0e0;
    }
</style>
@endsection