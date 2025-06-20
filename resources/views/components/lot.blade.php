<li class="lots__item lot">
    <div class="lot__image">
        <img src="{{ asset($ad->img) }}" width="350" height="260" alt="Snowboard">
    </div>
    <div class="lot__info">
        <span class="lot__category">Category: {{ $ad->category->name }}</span>
        <div class="lot__title">
            <a class="link" href="{{ route('lot.show', ['category_slug' => $ad->category->slug, 'slug' => $ad->slug]) }}">{{ $ad->title }}</a>
        </div>
        <div class="lot__state">
            <div class="lot__rate">
                <span class="lot__amount">Starting price</span>
                <span class="lot__cost">{{ formatPrice($ad->price) }}</span>
            </div>
            <div class="lot__timer timer">
                {{ lot_time_left($ad->timer) }}
            </div>
        </div>
    </div>
</li>
