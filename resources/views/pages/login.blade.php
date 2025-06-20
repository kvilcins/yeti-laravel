@extends('layouts.internal')

@section('title', 'Login')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form {{ $errors->any() ? 'form--invalid' : '' }}" action="{{ route('login') }}" method="post" novalidate>
                @csrf
                <h1 class="form__title h1">Login</h1>

                <div class="form__item {{ $errors->has('email') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="email">E-mail*</label>
                    <input class="form__input"
                           id="email"
                           type="email"
                           name="email"
                           placeholder="Enter e-mail"
                           value="{{ old('email') }}">
                    <span class="form__error">
                        @if($errors->has('email'))
                            {{ $errors->first('email') }}
                        @else
                            Enter e-mail
                        @endif
                    </span>
                </div>

                <div class="form__item form__item--last {{ $errors->has('password') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="password">Password*</label>
                    <input class="form__input"
                           id="password"
                           type="password"
                           name="password"
                           placeholder="Enter password">
                    <span class="form__error">
                        @if($errors->has('password'))
                            {{ $errors->first('password') }}
                        @else
                            Enter password
                        @endif
                    </span>
                </div>

                <button type="submit" class="form__submit button">Login</button>
            </form>
        </div>
    </main>
@endsection
