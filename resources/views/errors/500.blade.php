@extends('layouts.internal')

@section('title', 'Server Error')

@section('content')
    <main>
        <div class="container">
            <div class="error-content">
                <h1 class="error-content__title">Server Error - 500</h1>
                <p class="error-content__description">
                    Something went wrong on our server. We're working to fix it.
                </p>
                <div class="error-content__actions">
                    <a href="{{ route('home') }}" class="error-content__link link">
                        Go to Homepage
                    </a>
                    <a href="javascript:location.reload()" class="error-content__link link">
                        Try Again
                    </a>
                </div>
            </div>
        </div>
    </main>
@endsection
