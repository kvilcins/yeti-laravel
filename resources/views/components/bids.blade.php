@if($userBids && $userBids->isNotEmpty())
    <div class="bids">
        <div class="bids__title h2">My Bids</div>
        <ul class="bids__list">
            @foreach($userBids as $bid)
                @if($bid->lot)
                    <li>
                        <a href="{{ route('lot.show', ['category_slug' => $bid->lot->category->slug, 'slug' => $bid->lot->slug]) }}" class="link">{{ $bid->lot->title }}</a> - Bid: {{ $bid->bid_amount }}
                    </li>
                @else
                    <li>This lot is no longer available.</li>
                @endif
            @endforeach
        </ul>
    </div>
@else
    <p>You have no bids.</p>
@endif
