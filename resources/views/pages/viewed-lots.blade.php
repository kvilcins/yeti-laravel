@extends('layouts.internal')

@section('title', 'Viewing History')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <section class="lots">
                <div class="lots__title h1">
                    <h1 class="h1">Viewing History</h1>
                </div>

                @if ($viewedLotsData->isEmpty())
                    <p>No viewed lots.</p>
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
