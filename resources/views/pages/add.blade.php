@extends('layouts.layout')

@section('title', 'Добавление лота')

@section('content')
    <form class="form form--add-lot container {{ $errors->any() ? 'form--invalid' : '' }}" action="{{ route('lot.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <h2>Добавление лота</h2>
        <div class="form__container-two">
            <div class="form__item {{ $errors->has('lot-name') ? 'form__item--invalid' : '' }}">
                <label for="lot-name">Наименование</label>
                <input id="lot-name" type="text" name="lot-name" placeholder="Введите наименование лота" value="{{ old('lot-name') }}" required>
                <span class="form__error">{{ $errors->first('lot-name') }}</span>
            </div>
            <div class="form__item {{ $errors->has('category') ? 'form__item--invalid' : '' }}">
                <label for="category">Категория</label>
                <select id="category" name="category" required>
                    <option value="">Выберите категорию</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category['name'] }}" {{ old('category') == $category['name'] ? 'selected' : '' }}>
                            {{ $category['name'] }}
                        </option>
                    @endforeach
                </select>
                <span class="form__error">{{ $errors->first('category') }}</span>
            </div>
        </div>
        <div class="form__item form__item--wide {{ $errors->has('message') ? 'form__item--invalid' : '' }}">
            <label for="message">Описание</label>
            <textarea id="message" name="message" placeholder="Напишите описание лота" required>{{ old('message') }}</textarea>
            <span class="form__error">{{ $errors->first('message') }}</span>
        </div>
        <div class="form__item form__item--file {{ $errors->has('lot-img') ? 'form__item--invalid' : '' }}">
            <label>Изображение</label>
            <div class="form__input-file">
                <input class="visually-hidden" type="file" name="lot-img" id="lot-img" required>
                <label for="lot-img">
                    <span>+ Добавить</span>
                </label>
            </div>
            <span class="form__error">{{ $errors->first('lot-img') }}</span>
        </div>
        <div class="form__container-three">
            <div class="form__item form__item--small {{ $errors->has('lot-rate') ? 'form__item--invalid' : '' }}">
                <label for="lot-rate">Начальная цена</label>
                <input id="lot-rate" type="number" name="lot-rate" placeholder="0" value="{{ old('lot-rate') }}" required>
                <span class="form__error">{{ $errors->first('lot-rate') }}</span>
            </div>
            <div class="form__item form__item--small {{ $errors->has('lot-step') ? 'form__item--invalid' : '' }}">
                <label for="lot-step">Шаг ставки</label>
                <input id="lot-step" type="number" name="lot-step" placeholder="0" value="{{ old('lot-step') }}" required>
                <span class="form__error">{{ $errors->first('lot-step') }}</span>
            </div>
            <div class="form__item {{ $errors->has('lot-date') ? 'form__item--invalid' : '' }}">
                <label for="lot-date">Дата окончания торгов</label>
                <input class="form__input-date" id="lot-date" type="date" name="lot-date" value="{{ old('lot-date') }}" required>
                <span class="form__error">{{ $errors->first('lot-date') }}</span>
            </div>
        </div>
        <span class="form__error form__error--bottom">Пожалуйста, исправьте ошибки в форме.</span>
        <button type="submit" class="button">Добавить лот</button>
    </form>
@endsection
