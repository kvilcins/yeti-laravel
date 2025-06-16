@extends('layouts.page')

@section('title', 'Категория: ' . $category_name)

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            @include('components.lots', [
                'lots__title' => 'Все лоты в категории <span>«' . ($category_name ?? 'Все категории') . '»</span>'
            ])
        </div>
    </main>
@endsection
