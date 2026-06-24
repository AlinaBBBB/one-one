@extends('layouts.layout')

@section('title')
    @parent{{ $title ?? 'Личный кабинет' }} | ONEONE
@endsection

@section('content')
    <!-- Герой секция личного кабинета -->
    <div class="py-5" style="
        background: linear-gradient(135deg, #111111 0%, #1A1A1A 100%);
        color: white;
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
                    ">{{ auth()->user()->name }}</h1>
                    <p class="lead mb-0" style="
                        color: rgba(255, 255, 255, 0.7);
                        font-weight: 300;
                        font-size: 1.1rem;
                    ">Управление аккаунтом и заказами</p>
                </div>
                <div class="col-md-4 text-md-end mt-4 mt-md-0">
                    <span class="d-inline-block px-3 py-2" style="
                        background: rgba(255, 255, 255, 0.1);
                        color: white;
                        font-size: 0.8rem;
                        font-weight: 500;
                        letter-spacing: 1px;
                        border: 1px solid rgba(255, 255, 255, 0.2);
                        backdrop-filter: blur(10px);
                    ">
                        @if(auth()->user()->role == 1)
                            АДМИНИСТРАТОР
                        @else
                            КЛИЕНТ
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Основной контент -->
    <div class="container py-5" style="padding-top: 4rem !important;">
        <!-- Информация о пользователе -->
        <div class="row mb-5">
            <div class="col-lg-8 mx-auto">
                <div class="mb-4">
                    <p class="text-uppercase mb-3" style="
                        letter-spacing: 2px;
                        font-size: 0.8rem;
                        font-weight: 600;
                        color: #888;
                    ">ПЕРСОНАЛЬНЫЕ ДАННЫЕ</p>
                </div>
                <div class="bg-white border-0" style="border: 1px solid #f0f0f0;">
                    <div class="p-4" style="border-bottom: 1px solid #f0f0f0;">
                        <div class="row">
                            <div class="col-md-4 mb-3 mb-md-0">
                                <p class="small text-uppercase mb-2" style="
                                    color: #666;
                                    font-weight: 500;
                                    letter-spacing: 1px;
                                ">Имя</p>
                                <p style="font-size: 1.1rem; font-weight: 400;">{{ auth()->user()->name }}</p>
                            </div>
                            <div class="col-md-4 mb-3 mb-md-0">
                                <p class="small text-uppercase mb-2" style="
                                    color: #666;
                                    font-weight: 500;
                                    letter-spacing: 1px;
                                ">Email</p>
                                <p style="font-size: 1.1rem; font-weight: 400;">{{ auth()->user()->email }}</p>
                            </div>
                            <div class="col-md-4">
                                <p class="small text-uppercase mb-2" style="
                                    color: #666;
                                    font-weight: 500;
                                    letter-spacing: 1px;
                                ">Роль</p>
                                <p style="font-size: 1.1rem; font-weight: 400;">
                                    @if(auth()->user()->role == 1)
                                        <span style="
                                            background: #111;
                                            color: white;
                                            padding: 4px 12px;
                                            font-size: 0.85rem;
                                            font-weight: 500;
                                            letter-spacing: 0.5px;
                                        ">Администратор</span>
                                    @else
                                        <span style="
                                            background: #f8f8f8;
                                            color: #111;
                                            padding: 4px 12px;
                                            font-size: 0.85rem;
                                            font-weight: 500;
                                            letter-spacing: 0.5px;
                                        ">Клиент</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Кнопки действий -->
                    <div class="p-4">
                        @if (auth()->user()->role == 1)
                            <a href="{{ route('admin.categories.create') }}" class="btn"
                               style="
                                    background: #111;
                                    color: white;
                                    border: none;
                                    padding: 12px 24px;
                                    border-radius: 0;
                                    font-weight: 500;
                                    letter-spacing: 1.5px;
                                    font-size: 0.85rem;
                                    text-transform: uppercase;
                                    transition: all 0.3s ease;
                               "
                               onmouseover="this.style.backgroundColor='#222'; this.style.transform='translateY(-2px)'"
                               onmouseout="this.style.backgroundColor='#111'; this.style.transform='translateY(0)'">
                                Создать категорию
                            </a>
                        @else
                            <p class="text-muted mb-0 small">Исследуйте минималистичные коллекции ONEONE</p>
                        @endif

                     @if (auth()->user()->role == 0)
                        <div class="mt-4">
                            <a href="{{ route('catalog') }}" class="btn"
                            style="
                                    background: #111;
                                    color: white;
                                    border: none;
                                    padding: 12px 24px;
                                    border-radius: 0;
                                    font-weight: 500;
                                    letter-spacing: 1.5px;
                                    font-size: 0.85rem;
                                    text-transform: uppercase;
                                    transition: all 0.3s ease;
                            "
                            onmouseover="this.style.backgroundColor='#222'; this.style.transform='translateY(-2px)'"
                            onmouseout="this.style.backgroundColor='#111'; this.style.transform='translateY(0)'">
                            Перейти в каталог
                            </a>
                        </div>
                    @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Таблица заказов -->
        <div class="row">
            <div class="col-12">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-uppercase mb-0" style="
                            letter-spacing: 2px;
                            font-size: 0.8rem;
                            font-weight: 600;
                            color: #888;
                        ">ИСТОРИЯ ЗАКАЗОВ</p>
                        <span class="px-3 py-1" style="
                            background: #f8f8f8;
                            color: #111;
                            font-size: 0.85rem;
                            font-weight: 500;
                            letter-spacing: 1px;
                        ">{{ $orders->count() }} {{ trans_choice('заказ|заказа|заказов', $orders->count()) }}</span>
                    </div>
                </div>
                
                @if(isset($orders) && $orders->count() > 0)
                    <div class="bg-white border-0" style="border: 1px solid #f0f0f0;">
                        <div class="table-responsive">
                            <table class="table mb-0">
                                <thead>
                                    <tr style="
                                        border-bottom: 2px solid #f0f0f0;
                                    ">
                                        <th style="
                                            font-weight: 500;
                                            color: #111;
                                            padding: 20px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                            font-size: 0.85rem;
                                            border: none;
                                        ">ЗАКАЗ</th>
                                        <th style="
                                            font-weight: 500;
                                            color: #111;
                                            padding: 20px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                            font-size: 0.85rem;
                                            border: none;
                                        ">ДАТА</th>
                                        <th style="
                                            font-weight: 500;
                                            color: #111;
                                            padding: 20px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                            font-size: 0.85rem;
                                            border: none;
                                        ">НОМЕР</th>
                                        <th style="
                                            font-weight: 500;
                                            color: #111;
                                            padding: 20px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                            font-size: 0.85rem;
                                            border: none;
                                        ">СТАТУС</th>
                                        <th style="
                                            font-weight: 500;
                                            color: #111;
                                            padding: 20px;
                                            text-transform: uppercase;
                                            letter-spacing: 1px;
                                            font-size: 0.85rem;
                                            border: none;
                                        "></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>
                                                <strong>#{{ $order->order_number ?? $order->id }}</strong>
                                            </td>
                                            <td>{{ $order->created_at->format('d.m.Y, H:i') }}</td>
                                            <td>
                                                <span>#{{ $order->id }}</span>
                                            </td>
                                            <td>
                                                <span style="
                                                    @if($order->status === 'delivered') 
                                                        background: rgba(40, 167, 69, 0.1); color: #28a745;
                                                    @elseif($order->status === 'cancelled') 
                                                        background: rgba(220, 53, 69, 0.1); color: #dc3545;
                                                    @elseif($order->status === 'processing' || $order->status === 'shipped') 
                                                        background: rgba(255, 193, 7, 0.1); color: #856404;
                                                    @else 
                                                        background: rgba(23, 162, 184, 0.1); color: #17a2b8;
                                                    @endif
                                                    padding: 6px 16px; font-size: 0.85rem; font-weight: 500;
                                                ">
                                                    {{ $order->status ?? 'Новый' }}
                                                </span>
                                            </td>
                                            <td>
                                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm"
                                                style="background: transparent; color: #111; border: 1px solid #111;">
                                                    Подробнее
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                @else
                    <div class="bg-white border-0 p-5 text-center" style="border: 1px solid #f0f0f0;">
                        <div class="mb-4">
                            <div style="
                                width: 60px;
                                height: 60px;
                                background: #f8f8f8;
                                border-radius: 50%;
                                display: flex;
                                align-items: center;
                                justify-content: center;
                                margin: 0 auto 20px;
                                color: #ccc;
                            ">
                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                    <polyline points="14 2 14 8 20 8"></polyline>
                                    <line x1="16" y1="13" x2="8" y2="13"></line>
                                    <line x1="16" y1="17" x2="8" y2="17"></line>
                                    <polyline points="10 9 9 9 8 9"></polyline>
                                </svg>
                            </div>
                        </div>
                        <p class="text-muted mb-4" style="font-size: 1.1rem;">У вас пока нет заказов</p>
                                       @if (auth()->user()->role == 0)
                            <div class="mt-4 text-center">
                                <a href="{{ route('catalog') }}" 
                                   class="btn"
                                   style="
                                        background: #111;
                                        color: white;
                                        border: none;
                                        padding: 12px 32px;
                                        border-radius: 0;
                                        font-weight: 500;
                                        letter-spacing: 1.5px;
                                        font-size: 0.9rem;
                                        text-transform: uppercase;
                                        transition: all 0.3s ease;
                                   "
                                   onmouseover="this.style.backgroundColor='#222'; this.style.transform='translateY(-2px)'"
                                   onmouseout="this.style.backgroundColor='#111'; this.style.transform='translateY(0)'">
                                   Перейти в каталог
                                </a>
                            </div>
                        @endif
                    </div>
                @endif
            </div>
        </div>

               <!-- Призыв к действию -->
        @if (auth()->user()->role == 0 && isset($orders) && $orders->count() > 0)
            <div class="row mt-5">
                <div class="col-12">
                    <div class="bg-white border-0 p-5" style="border: 1px solid #f0f0f0;">
                        <div class="row align-items-center">
                            <div class="col-md-8">
                                <h4 class="fw-light mb-2" style="
                                    font-weight: 300;
                                    letter-spacing: -0.5px;
                                    font-size: 1.5rem;
                                ">Готовы к новым покупкам?</h4>
                                <p class="text-muted mb-0" style="font-size: 0.95rem;">Исследуйте наши минималистичные коллекции</p>
                            </div>
                            <div class="col-md-4 text-md-end mt-3 mt-md-0">
                                <a href="{{ route('catalog') }}" 
                                   class="btn"
                                   style="
                                        background: #111;
                                        color: white;
                                        border: none;
                                        padding: 12px 32px;
                                        border-radius: 0;
                                        font-weight: 500;
                                        letter-spacing: 1.5px;
                                        font-size: 0.85rem;
                                        text-transform: uppercase;
                                        transition: all 0.3s ease;
                                   "
                                   onmouseover="this.style.backgroundColor='#222'; this.style.transform='translateY(-2px)'"
                                   onmouseout="this.style.backgroundColor='#111'; this.style.transform='translateY(0)'">
                                   В каталог
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
@endsection