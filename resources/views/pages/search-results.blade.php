@extends('layouts.page')

@section('title', 'Site Search')

@section('content')
    <main>
        <div class="container">
            <h1 class="h1">Search results for "{{ $searchTerm }}"</h1>

            @if($results->isEmpty())
                <p>Nothing found for your search query</p>
            @else
                <ul>
                    @foreach($results as $lot)
                        <li>
                            <a href="{{ route('lot.show', ['category_slug' => $lot->category->slug, 'slug' => $lot->slug]) }}">{{ $lot->title }}</a>
                            <p>{{ $lot->description }}</p>
                        </li>
                    @endforeach
                </ul>
            @endif
        </div>
    </main>
@endsection
