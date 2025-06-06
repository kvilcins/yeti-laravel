@extends('layouts.internal')

@section('title', 'Страница аккаунта')

@section('content')
    <main class="container">

        @if($is_auth)
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <h2>Редактирование профиля</h2>

                <div class="form__item">
                    <label for="name">Имя*</label>
                    <input id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                    @error('name')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item">
                    <label for="password">Новый пароль</label>
                    <input id="password" type="password" name="password" placeholder="Введите новый пароль">
                    @error('password')
                    <span class="form__error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form__item">
                    <label for="password_confirmation">Подтверждение пароля</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" placeholder="Повторите новый пароль">
                </div>

                <div class="form__item form__item--file form__item--last">
                    <label>Аватар</label>
                    <div class="form__input-file">
                        <input class="visually-hidden" type="file" id="photo" name="avatar">
                        <label for="photo">
                            <span>+ Заменить</span>
                        </label>
                    </div>
                    @if(auth()->user()->avatar)
                        <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="Аватар" class="profile-avatar">
                    @endif
                </div>

                <button type="submit" class="button">Сохранить изменения</button>
            </form>

            @if($userBids && $userBids->isNotEmpty())
                <h2>Мои ставки</h2>
                <ul class="bids-list">
                    @foreach($userBids as $bid)
                        @if($bid->lot)
                        <li>
                            <a href="{{ route('bids.store', $bid->id) }}">{{ $bid->lot->title }}</a> - Ставка: {{ $bid->bid_amount }}
                        </li>
                        @else
                            <li>Этот лот больше не доступен.</li>
                        @endif
                    @endforeach
                </ul>
            @else
                <p>У вас нет ставок.</p>
            @endif
        @endif
    </main>
@endsection
