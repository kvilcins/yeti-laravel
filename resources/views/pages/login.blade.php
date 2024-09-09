@extends('layouts.page')

@section('title', 'Вход')

@section('content')
    <form class="form container {{ $errors->any() ? 'form--invalid' : '' }}" action="{{ route('login') }}" method="post">
        @csrf
        <h2>Вход</h2>
        <div class="form__item {{ $errors->has('email') ? 'form__item--invalid' : '' }}">
            <label for="email">E-mail*</label>
            <input id="email" type="text" name="email" placeholder="Введите e-mail" value="{{ old('email') }}" required>
            @if($errors->has('email'))
                <span class="form__error">{{ $errors->first('email') }}</span>
            @else
                <span class="form__error">Введите e-mail</span>
            @endif
        </div>
        <div class="form__item form__item--last {{ $errors->has('password') ? 'form__item--invalid' : '' }}">
            <label for="password">Пароль*</label>
            <input id="password" type="password" name="password" placeholder="Введите пароль" required>
            @if($errors->has('password'))
                <span class="form__error">{{ $errors->first('password') }}</span>
            @else
                <span class="form__error">Введите пароль</span>
            @endif
        </div>
        <button type="submit" class="button">Войти</button>
    </form>
@endsection
