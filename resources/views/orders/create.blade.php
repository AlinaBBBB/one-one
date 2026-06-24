@extends('layouts.layout')

@section('title', 'Оформление заказа | ONEONE')

@section('content')
<div class="container py-5">
    <!-- Хлебные крошки -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Главная</a></li>
            <li class="breadcrumb-item"><a href="{{ route('cart.index') }}">Корзина</a></li>
            <li class="breadcrumb-item active">Оформление заказа</li>
        </ol>
    </nav>

    <h1 class="mb-4">Оформление заказа</h1>

    <form action="{{ route('orders.store') }}" method="POST">
        @csrf

        <div class="row g-4">
            <!-- Левая колонка -->
            <div class="col-lg-8">
                <!-- Контактные данные -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Контактные данные</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Имя</label>
                            <input type="text" class="form-control" value="{{ $user->name }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" value="{{ $user->email }}" readonly>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Телефон <span class="text-danger">*</span></label>
                            <input type="tel" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                                   value="{{ old('phone', $user->phone) }}" required>
                            @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Адрес доставки -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Адрес доставки</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Адрес <span class="text-danger">*</span></label>
                            <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror" 
                                      rows="3" placeholder="Город, улица, дом, квартира" required>{{ old('delivery_address') }}</textarea>
                            @error('delivery_address') <div class="invalid-feedback">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>

                <!-- Способ оплаты -->
                <div class="card border-0 shadow-sm mb-4">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Способ оплаты</h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="card" id="card" checked>
                            <label class="form-check-label" for="card">Банковской картой онлайн</label>
                        </div>
                        <div class="form-check mb-2">
                            <input class="form-check-input" type="radio" name="payment_method" value="cash" id="cash">
                            <label class="form-check-label" for="cash">Наличными при получении</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="payment_method" value="online" id="online">
                            <label class="form-check-label" for="online">Онлайн-перевод</label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Правая колонка -->
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm sticky-top" style="top: 100px;">
                    <div class="card-header bg-white border-0">
                        <h5 class="mb-0">Ваш заказ</h5>
                    </div>
                    <div class="card-body">
                        @foreach($cartItems as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <small>{{ $item->product->title }} × {{ $item->quantity }}</small>
                            <small>{{ number_format($item->product->price * $item->quantity, 0, ',', ' ') }} ₽</small>
                        </div>
                        @endforeach

                        <hr>

                        <div class="d-flex justify-content-between mb-2">
                            <span>Товары</span>
                            <span>{{ number_format($subtotal, 0, ',', ' ') }} ₽</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span>Доставка</span>
                            <span>{{ $deliveryPrice == 0 ? 'Бесплатно' : number_format($deliveryPrice, 0, ',', ' ') . ' ₽' }}</span>
                        </div>

                        <hr>

                        <div class="d-flex justify-content-between fw-bold mb-3">
                            <span>Итого</span>
                            <span style="font-size: 1.2rem;">{{ number_format($total, 0, ',', ' ') }} ₽</span>
                        </div>

                        <button type="submit" class="btn btn-dark w-100 py-3">
                            Подтвердить заказ
                        </button>

                        <p class="text-muted small text-center mt-2">
                            Нажимая кнопку, вы соглашаетесь с условиями доставки
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection