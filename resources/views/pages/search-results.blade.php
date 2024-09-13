@extends('layouts.page')

@section('title', 'Поиск по сайту')

@section('content')
    <section class="container">
        <h1>Результаты поиска для "{{ $searchTerm }}"</h1>
        
        @if($results->isEmpty())
            <p>Ничего не найдено по вашему запросу</p>
        @else
            <ul>
                @foreach($results as $lot)
                    <li>
                        <a href="{{ route('lot.show', $lot->id) }}">{{ $lot->title }}</a>
                        <p>{{ $lot->description }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </section>
@endsection
