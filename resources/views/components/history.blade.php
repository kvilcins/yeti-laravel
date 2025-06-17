<div class="history">
    <div class="h3">История ставок (<span>{{ $bids->count() }}</span>)</div>

    <div class="history__wrapper">
        @foreach($bids as $bid)
            <div class="history__item">
                <div class="history__name">{{ $bid->user->name }}</div>
                <div class="history__price">{{ number_format($bid->bid_amount, 0, '', ' ') }} ₽</div>
                <div class="history__time">{{ $bid->bid_time->diffForHumans() }}</div>
            </div>
        @endforeach
    </div>
</div>
