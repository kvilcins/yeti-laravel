@extends('layouts.internal')

@section('title', 'Registration')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form" action="{{ route('register') }}" method="POST" enctype="multipart/form-data" novalidate>
                @csrf
                <h1 class="form__title h1">Register new account</h1>

                <div class="form__item {{ $errors->has('email') ? 'form__item--invalid' : '' }}" id="emailGroup">
                    <label class="form__label" for="email">E-mail*</label>
                    <input class="form__input" id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter e-mail">
                    <span class="form__error" id="emailError">
                        @error('email'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('password') ? 'form__item--invalid' : '' }}" id="passwordGroup">
                    <label class="form__label" for="password">Password*</label>
                    <input class="form__input" id="password" type="password" name="password" placeholder="Enter password">
                    <span class="form__error" id="passwordError">
                        @error('password'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('name') ? 'form__item--invalid' : '' }}" id="nameGroup">
                    <label class="form__label" for="name">Name*</label>
                    <input class="form__input" id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter name">
                    <span class="form__error" id="nameError">
                        @error('name'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('message') ? 'form__item--invalid' : '' }}" id="messageGroup">
                    <label class="form__label" for="message">Contact details</label>
                    <textarea class="form__input" id="message" name="message" placeholder="Write how to contact you">{{ old('message') }}</textarea>
                    <span class="form__error" id="messageError">
                        @error('message'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item form__item--img {{ $errors->has('lot-img') ? 'form__item--invalid' : '' }}" id="avatarGroup">
                    <label class="form__label" for="lot-img">
                        Avatar
                        <span class="form__file-label">Upload</span>
                    </label>
                    <input class="form__input-file" type="file" name="lot-img" id="lot-img">
                    <div class="form__preview">
                        <img src="" alt="Image preview" class="form__preview-img">
                    </div>
                    <span class="form__error" id="avatarError">
                        @error('lot-img'){{ $message }}@enderror
                    </span>
                </div>

                <button type="submit" class="form__submit button">Register</button>
                <a class="link" href="{{ route('login') }}">Already have an account</a>
            </form>
        </div>
    </main>
@endsection
