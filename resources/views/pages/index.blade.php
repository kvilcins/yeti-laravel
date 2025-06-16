@extends('layouts.main')

@section('title', 'Главная страница')

@section('content')
    <main>
        <div class="container">
            <section class="promo">
                <h1 class="promo__title h1">Нужен стафф для катки?</h1>
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

            @include('components.lots', [
                'lots__title' => 'Открытые лоты',
                'lots__tag' => 'h2'
            ])
        </div>
    </main>
@endsection
