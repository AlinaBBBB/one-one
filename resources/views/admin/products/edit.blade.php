@extends('admin.layout')

@section('content')
<h2 class="mb-4">Редактировать товар: {{ $product->title }}</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            
            <div class="row">
                <div class="col-md-8">
                    <div class="mb-3">
                        <label class="form-label">Название *</label>
                        <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" 
                               value="{{ old('title', $product->title) }}" required>
                        @error('title') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Описание</label>
                        <textarea name="description" class="form-control @error('description') is-invalid @enderror" 
                                  rows="5">{{ old('description', $product->description) }}</textarea>
                        @error('description') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="mb-3">
                        <label class="form-label">Цена *</label>
                        <input type="number" name="price" class="form-control @error('price') is-invalid @enderror" 
                               value="{{ old('price', $product->price) }}" step="0.01" min="0" required>
                        @error('price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Старая цена</label>
                        <input type="number" name="old_price" class="form-control @error('old_price') is-invalid @enderror" 
                               value="{{ old('old_price', $product->old_price) }}" step="0.01" min="0">
                        @error('old_price') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Количество на складе *</label>
                        <input type="number" name="stock" class="form-control @error('stock') is-invalid @enderror" 
                               value="{{ old('stock', $product->stock) }}" min="0" required>
                        @error('stock') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Категория</label>
                        <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                            <option value="">Без категории</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Изображение</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     style="width: 100px; height: 100px; object-fit: cover; border-radius: 6px;">
                            </div>
                        @endif
                        <input type="file" name="image" class="form-control @error('image') is-invalid @enderror" accept="image/*">
                        <small class="text-muted">Оставьте пустым, чтобы не менять</small>
                        @error('image') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_new" value="1" class="form-check-input" 
                               {{ old('is_new', $product->is_new) ? 'checked' : '' }}>
                        <label class="form-check-label">Новинка</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_popular" value="1" class="form-check-input" 
                               {{ old('is_popular', $product->is_popular) ? 'checked' : '' }}>
                        <label class="form-check-label">Популярное</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_bestseller" value="1" class="form-check-input" 
                               {{ old('is_bestseller', $product->is_bestseller) ? 'checked' : '' }}>
                        <label class="form-check-label">Бестселлер</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input type="checkbox" name="is_on_sale" value="1" class="form-check-input" 
                               {{ old('is_on_sale', $product->is_on_sale) ? 'checked' : '' }}>
                        <label class="form-check-label">Распродажа</label>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg"></i> Сохранить
                </button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Отмена</a>
            </div>
        </form>
    </div>
</div>
@endsection