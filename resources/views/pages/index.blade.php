@extends('layouts.layout')

@section('title', 'Главная страница')

@section('content')
    <main class="container">
        <section class="promo">
            <h2 class="promo__title">Нужен стафф для катки?</h2>
            <p class="promo__text">На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.</p>
            <ul class="promo__list">
                @foreach ($categories as $category)
                    <li class="promo__item promo__item--{{ $category['class'] }}">
                        <a class="promo__link" href="all-lots.html">{{ $category['name'] }}</a>
                    </li>
                @endforeach
            </ul>
        </section>
        
        <section class="lots">
            <div class="lots__header">
                <h2>Открытые лоты</h2>
            </div>
            <ul class="lots__list">
                @foreach ($ads as $index => $ad)
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="{{ $ad['img'] }}" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">{{ $ad['category'] }}</span>
                            <h3 class="lot__title">
                                <a class="text-link" href="{{ url('pages/lot', ['id' => $index]) }}">{{ $ad['title'] }}</a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost">{{ formatPrice($ad['price']) }}</span>
                                </div>
                                <div class="lot__timer timer">
                                    {{ time_to_midnight() }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        </section>
    </main>
@endsection
