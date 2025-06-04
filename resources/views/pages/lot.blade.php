@extends('layouts.page')

@section('title', $lot->title)

@section('content')
    <main class="container">
        <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

        <section class="lot-item container">
        <h2>{{ $lot->title }}</h2>
        <div class="lot-item__content">
            <div class="lot-item__left">
                <div class="lot-item__image">
                    <img src="{{ asset($lot->img) }}" width="730" height="548" alt="{{ $lot->title }}">
                </div>
                <p class="lot-item__category">Категория: <span>{{ $lot->category->name }}</span></p>
                <p class="lot-item__description">{{ $lot->description }}</p>
            </div>
            <div class="lot-item__right">
                <div class="lot-item__state">
                    <div class="lot-item__timer timer">
                        {{ lot_time_left($lot->timer) }}
                    </div>
                    <div class="lot-item__cost-state">
                        <div class="lot-item__rate">
                            <span class="lot-item__amount">Текущая цена</span>
                            <span class="lot-item__cost">{{ formatPrice($lot->price) }}</span>
                        </div>
                        <div class="lot-item__min-cost">
                            Мин. ставка <span>{{ formatPrice($lot->min_bid) }}</span>
                        </div>
                    </div>
                    @if($is_auth)
                        <form class="lot-item__form" action="{{ route('bids.store', $lot->id) }}" method="post">
                            @csrf
                            <p class="lot-item__form-item">
                                <label for="cost">Ваша ставка</label>
                                <input id="cost" type="number" name="cost" placeholder="12 000" min="{{ max($lot->min_bid, $lot->bids->max('bid_amount') ?? 0) + 1 }}" required>
                            </p>
                            <button type="submit" class="button">Сделать ставку</button>
                        </form>
                    @endif
                </div>

                <div class="history">
                    <h3>История ставок (<span>{{ $bids->count() }}</span>)</h3>
                    <table class="history__list">
                        <tbody>
                        @foreach($bids as $bid)
                            <tr class="history__item">
                                <td class="history__name">{{ $bid->user->name }}</td>
                                <td class="history__price">{{ number_format($bid->bid_amount, 0, '', ' ') }} ₽</td>
                                <td class="history__time">{{ $bid->bid_time->diffForHumans() }}</td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    </main>
@endsection
