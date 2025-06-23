@extends('layouts.page')

@section('title', $lot->title)

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <section class="lot-item">
                <div class="lot-item__header">
                    <h1>{{ $lot->title }}</h1>

                    @if(isset($canManage) && $canManage)
                        <div class="lot-item__management">
                            <a href="{{ route('lot.edit', $lot->id) }}" class="button button--small button--secondary">Edit Lot</a>

                            <form method="POST" action="{{ route('lot.toggle-status', $lot->id) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="button button--small {{ $lot->status === 'active' ? 'button--warning' : 'button--success' }}">
                                    {{ $lot->status === 'active' ? 'Deactivate' : 'Activate' }}
                                </button>
                            </form>

                            @if(auth()->user()->isAdmin())
                                <form method="POST" action="{{ route('lot.destroy', $lot->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this lot?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="button button--small button--danger">Delete</button>
                                </form>
                            @endif
                        </div>
                    @endif
                </div>

                @if($lot->status !== 'active')
                    <div class="lot-item__status-warning">
                        <p>⚠ This lot is currently {{ $lot->status }}</p>
                    </div>
                @endif

                <div class="lot-item__content">
                    <div class="lot-item__left">
                        <div class="lot-item__image">
                            <img src="{{ $lot->image_url }}" alt="{{ $lot->title }}">
                        </div>
                        <p class="lot-item__category">Category: <span>{{ $lot->category->name }}</span></p>
                        <p class="lot-item__owner">Owner: <span>{{ $lot->user->name }}</span></p>
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
                                    <span class="lot-item__cost">{{ formatPrice($lot->getCurrentPrice()) }}</span>
                                </div>
                                <div class="lot-item__min-cost">
                                    Min. bid <span>{{ formatPrice($lot->min_bid) }}</span>
                                </div>
                            </div>
                            @if($is_auth && $isLotActive && auth()->user()->hasVerifiedEmail() && $lot->status === 'active' && $lot->user_id !== auth()->id())
                                <form class="lot-item__form" action="{{ route('bids.store', $lot->id) }}" method="post">
                                    @csrf
                                    <p class="lot-item__form-item">
                                        <label for="cost">Your bid</label>
                                        <input id="cost" type="number" name="cost" placeholder="{{ max($lot->min_bid, $lot->bids->max('bid_amount') ?? 0) + 1 }}" min="{{ max($lot->min_bid, $lot->bids->max('bid_amount') ?? 0) + 1 }}" required>
                                    </p>
                                    <button type="submit" class="button">Place bid</button>
                                </form>
                            @elseif($is_auth && $lot->user_id === auth()->id())
                                <p class="lot-item__expired-message">You cannot bid on your own item</p>
                            @elseif($lot->status !== 'active')
                                <p class="lot-item__expired-message">This lot is {{ $lot->status }}</p>
                            @elseif(!$isLotActive)
                                <p class="lot-item__expired-message">Bidding has ended</p>
                            @elseif($is_auth && !auth()->user()->hasVerifiedEmail())
                                <p class="lot-item__expired-message">
                                    <a href="{{ route('verification.notice') }}">Verify your email</a> to place bids
                                </p>
                            @endif
                        </div>

                        @include('components.history')
                    </div>
                </div>
            </section>
        </div>
    </main>
@endsection
