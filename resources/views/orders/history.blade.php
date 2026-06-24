@extends('layouts.layout')

@section('title', 'Мои заказы | ONEONE')

@section('content')
<div class="container py-5">
    <h1 class="mb-4">Мои заказы</h1>

    @if($orders->count() > 0)
        <div class="card border-0 shadow-sm">
            <div class="table-responsive">
                <table class="table mb-0">
                    <thead>
                        <tr>
                            <th>№ заказа</th>
                            <th>Дата</th>
                            <th>Товаров</th>
                            <th>Сумма</th>
                            <th>Статус</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                        <tr>
                            <td><strong>#{{ $order->order_number ?? $order->id }}</strong></td>
                            <td>{{ $order->created_at->format('d.m.Y') }}</td>
                            <td>{{ $order->items_count }} шт.</td>
                            <td>{{ number_format($order->total_amount, 0, ',', ' ') }} ₽</td>
                            <td>
                                <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'dark') }}">
                                    {{ $order->status ?? 'Новый' }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('orders.show', $order->id) }}" class="btn btn-sm btn-outline-dark">Подробнее</a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        {{ $orders->links() }}
    @else
        <div class="text-center py-5">
            <i class="bi bi-box-seam display-1 text-muted"></i>
            <h4 class="mt-3">У вас пока нет заказов</h4>
            <a href="{{ route('catalog') }}" class="btn btn-dark mt-3">Перейти в каталог</a>
        </div>
    @endif
</div>
@endsection