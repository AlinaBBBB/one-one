@extends('layouts.layout')

@section('title')
    @parent Вход | ONEONE
@endsection

@section('content')
    <!-- Hero Section -->
    <div class="text-white py-5" style="
        background: linear-gradient(135deg, #111111 0%, #1A1A1A 100%);
        border-bottom: 1px solid rgba(255, 255, 255, 0.05);
    ">
        <div class="container py-4">
            <div class="row justify-content-center text-center">
                <div class="col-lg-8">
                    <div class="mb-4" style="
                        width: 40px;
                        height: 2px;
                        background: #D282A9;
                        margin: 0 auto 30px;
                    "></div>
                    <h1 class="fw-light mb-3" style="
                        letter-spacing: -1.5px;
                        font-size: 2.8rem;
                        font-weight: 300;
                    ">Вход</h1>
                    <p class="lead mb-0" style="
                        color: rgba(255, 255, 255, 0.7);
                        font-weight: 300;
                        font-size: 1.1rem;
                        max-width: 500px;
                        margin: 0 auto;
                    ">Вернуться к минимализму и элегантности</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Form -->
    <div class="container py-5" style="padding-top: 4rem !important;">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-5">
                <div class="card border-0" style="
                    background: transparent;
                    border-radius: 0;
                ">
                    <div class="card-header bg-transparent text-center py-4 border-0">
                        <p class="text-muted mb-0 small text-uppercase" style="
                            letter-spacing: 2px;
                            font-weight: 500;
                            color: #888;
                        ">АВТОРИЗАЦИЯ</p>
                    </div>
                    <div class="card-body p-0 pt-4">
                        <form action="{{ route('login') }}" method="post">
                            @csrf
                            
                            <!-- Email -->
                            <div class="mb-4">
                                <label for="email" class="form-label small" style="
                                    color: #444;
                                    font-weight: 500;
                                    letter-spacing: 0.5px;
                                    margin-bottom: 8px;
                                    display: block;
                                ">E-mail</label>
                                <input class="form-control" 
                                       type="email" 
                                       name="email" 
                                       placeholder="your@email.com"
                                       value="{{ old('email') }}"
                                       required
                                       style="
                                            border: none;
                                            border-bottom: 1px solid #e0e0e0;
                                            border-radius: 0;
                                            padding: 0.75rem 0;
                                            background: transparent;
                                            font-size: 1rem;
                                            transition: all 0.3s ease;
                                       "
                                       onfocus="this.style.borderBottomColor='#111'; this.style.padding='0.75rem 0 0.5rem 0'"
                                       onblur="this.style.borderBottomColor='#e0e0e0'; this.style.padding='0.75rem 0'">
                            </div>

                            <!-- Password -->
                            <div class="mb-5">
                                <label for="password" class="form-label small" style="
                                    color: #444;
                                    font-weight: 500;
                                    letter-spacing: 0.5px;
                                    margin-bottom: 8px;
                                    display: block;
                                ">Пароль</label>
                                <input class="form-control" 
                                       type="password" 
                                       name="password" 
                                       placeholder="Ваш пароль"
                                       required
                                       style="
                                            border: none;
                                            border-bottom: 1px solid #e0e0e0;
                                            border-radius: 0;
                                            padding: 0.75rem 0;
                                            background: transparent;
                                            font-size: 1rem;
                                            transition: all 0.3s ease;
                                       "
                                       onfocus="this.style.borderBottomColor='#111'; this.style.padding='0.75rem 0 0.5rem 0'"
                                       onblur="this.style.borderBottomColor='#e0e0e0'; this.style.padding='0.75rem 0'">
                            </div>

                            <!-- Submit Button -->
                            <div class="d-grid gap-2 mb-5">
                                <button class="btn py-3" type="submit" 
                                        style="
                                            background: #111111;
                                            color: white;
                                            border: none;
                                            border-radius: 0;
                                            font-weight: 500;
                                            letter-spacing: 1.5px;
                                            font-size: 0.9rem;
                                            text-transform: uppercase;
                                            transition: all 0.3s ease;
                                            position: relative;
                                            overflow: hidden;
                                        "
                                        onmouseover="this.style.backgroundColor='#222222'"
                                        onmouseout="this.style.backgroundColor='#111111'">
                                    <span style="position: relative; z-index: 2;">Войти в аккаунт</span>
                                    <div style="
                                        position: absolute;
                                        top: 0;
                                        left: -100%;
                                        width: 100%;
                                        height: 100%;
                                        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.1), transparent);
                                        transition: left 0.5s ease;
                                    "></div>
                                </button>
                            </div>
                        </form>

                        <!-- Registration Link -->
                        <div class="text-center pt-4 border-top" style="border-top: 1px solid #f0f0f0 !important;">
                            <p class="mb-0 small" style="color: #888;">
                                Нет аккаунта? 
                                <a href="{{ route('register') }}" class="text-decoration-none fw-medium text-dark" 
                                   style="
                                        color: #111 !important;
                                        border-bottom: 1px solid transparent;
                                        padding-bottom: 1px;
                                        transition: all 0.3s ease;
                                   "
                                   onmouseover="this.style.borderBottomColor='#111'"
                                   onmouseout="this.style.borderBottomColor='transparent'">
                                    Зарегистрироваться
                                </a>
                            </p>
                        </div>

                        <!-- Error Messages -->
                        @if ($errors->any())
                            <div class="mt-5 pt-4" style="border-top: 1px solid rgba(196, 30, 58, 0.2);">
                                <div style="
                                    background: transparent;
                                    color: #C41E3A;
                                    padding: 0;
                                ">
                                    <p class="small text-uppercase mb-3" style="
                                        letter-spacing: 1.5px;
                                        font-weight: 600;
                                        color: #C41E3A;
                                    ">Ошибка авторизации:</p>
                                    <ul class="mb-0 small" style="
                                        padding-left: 1rem;
                                        list-style: none;
                                    ">
                                        @foreach ($errors->all() as $error)
                                            <li style="
                                                margin-bottom: 5px;
                                                position: relative;
                                                padding-left: 15px;
                                            ">
                                                <span style="
                                                    position: absolute;
                                                    left: 0;
                                                    top: 8px;
                                                    width: 4px;
                                                    height: 4px;
                                                    background: #C41E3A;
                                                    border-radius: 50%;
                                                "></span>
                                                {{ $error }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection