@extends('layouts.internal')

@section('title', 'Sign-up')

@section('content')
    <main class="sing-up">
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

                <div class="profile__field profile__field--file {{ $errors->has('avatar') ? 'profile__field--invalid' : '' }}" id="avatarGroup">
                    <label class="profile__label" for="avatar">
                        Avatar
                        <span class="profile__file-label">Upload</span>
                    </label>

                    <input class="profile__file-input" type="file" name="avatar" id="avatar" accept="image/*">

                    <div class="profile__avatar profile__avatar--hidden" id="avatarContainer">
                        <img src="" alt="Avatar preview" class="profile__avatar-img" id="avatarPreview">
                        <button type="button" class="profile__avatar-delete profile__avatar-delete--hidden" id="deleteAvatarBtn" title="Remove avatar">×</button>
                    </div>

                    <span class="profile__error" id="avatarError">
                        @error('avatar'){{ $message }}@enderror
                    </span>
                </div>

                <button type="submit" class="form__submit button">Register</button>
                <a class="link" href="{{ route('login') }}">Already have an account</a>
            </form>
        </div>
    </main>
@endsection
