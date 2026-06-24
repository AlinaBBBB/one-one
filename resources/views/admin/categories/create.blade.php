@extends('admin.layout')

@section('content')
<h2 class="mb-4">Добавить категорию</h2>

<div class="card">
    <div class="card-body">
        <form action="{{ route('admin.categories.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label class="form-label">Название *</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                       value="{{ old('name') }}" required>
                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
            </div>
            <button type="submit" class="btn btn-primary">
                <i class="bi bi-check-lg"></i> Сохранить
            </button>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Отмена</a>
        </form>
    </div>
</div>
@endsection