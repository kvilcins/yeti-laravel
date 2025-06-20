@extends('layouts.main')

@section('title', 'Homepage')

@section('content')
    <main>
        <div class="container">
            <section class="promo">
                <h1 class="promo__title h1">Need snowboard gear?</h1>
                <p class="promo__text">
                    At our online auction you'll find the most exclusive snowboard and ski equipment.
                </p>
                <ul class="promo__list">
                    @foreach ($categories as $category)
                        <li class="promo__item promo__item--{{ $category->class }}">
                            <a class="promo__link" href="{{ route('category.show', ['slug' => $category->slug]) }}">
                                {{ $category->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </section>

            @include('components.lots', [
                'lots__title' => 'Open Lots',
                'lots__tag' => 'h2'
            ])
        </div>
    </main>
@endsection
