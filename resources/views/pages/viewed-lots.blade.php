@extends('layouts.internal')

@section('title', 'История просмотров')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <section class="lots">
                <div class="lots__title h1">
                    <h1 class="h1">История просмотров</h1>
                </div>

                @if ($viewedLotsData->isEmpty())
                    <p>Просмотренные лоты отсутствуют.</p>
                @else
                    <ul class="lots__list">
                        @foreach ($viewedLotsData as $ad)
                            @include('components.lot')
                        @endforeach
                    </ul>
                @endif
            </section>
        </div>
    </main>
@endsection
