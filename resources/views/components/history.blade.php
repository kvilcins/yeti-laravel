<div class="history">
    <div class="h3">Bid History (<span>{{ $bids->count() }}</span>)</div>

    <div class="history__wrapper">
        @foreach($bids as $bid)
            <div class="history__item">
                <div class="history__name">{{ $bid->user->name }}</div>
                <div class="history__price">${{ number_format($bid->bid_amount, 0, '', ' ') }}</div>
                <div class="history__time">{{ $bid->bid_time->diffForHumans() }}</div>

                @if($is_auth && (auth()->user()->isAdmin() || $bid->user_id === auth()->id()))
                    <form method="POST" action="{{ route('bids.destroy', $bid->id) }}" class="history__delete-form" onsubmit="return confirm('Are you sure you want to delete this bid?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="history__delete-btn" title="Delete bid">×</button>
                    </form>
                @endif
            </div>
        @endforeach
    </div>
</div>
