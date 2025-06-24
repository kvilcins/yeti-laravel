@extends('layouts.page')

@section('title', 'Lot Catalog')

@section('content')
    <main class="catalog">
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            @include('components.filters')

            @include('components.lots', [
                'lots__title' => 'Open Items'
            ])
        </div>
    </main>
@endsection
