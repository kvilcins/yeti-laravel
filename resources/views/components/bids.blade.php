@if($userBids && $userBids->isNotEmpty())
    <div class="bids">
        <div class="bids__title h2">Мои ставки</div>
        <ul class="bids__list">
            @foreach($userBids as $bid)
                @if($bid->lot)
                    <li>
                        <a href="{{ route('lot.show', ['category_slug' => $bid->lot->category->slug, 'slug' => $bid->lot->slug]) }}" class="text-link">{{ $bid->lot->title }}</a> - Ставка: {{ $bid->bid_amount }}
                    </li>
                @else
                    <li>Этот лот больше не доступен.</li>
                @endif
            @endforeach
        </ul>
    </div>
@else
    <p>У вас нет ставок.</p>
@endif
