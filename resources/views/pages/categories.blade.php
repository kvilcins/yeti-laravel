@extends('layouts.page')

@section('title', 'Категория: ' . $category_name)

@section('content')
    <main class="container">
        <section class="lots">
            <h2>Все лоты в категории <span>«{{ $category_name ?? 'Все категории' }}»</span></h2>
    
            <ul class="lots__list">
                @forelse ($ads as $ad)
                    <li class="lots__item lot">
                        <div class="lot__image">
                            <img src="{{ asset($ad->img) }}" width="350" height="260" alt="Сноуборд">
                        </div>
                        <div class="lot__info">
                            <span class="lot__category">{{ $ad->category->name }}</span>
                            <h3 class="lot__title">
                                <a class="text-link" href="{{ url('lot', ['id' => $ad->id]) }}">{{ $ad->title }}</a>
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
                @empty
                    <li class="lots__item lot">
                        <p>Нет лотов в этой категории.</p>
                    </li>
                @endforelse
            </ul>
            
            @if ($ads->hasPages())
                <x-partials.pagination :paginator="$ads" />
            @endif

        </section>
    </main>
@endsection
