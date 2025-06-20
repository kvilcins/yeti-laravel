@extends('layouts.internal')

@section('title', 'Account Page')

@section('content')

    @php
        $avatar = auth()->user()->avatar;

        if ($avatar) {
            $isPublic = str_starts_with($avatar, 'img/');
            $avatarUrl = $isPublic ? asset($avatar) : asset('storage/' . $avatar);
        } else {
            $avatarUrl = asset('img/default-avatar.jpg');
        }
    @endphp

    @if(session('success'))
        <meta name="flash-success" content="{{ session('success') }}">
    @endif

    @if(session('error'))
        <meta name="flash-error" content="{{ session('error') }}">
    @endif

    <main>
        <div class="container">
            @if($is_auth)
                <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

                <form class="form form--profile" id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <h1 class="form__title h1">Edit Profile</h1>

                    <div class="form__item" id="nameGroup">
                        <label class="form__label" for="name">Name*</label>
                        <input class="form__input" id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                        <span class="form__error" id="nameError"></span>
                    </div>

                    <div class="form__item" id="passwordGroup">
                        <label class="form__label" for="password">New Password</label>
                        <input class="form__input" id="password" type="password" name="password" placeholder="Enter new password">
                        <span class="form__error" id="passwordError"></span>
                    </div>

                    <div class="form__item" id="password_confirmationGroup">
                        <label class="form__label" for="password_confirmation">Confirm Password</label>
                        <input class="form__input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat new password">
                        <span class="form__error" id="password_confirmationError"></span>
                    </div>

                    <div class="form__item form__item--img" id="avatarGroup">
                        <label class="form__label" for="avatar">
                            Avatar
                            <span class="form__file-label">Upload</span>
                        </label>

                        <input class="form__input-file" type="file" name="avatar" id="avatar" accept="image/*">

                        <div class="form__preview form__preview--visible">
                            <img
                                src="{{ $avatarUrl }}"
                                alt="Image preview"
                                class="form__preview-img"
                                id="avatarPreview"
                            >

                            @if(auth()->user()->avatar)
                                <button
                                    type="button"
                                    class="form__delete-avatar"
                                    id="deleteAvatarBtn"
                                    title="Delete avatar"
                                >
                                    ×
                                </button>
                            @endif
                        </div>

                        <span class="form__error" id="avatarError"></span>
                    </div>

                    <button type="submit" class="form__submit button">Save Changes</button>
                </form>

                @include('components.bids')
            @endif
        </div>
    </main>

    @include('modals.notification')

@endsection
