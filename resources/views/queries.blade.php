@extends('layouts.layout')

@section('title')
    @parent{{ $title }}
@endsection


@section('content')
    <div class="py-5 text-center container">
        <div class="row py-lg-5">
            <div class="col-lg-12 col-md-8 col-sm-6 mx-auto">
                <h1 class="fw-light">Ваша история заявок</h1>
                <p class="lead">Здесь вы можете посмотреть свою историю заявок
                </p>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col">Заголовок</th>
                            <th class="col">Дата создания заявки</th>
                            <th class="col">Код заявки</th>
                            <th class="col-2">Статус заявки</th>
                            <th class="col">Удаление заявки</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($queries as $query)
                            <tr>
                                <td>{{ $query->title }}</td>
                                <td>{{ $query->created_at->format('d.m.y, H:i') }}</td>
                                <td>{{ $query->query_id }}</td>
                                <td>{{ $query->status }}
                                    @if ($query->status == 'Отклонено')
                                        <p><strong>Комментарий:</strong> {{ $query->comment }}</p>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-primary link-underline-opacity-0 link-light"
                                        href="{{ route('queries.destroy', $query->query_id) }}">Удалить</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <p>
                    <a href="{{ route('newquery', ['user_id' => asset(auth()->user()->id)]) }}"
                        class="btn btn-primary my-2">Подать заявку</a>
                </p>
            </div>
        </div>
    </div>

    @auth
        @if (auth()->user()->role == 1)
            <div class="container">
                <h2>Все заявки(только для администратора)</h2>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th class="col">Заголовок</th>
                            <th class="col">Дата создания заявки</th>
                            <th class="col">Код заявки</th>
                            <th class="col">Обновление статуса заявки</th>
                            <th class="col">Добавление фото решения</th>
                            <th class="col">Удаление заявки</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($queries as $query)
                            <tr>
                                <td>{{ $query->title }}</td>
                                <td>{{ $query->created_at->format('d.m.y, H:i') }}</td>
                                <td>{{ $query->query_id }}</td>
                                <td>
                                    <form action="{{ route('home.update', $query->query_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <select name="status" id="status" class="form-select">
                                            <option value="В процессе" {{ $query->status == 'В процессе' ? 'selected' : '' }}>В
                                                процессе</option>
                                            <option value="Решено" {{ $query->status == 'Решено' ? 'selected' : '' }}>Решено
                                            </option>
                                            <option value="Отклонена" {{ $query->status == 'Отклонена' ? 'selected' : '' }}>
                                                Отклонена</option>
                                        </select>
                                        <button type="submit" class="btn btn-primary mt-3">Изменить статус</button>
                                    </form>
                                </td>
                                <td>
                                    <form action="{{ route('home.update', $query->query_id) }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <input class="form-control" id="photo_after" type="file" name="photo_after">
                                        <button type="submit" class="btn btn-primary mt-3">Добавить фото</button>
                                    </form>
                                </td>
                                <td>
                                    <a class="btn btn-primary link-underline-opacity-0 link-light"
                                        href="{{ route('queries.destroy', $query->query_id) }}">Удалить</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    @endauth
@endsection
