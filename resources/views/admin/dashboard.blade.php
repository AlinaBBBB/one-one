@extends('admin.layout')

@section('content')
<h2 class="mb-4">Дашборд</h2>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card card-stat products">
            <div class="card-body">
                <h5 class="card-title text-muted">Всего товаров</h5>
                <h2 class="mb-0">{{ $totalProducts }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat orders">
            <div class="card-body">
                <h5 class="card-title text-muted">Всего заказов</h5>
                <h2 class="mb-0">{{ $totalOrders }}</h2>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card card-stat users">
            <div class="card-body">
                <h5 class="card-title text-muted">Пользователей</h5>
                <h2 class="mb-0">{{ $totalUsers }}</h2>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Последние заказы</h5>
    </div>
    <div class="card-body">
        @if($recentOrders->count() > 0)
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Дата</th>
                        <th>Покупатель</th>
                        <th>Сумма</th>
                        <th>Статус</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentOrders as $order)
                    <tr>
                        <td>#{{ $order->id }}</td>
                        <td>{{ $order->created_at->format('d.m.Y H:i') }}</td>
                        <td>{{ $order->user->name ?? '—' }}</td>
                        <td>{{ number_format($order->total, 0, ',', ' ') }} ₽</td>
                        <td>
                            <span class="badge bg-{{ $order->status === 'delivered' ? 'success' : ($order->status === 'cancelled' ? 'danger' : 'warning') }}">
                                {{ $order->status ?? 'новый' }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted mb-0">Заказов пока нет</p>
        @endif
    </div>
</div>
@endsection