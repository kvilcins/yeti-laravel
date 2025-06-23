@extends('layouts.internal')

@section('title', 'Verify Email')

@section('content')
    <main>
        <div class="container">
            <div class="verification-page">
                <h1 class="h1">Verify Your Email Address</h1>

                <div class="verification-content">
                    <p>Before you can add items and place bids, please verify your email address.</p>
                    <p>We've sent a verification link to <strong>{{ auth()->user()->email }}</strong></p>
                    <p>If you didn't receive the email, click the button below to request another.</p>

                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="button">Resend Verification Email</button>
                    </form>

                    <div class="verification-actions">
                        <a href="{{ route('profile.') }}" class="link">Edit Profile</a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="link">Logout</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
@endsection
