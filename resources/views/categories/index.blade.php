@extends('layouts.layout')

@section('title')
    @parent Категории
@endsection

@section('content')
    <div class="container py-5">
        <h1 class="text-center">Список категорий</h1>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Название категории</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr>
                        <td>{{ $category->category_id }}</td>
                        <td>{{ $category->name }}</td>
                    </tr>
                @endforeach

            </tbody>
        </table><div class="text-left mt-4">
                    <a href="{{ route('home') }}" class="btn btn-dark">Вернуться на главную</a>
                </div>
    </div>
@endsection
