@extends('layouts.page')

@section('title', 'Каталог лотов')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            @include('components.lots', [
                'lots__title' => 'Открытые лоты'
            ])
        </div>
    </main>
@endsection
