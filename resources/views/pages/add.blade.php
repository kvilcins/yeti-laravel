@extends('layouts.internal')

@section('title', 'Добавление лота')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form form--add-lot {{ $errors->any() ? 'form--invalid' : '' }}"
                  action="{{ route('lot.store') }}"
                  method="POST"
                  enctype="multipart/form-data">
                @csrf
                <h1 class="form__title h1">Добавление лота</h1>

                <div class="form__item {{ $errors->has('lot-name') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="lot-name">Наименование</label>
                    <input class="form__input" id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="{{ old('lot-name') }}" required>
                    <span class="form__error">{{ $errors->first('lot-name') }}</span>
                </div>

                <div class="form__item {{ $errors->has('category') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="category">Категория</label>
                    <select class="form__input" id="category" name="category" required>
                        <option value="">Выберите категорию</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['name'] }}" {{ old('category') == $category['name'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form__error">{{ $errors->first('category') }}</span>
                </div>

                <div class="form__item {{ $errors->has('message') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="message">Описание</label>
                    <textarea class="form__input" id="message" name="message" placeholder="Напишите описание лота" required>{{ old('message') }}</textarea>
                    <span class="form__error">{{ $errors->first('message') }}</span>
                </div>

                <div class="form__item form__item--img {{ $errors->has('lot-img') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="lot-img">
                        Изображение
                        <span class="form__file-label">Загрузить</span>
                    </label>
                    <input class="form__input-file" type="file" name="lot-img" id="lot-img" required>
                    <div class="form__preview">
                        <img src="" alt="Предпросмотр изображения" class="form__preview-img">
                    </div>
                    <span class="form__error">{{ $errors->first('lot-img') }}</span>
                </div>

                <div class="form__item {{ $errors->has('lot-rate') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="lot-rate">Начальная цена</label>
                    <input class="form__input" id="lot-rate" type="number" name="lot-rate" placeholder="0" value="{{ old('lot-rate') }}" required>
                    <span class="form__error">{{ $errors->first('lot-rate') }}</span>
                </div>

                <div class="form__item {{ $errors->has('lot-step') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="lot-step">Шаг ставки</label>
                    <input class="form__input" id="lot-step" type="number" name="lot-step" placeholder="0" value="{{ old('lot-step') }}" required>
                    <span class="form__error">{{ $errors->first('lot-step') }}</span>
                </div>

                <div class="form__item {{ $errors->has('timer') ? 'form__item--invalid' : '' }}">
                    <label class="form__label" for="timer">Дата окончания торгов</label>
                    <input class="form__input form__input--date" id="timer" type="date" name="timer" value="{{ old('timer') }}" required>
                    <span class="form__error">{{ $errors->first('timer') }}</span>
                </div>

                @if ($errors->any())
                    <span class="form__error form__error--global">Пожалуйста, исправьте ошибки в форме.</span>
                @endif

                <button type="submit" class="form__submit button">Добавить лот</button>
            </form>
        </div>
    </main>
@endsection
