@extends('layouts.internal')

@section('title', 'Регистрация')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <h1 class="form__title h1">Регистрация нового аккаунта</h1>

                <div class="form__item {{ $errors->has('email') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="email">E-mail*</label>
                    <input class="form__input" id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Введите e-mail" required>
                    @error('email')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item {{ $errors->has('password') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="password">Пароль*</label>
                    <input class="form__input" id="password" type="password" name="password" placeholder="Введите пароль" required>
                    @error('password')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item {{ $errors->has('name') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="name">Имя*</label>
                    <input class="form__input" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Введите имя" required>
                    @error('name')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item {{ $errors->has('message') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="message">Контактные данные*</label>
                    <textarea class="form__input" id="message" name="message" placeholder="Напишите как с вами связаться" required>{{ old('message') }}</textarea>
                    @error('message')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item form__item--img {{ $errors->has('lot-img') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="lot-img">
                        Аватар
                        <span class="form__file-label">Загрузить</span>
                    </label>
                    <input class="form__input-file" type="file" name="lot-img" id="lot-img" required>
                    <div class="form__preview">
                        <img src="" alt="Предпросмотр изображения" class="form__preview-img">
                    </div>
                    <span class="form__error">{{ $errors->first('lot-img') }}</span>
                </div>

                <button type="submit" class="form__submit button">Зарегистрироваться</button>
                <a class="text-link" href="{{ route('login') }}">Уже есть аккаунт</a>
            </form>
        </div>
    </main>
@endsection
