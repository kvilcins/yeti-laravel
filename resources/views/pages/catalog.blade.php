@extends('layouts.page')

@section('title', 'Lot Catalog')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            @include('components.lots', [
                'lots__title' => 'Open Items'
            ])
        </div>
    </main>
@endsection
