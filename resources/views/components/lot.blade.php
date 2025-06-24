<li class="lots__item lot">
    <div class="lot__image">
        <img src="{{ $ad->image_url }}" width="350" height="260" alt="{{ $ad->title }}">

        <div class="lot__status lot__status--{{ $ad->status ?? 'active' }}">
            {{ ucfirst($ad->status ?? 'active') }}
        </div>

        <div class="lot__category-badge">{{ $ad->category->name }}</div>

        <div class="lot__overlay">
            <a href="{{ route('lot.show', ['category_slug' => $ad->category->slug, 'slug' => $ad->slug]) }}" class="lot__quick-view">
                Quick View
            </a>
        </div>
    </div>

    <div class="lot__info">
        <div class="lot__title">
            <a class="lot__title-link" href="{{ route('lot.show', ['category_slug' => $ad->category->slug, 'slug' => $ad->slug]) }}">
                {{ $ad->title }}
            </a>
        </div>

        <div class="lot__state">
            <div class="lot__pricing">
                <div class="lot__current-price">
                    <span class="lot__price-label">Current price</span>
                    <span class="lot__price-value">{{ formatPrice($ad->getCurrentPrice()) }}</span>
                </div>

                @if($ad->bids()->count() > 0)
                    <div class="lot__bid-info">
                        <span class="lot__bids-count">{{ $ad->bids()->count() }} bid{{ $ad->bids()->count() > 1 ? 's' : '' }}</span>
                    </div>
                @else
                    <div class="lot__starting-price">
                        <span class="lot__price-label">Starting at</span>
                        <span class="lot__price-value">{{ formatPrice($ad->price) }}</span>
                    </div>
                @endif
            </div>

            <div class="lot__timer-section">
                @php
                    $now = \Carbon\Carbon::now();
                    $created = \Carbon\Carbon::parse($ad->created_at ?? $now);
                    $ends = \Carbon\Carbon::parse($ad->timer);
                    $total = $created->diffInMinutes($ends);
                    $remaining = $now->diffInMinutes($ends);
                    $progress = $total > 0 ? max(0, min(100, (($total - $remaining) / $total) * 100)) : 0;
                @endphp

                @if($ad->timer && \Carbon\Carbon::parse($ad->timer)->isPast())
                    <div class="lot__timer lot__ended timer">
                        {{ lot_time_left($ad->timer) }}
                    </div>
                @else
                    <div class="lot__timer timer">
                        {{ lot_time_left($ad->timer) }}
                    </div>
                @endif

                <div class="lot__progress">
                    <div class="lot__progress-bar" style="width: {{ $progress }}%"></div>
                </div>
            </div>
        </div>

        <div class="lot__meta">
            <span class="lot__owner">by {{ $ad->user->name ?? 'Unknown' }}</span>
        </div>
    </div>
</li>
