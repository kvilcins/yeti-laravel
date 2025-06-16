@php
    $lots__tag = $lots__tag ?? 'h1';
    $lots__title = $lots__title ?? 'Открытые лоты';
@endphp

<section class="lots">
    <{{ $lots__tag }} class="lots__title">{!! $lots__title !!}</{{ $lots__tag }}>

    <ul class="lots__list">
        @forelse ($ads as $ad)
            @include('components.lot')
        @empty
            <li class="lots__item lot">
                <p>{!! $empty ?? 'Нет лотов в этой категории.' !!}</p>
            </li>
        @endforelse
    </ul>

    @if ($ads->hasPages())
        <x-partials.pagination :paginator="$ads" />
    @endif

</section>
