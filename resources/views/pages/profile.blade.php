@extends('layouts.internal')

@section('title', 'Страница аккаунта')

@section('content')
    <main>
        <div class="container">
            @if($is_auth)
                <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

                <form class="form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <h1 class="form__title h1">Редактирование профиля</h1>

                    <div class="form__item {{ $errors->has('name') ? 'form__item--invalid' : '' }}">
                        <label class="form__label" for="name">Имя*</label>
                        <input class="form__input" id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @error('name')
                            <span class="form__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form__item {{ $errors->has('password') ? 'form__item--invalid' : '' }}">
                        <label class="form__label" for="password">Новый пароль</label>
                        <input class="form__input" id="password" type="password" name="password" placeholder="Введите новый пароль">
                        @error('password')
                        <span class="form__error">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form__item">
                        <label class="form__label" for="password_confirmation">Подтверждение пароля</label>
                        <input class="form__input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Повторите новый пароль">
                    </div>

                    <div class="form__item form__item--img {{ $errors->has('avatar') ? 'form__item--invalid' : '' }}">
                        <label class="form__label" for="avatar">
                            Аватар
                            <span class="form__file-label">Загрузить</span>
                        </label>
                        <input class="form__input-file" type="file" name="avatar" id="avatar" accept="image/*" required>

                        <div class="form__preview {{ auth()->user()->avatar ? 'form__preview--visible' : '' }}">
                            <img
                                src="{{ auth()->user()->avatar ? asset('storage/' . auth()->user()->avatar) : '' }}"
                                alt="Предпросмотр изображения"
                                class="form__preview-img"
                                id="avatarPreview"
                                style="{{ auth()->user()->avatar ? '' : 'display: none;' }}"
                            >
                        </div>

                        <span class="form__error">{{ $errors->first('avatar') }}</span>
                    </div>

                    <button type="submit" class="form__submit button">Сохранить изменения</button>
                </form>

                @if($userBids && $userBids->isNotEmpty())
                    <div class="bids">
                        <div class="bids__title h2">Мои ставки</div>
                        <ul class="bids__list">
                            @foreach($userBids as $bid)
                                @if($bid->lot)
                                    <li>
                                        <a href="{{ route('lot.show', ['category_slug' => $bid->lot->category->slug, 'slug' => $bid->lot->slug]) }}">{{ $bid->lot->title }}</a> - Ставка: {{ $bid->bid_amount }}
                                    </li>
                                @else
                                    <li>Этот лот больше не доступен.</li>
                                @endif
                            @endforeach
                        </ul>
                    </div>
                @else
                    <p>У вас нет ставок.</p>
                @endif
            @endif
        </div>
    </main>
@endsection
