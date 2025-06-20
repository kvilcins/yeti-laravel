@extends('layouts.internal')

@section('title', 'Page Not Found')

@section('content')
    <main>
        <div class="container">
            <div class="error-content">
                <h1 class="error-content__title">Page Not Found - 404</h1>
                <p class="error-content__description">
                    Sorry, the page you are looking for doesn't exist or has been moved.
                </p>
                <div class="error-content__actions">
                    <a href="{{ route('home') }}" class="error-content__link link">
                        Go to Homepage
                    </a>
                    <a href="javascript:history.back()" class="error-content__link link">
                        Go Back
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
