@extends('layouts.layout')

@section('title')
    @parent Создание категории
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="text-center">Создать категорию</h1>

        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="name">Название категории</label>
                <input type="text" name="name" id="name" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Создать</button>
        </form>
    </div>
@endsection
