@extends('admin.layout')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="mb-0">Товары</h2>
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Добавить товар
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Фото</th>
                            <th>Название</th>
                            <th>Категория</th>
                            <th>Цена</th>
                            <th>Сток</th>
                            <th>Действия</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>
                                @php
                                    $imgUrl = null;
                                    if (!empty($product->image)) {
                                        $imgUrl = url($product->image);
                                    }
                                @endphp
                                @if($imgUrl)
                                    <img src="{{ $imgUrl }}" alt="{{ $product->title }}" 
                                         style="width: 60px; height: 60px; object-fit: cover; border-radius: 6px;">
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </td>
                            <td>{{ $product->title }}</td>
                            <td>{{ $product->category->name ?? '—' }}</td>
                            <td>{{ number_format($product->price, 0, ',', ' ') }} ₽</td>
                            <td>
                                <span class="badge bg-{{ $product->stock > 0 ? 'success' : 'danger' }}">
                                    {{ $product->stock }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-primary me-1">✏️</a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" 
                                      onsubmit="return confirm('Удалить?')">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger">🗑️</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            {{ $products->links() }}
        @else
            <p class="text-muted">Товаров пока нет</p>
        @endif
    </div>
</div>
@endsection