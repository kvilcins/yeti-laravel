@extends('layouts.page')

@section('title', 'Category: ' . $category_name)

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            @include('components.lots', [
                'lots__title' => 'All lots in category <span>"' . ($category_name ?? 'All categories') . '"</span>'
            ])
        </div>
    </main>
@endsection
