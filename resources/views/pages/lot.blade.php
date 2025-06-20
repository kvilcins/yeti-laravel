@extends('layouts.page')

@section('title', $lot->title)

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <section class="lot-item">
                <h1>{{ $lot->title }}</h1>
                <div class="lot-item__content">
                    <div class="lot-item__left">
                        <div class="lot-item__image">
                            <img src="{{ asset($lot->img) }}" alt="{{ $lot->title }}">
                        </div>
                        <p class="lot-item__category">Category: <span>{{ $lot->category->name }}</span></p>
                        <p class="lot-item__description">{{ $lot->description }}</p>
                    </div>
                    <div class="lot-item__right">
                        <div class="lot-item__state">
                            <div class="lot-item__timer timer">
                                {{ lot_time_left($lot->timer) }}
                            </div>
                            <div class="lot-item__cost-state">
                                <div class="lot-item__rate">
                                    <span class="lot-item__amount">Current price</span>
                                    <span class="lot-item__cost">{{ formatPrice($lot->price) }}</span>
                                </div>
                                <div class="lot-item__min-cost">
                                    Min. bid <span>{{ formatPrice($lot->min_bid) }}</span>
                                </div>
                            </div>
                            @if($is_auth && $isLotActive)
                                <form class="lot-item__form" action="{{ route('bids.store', $lot->id) }}" method="post">
                                    @csrf
                                    <p class="lot-item__form-item">
                                        <label for="cost">Your bid</label>
                                        <input id="cost" type="number" name="cost" placeholder="{{ max($lot->min_bid, $lot->bids->max('bid_amount') ?? 0) + 1 }}" min="{{ max($lot->min_bid, $lot->bids->max('bid_amount') ?? 0) + 1 }}" required>
                                    </p>
                                    <button type="submit" class="button">Place bid</button>
                                </form>
                            @elseif(!$isLotActive)
                                <p class="lot-item__expired-message">Bidding has ended</p>
                            @endif
                        </div>

                        @include('components.history')
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
