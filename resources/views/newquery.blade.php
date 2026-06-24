@extends('layouts.layout')

@section('title')
    @parent - Создание заказа фигурки
@endsection

@section('content')
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header anime-gradient text-white py-3">
                        <h2 class="mb-0 text-center">Создание заказа фигурки</h2>
                    </div>
                    <div class="card-body p-4">
                        <form action="{{ route('newquery.create') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            
                            <!-- Основная информация -->
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="title" class="form-label fw-bold">Название фигурки *</label>
                                    <input type="text" class="form-control form-control-lg" id="title" name="title" 
                                           placeholder="Например: Фигурка Наруто" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label fw-bold">Категория *</label>
                                    <select name="category_id" id="category" class="form-select form-select-lg" required>
                                        <option value="">Выберите категорию</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->category_id }}">{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Описание -->
                            <div class="mb-3">
                                <label for="description" class="form-label fw-bold">Описание фигурки *</label>
                                <textarea class="form-control" id="description" name="description" rows="4" 
                                          placeholder="Опишите детали: персонаж, поза, размер, особые пожелания..." required></textarea>
                            </div>

                            <!-- Размер и материалы -->
                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="size" class="form-label fw-bold">Высота фигурки (см)</label>
                                    <input type="number" class="form-control" id="size" name="size" 
                                           placeholder="Например: 20" min="5" max="50">
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="material" class="form-label fw-bold">Материал</label>
                                    <select class="form-select" id="material" name="material">
                                        <option value="resin">Художественная смола</option>
                                        <option value="plastic">Пластик</option>
                                        <option value="clay">Полимерная глина</option>
                                        <option value="other">Другой</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="deadline" class="form-label fw-bold">Желаемый срок</label>
                                    <select class="form-select" id="deadline" name="deadline">
                                        <option value="14">2 недели</option>
                                        <option value="30">1 месяц</option>
                                        <option value="60">2 месяца</option>
                                        <option value="90">3 месяца</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Референсы -->
                            <div class="mb-3">
                                <label for="photo_before" class="form-label fw-bold">Референс-изображения *</label>
                                <input class="form-control" type="file" id="photo_before" name="photo_before" 
                                       accept="image/*" multiple required>
                                <div class="form-text">Загрузите изображения персонажа для reference (можно несколько)</div>
                            </div>

                            <!-- Дополнительные пожелания -->
                            <div class="mb-3">
                                <label for="additional_info" class="form-label fw-bold">Дополнительные пожелания</label>
                                <textarea class="form-control" id="additional_info" name="additional_info" rows="3"
                                          placeholder="Особые детали, цветовая гамма, база и т.д."></textarea>
                            </div>

                            <!-- Бюджет -->
                            <div class="mb-4">
                                <label for="budget" class="form-label fw-bold">Примерный бюджет (руб)</label>
                                <input type="number" class="form-control" id="budget" name="budget" 
                                       placeholder="Например: 5000" min="1000" step="500">
                                <div class="form-text">Укажите ориентировочную сумму, которую готовы потратить</div>
                            </div>

                            <!-- Контактная информация -->
                            <div class="card bg-light mb-4">
                                <div class="card-body">
                                    <h5 class="card-title">Контактная информация</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="contact_name" class="form-label">Имя для связи</label>
                                            <input type="text" class="form-control" id="contact_name" name="contact_name"
                                                   value="{{ auth()->user()->name ?? '' }}">
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="contact_phone" class="form-label">Телефон</label>
                                            <input type="tel" class="form-control" id="contact_phone" name="contact_phone"
                                                   placeholder="+7 (XXX) XXX-XX-XX">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Кнопки -->
                            <div class="d-flex gap-3 justify-content-center">
                                <button type="submit" class="btn btn-primary btn-lg px-5" 
                                        style="background: linear-gradient(135deg, #B209BD 0%, #C20378 100%); border: none;">
                                    Создать заказ
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-outline-secondary btn-lg px-5">
                                    Отмена
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
                
                <!-- Информация о процессе -->
                <div class="mt-4">
                    <div class="alert alert-info">
                        <h6 class="alert-heading">Как проходит процесс заказа:</h6>
                        <ol class="mb-0">
                            <li>Вы заполняете эту форму</li>
                            <li>Мы связываемся с вами для уточнения деталей</li>
                            <li>Создаем эскиз и согласовываем его</li>
                            <li>Изготавливаем фигурку</li>
                            <li>Доставляем готовую работу</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection