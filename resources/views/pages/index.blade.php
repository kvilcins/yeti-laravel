@extends('layouts.main')

@section('title', 'Главная страница')

@section('content')
    <main class="container">
        <section class="promo">
            <h1 class="promo__title">Нужен стафф для катки?</h1>
            <p class="promo__text">
                На нашем интернет-аукционе ты найдёшь самое эксклюзивное сноубордическое и горнолыжное снаряжение.
            </p>
            <ul class="promo__list">
                @foreach ($categories as $category)
                    <li class="promo__item promo__item--{{ $category->class }}">
                        <a class="promo__link" href="{{ route('category.show', ['slug' => $category->slug]) }}">
                            {{ $category->name }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </section>

        <section class="lots">
            <h2 class="lots__title">Открытые лоты</h2>

            <ul class="lots__list">
                @foreach ($ads as $ad)
                    <li class="lot">
                        <div class="lot__image">
                            <img src="{{ asset($ad->img) }}" alt="">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">{{ $ad->category->name }}</span>
                            <h3 class="lot__title">
                                <a class="lot__link text-link" href="{{ route('lot.show', ['category_slug' => $ad->category->slug, 'slug' => $ad->slug]) }}">
                                    {{ $ad->title }}
                                </a>
                            </h3>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost">{{ formatPrice($ad->price) }}</span>
                                </div>
                                <div class="lot__timer timer">
                                    {{ lot_time_left($ad->timer) }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>

            @if ($ads->hasPages())
                <x-partials.pagination :paginator="$ads" />
            @endif
        </section>
    </main>
@endsection
