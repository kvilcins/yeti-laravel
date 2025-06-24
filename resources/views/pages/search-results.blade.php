@extends('layouts.page')

@section('title', 'Site Search')

@section('content')
    <main class="search-page">
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <div class="search-results">
                <h1 class="h1">Search results</h1>

                <div class="search-results__meta">
                    <span class="search-results__count">
                        {{ $results->count() }} result{{ $results->count() !== 1 ? 's' : '' }} found
                    </span>
                    <span class="search-results__term">{{ $searchTerm }}</span>
                </div>

                @if($results->isEmpty())
                    <div class="search-results__empty"></div>

                    <div class="search-results__suggestions">
                        <h3 class="search-results__suggestions-title">Try searching for:</h3>
                        <div class="search-results__suggestions-list">
                            <a href="{{ route('search') }}?search=electronics" class="search-results__suggestion-item">Electronics</a>
                            <a href="{{ route('search') }}?search=furniture" class="search-results__suggestion-item">Furniture</a>
                            <a href="{{ route('search') }}?search=art" class="search-results__suggestion-item">Art</a>
                            <a href="{{ route('search') }}?search=books" class="search-results__suggestion-item">Books</a>
                            <a href="{{ route('search') }}?search=jewelry" class="search-results__suggestion-item">Jewelry</a>
                        </div>
                    </div>
                @else
                    <ul class="search-results__list">
                        @foreach($results as $lot)
                            <li class="search-results__item">
                                <a href="{{ route('lot.show', ['category_slug' => $lot->category->slug, 'slug' => $lot->slug]) }}" class="search-results__link">
                                    {{ $lot->title }}
                                </a>

                                <p class="search-results__description">{{ $lot->description }}</p>

                                <div class="search-results__meta-info">
                                    <span class="search-results__category">{{ $lot->category->name }}</span>
                                    <span class="search-results__price">{{ formatPrice($lot->getCurrentPrice()) }}</span>
                                    <span class="search-results__status search-results__status--{{ $lot->status ?? 'active' }}">
                                        {{ ucfirst($lot->status ?? 'active') }}
                                    </span>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @endif
            </div>
        </div>
    </main>
@endsection
