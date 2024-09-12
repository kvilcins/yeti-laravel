@extends('layouts.page')

@section('title', 'История просмотров')

@section('content')
    <section class="lots container">
        <div class="lots__header">
            <h2>История просмотров</h2>
        </div>
        
        @if ($viewedLotsData->isEmpty())
            <p>Просмотренные лоты отсутствуют.</p>
        @else
            <ul class="lots__list">
                @foreach ($viewedLotsData as $ad)
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="{{ asset($ad->img) }}" width="150" height="100" alt="{{ $ad->title }}">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">{{ $ad->category->name }}</span>
                            <h4 class="lot__title">
                                <a href="{{ route('lot.show', $ad->id) }}" class="text-link">{{ $ad->title }}</a>
                            </h4>
                            <div class="lot__state">
                                <div class="lot__rate">
                                    <span class="lot__amount">Стартовая цена</span>
                                    <span class="lot__cost">{{ formatPrice($ad->price) }}</span>
                                </div>
                                <div class="lot__timer timer">
                                    {{ time_to_midnight() }}
                                </div>
                            </div>
                        </div>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
