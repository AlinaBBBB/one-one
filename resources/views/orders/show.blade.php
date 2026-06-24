@extends('layouts.layout')

@section('title', 'Заказ #' . $order->order_number . ' | ONEONE')

@section('content')
<div class="container py-5">
    <!-- Хлебные крошки -->
    <nav class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('orders.history') }}">Мои заказы</a></li>
            <li class="breadcrumb-item active">Заказ #{{ $order->order_number }}</li>
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Заказ #{{ $order->order_number }}</h1>
        <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'dark') }} fs-6">
            {{ $order->status ?? 'Новый' }}
        </span>
    </div>

    <p class="text-muted">от {{ $order->created_at->format('d.m.Y H:i') }}</p>

    <div class="row g-4">
        <div class="col-lg-8">
            <!-- Товары -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Товары в заказе</h5>
                </div>
                <div class="card-body">
                    @foreach($order->items as $item)
                    <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                        <div>
                            <strong>{{ $item->product->title ?? 'Товар удалён' }}</strong>
                            @if($item->size) <small class="text-muted">| Размер: {{ $item->size }}</small> @endif
                            @if($item->color) <small class="text-muted">| Цвет: {{ $item->color }}</small> @endif
                            <br><small class="text-muted">{{ $item->quantity }} × {{ number_format($item->price, 0, ',', ' ') }} ₽</small>
                        </div>
                        <strong>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</strong>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- Детали -->
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white border-0">
                    <h5 class="mb-0">Детали заказа</h5>
                </div>
                <div class="card-body">
                    <p><strong>Статус:</strong> {{ $order->status }}</p>
                    <p><strong>Адрес доставки:</strong><br>{{ $order->delivery_address ?? '—' }}</p>
                    <p><strong>Способ оплаты:</strong><br>
                        @if($order->payment_method === 'card') Банковской картой
                        @elseif($order->payment_method === 'cash') Наличными
                        @else Онлайн-перевод
                        @endif
                    </p>
                    <p><strong>Телефон:</strong> {{ $order->phone ?? '—' }}</p>
                    <hr>
                    <h4>Итого: {{ number_format($order->total_amount, 0, ',', ' ') }} ₽</h4>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection