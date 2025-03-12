@extends('layouts.page')

@section('title', 'Каталог лотов')

@section('content')
    <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />
    
    <main class="container">
        <section class="lots">
            <div class="lots__header">
                <h2>Открытые лоты</h2>
            </div>
            <ul class="lots__list">
                @foreach ($ads as $ad)
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="{{ asset($ad->img) }}" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">{{ $ad->category->name }}</span>
                            <h3 class="lot__title">
                                <a class="text-link" href="{{ route('lot.show', ['category_slug' => $ad->category->slug, 'slug' => $ad->slug]) }}">{{ $ad->title }}</a>
                            </h3>
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
            
            @if ($ads->hasPages())
                <x-partials.pagination :paginator="$ads" />
            @endif
        
        </section>
    </main>
@endsection

