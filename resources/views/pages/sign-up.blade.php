@extends('layouts.page')

@section('title', 'Регистрация')

@section('content')
    <form class="form container" action="{{ route('register') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>Регистрация нового аккаунта</h2>
        
        <div class="form__item">
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" value="{{ old('email') }}" placeholder="Введите e-mail" required>
            @error('email')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form__item">
            <label for="password">Пароль*</label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" required>
            @error('password')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form__item">
            <label for="name">Имя*</label>
            <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Введите имя" required>
            @error('name')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form__item">
            <label for="message">Контактные данные*</label>
            <textarea id="message" name="message" placeholder="Напишите как с вами связаться" required>{{ old('message') }}</textarea>
            @error('message')
            <span class="form__error">{{ $message }}</span>
            @enderror
        </div>
        
        <div class="form__item form__item--file form__item--last">
            <label>Аватар</label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" id="photo2" name="avatar">
                <label for="photo2">
                    <span>+ Добавить</span>
                </label>
            </div>
        </div>
        
        <button type="submit" class="button">Зарегистрироваться</button>
        <a class="text-link" href="{{ route('login') }}">Уже есть аккаунт</a>
    </form>
@endsection
