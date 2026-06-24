@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Заказ #{{ $order->id }}</h2>
    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
        <i class="bi bi-arrow-left"></i> К списку
    </a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Товары в заказе</h5>
            </div>
            <div class="card-body">
                @if($order->items && $order->items->count() > 0)
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Товар</th>
                                <th>Цена</th>
                                <th>Кол-во</th>
                                <th>Сумма</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product->title ?? 'Товар удалён' }}</td>
                                <td>{{ number_format($item->price, 0, ',', ' ') }} ₽</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->price * $item->quantity, 0, ',', ' ') }} ₽</td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end"><strong>Итого:</strong></td>
                                <td><strong>{{ number_format($order->total, 0, ',', ' ') }} ₽</strong></td>
                            </tr>
                        </tfoot>
                    </table>
                @else
                    <p class="text-muted mb-0">Нет данных о товарах</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Информация о заказе</h5>
            </div>
            <div class="card-body">
                <p><strong>Дата:</strong> {{ $order->created_at->format('d.m.Y H:i') }}</p>
                <p><strong>Покупатель:</strong> {{ $order->user->name ?? '—' }}</p>
                <p><strong>Email:</strong> {{ $order->user->email ?? '—' }}</p>
                <p><strong>Телефон:</strong> {{ $order->user->phone ?? '—' }}</p>
                <p><strong>Адрес:</strong> {{ $order->user->address ?? '—' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Статус заказа</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.orders.update', $order) }}" method="POST">
                    @csrf @method('PUT')
                    <select name="status" class="form-select mb-3">
                        <option value="new" {{ $order->status === 'new' ? 'selected' : '' }}>Новый</option>
                        <option value="processing" {{ $order->status === 'processing' ? 'selected' : '' }}>В обработке</option>
                        <option value="shipped" {{ $order->status === 'shipped' ? 'selected' : '' }}>Отправлен</option>
                        <option value="delivered" {{ $order->status === 'delivered' ? 'selected' : '' }}>Доставлен</option>
                        <option value="cancelled" {{ $order->status === 'cancelled' ? 'selected' : '' }}>Отменён</option>
                    </select>
                    <button type="submit" class="btn btn-primary w-100">Обновить статус</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection