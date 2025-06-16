@extends('layouts.page')

@section('title', 'Поиск по сайту')

@section('content')
    <main>
        <div class="container">
            <h1 class="h1">Результаты поиска для "{{ $searchTerm }}"</h1>

            @if($results->isEmpty())
                <p>Ничего не найдено по вашему запросу</p>
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
