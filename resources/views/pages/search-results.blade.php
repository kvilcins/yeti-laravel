@extends('layouts.page')

@section('title', 'Site Search')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <div class="search-results">
                <h1 class="h1">Search results for "{{ $searchTerm }}"</h1>

                @if($results->isEmpty())
                    <p class="search-results__empty">Nothing found for your search query</p>
                @else
                    <ul class="search-results__list">
                        @foreach($results as $lot)
                            <li class="search-results__item">
                                <a href="{{ route('lot.show', ['category_slug' => $lot->category->slug, 'slug' => $lot->slug]) }}" class="search-results__link link">{{ $lot->title }}</a>
                                <p class="search-results__description">{{ $lot->description }}</p>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </main>
@endsection
