@extends('layouts.internal')

@section('title', 'Edit Profile')

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

    <main>
        <div class="container">
            @if($is_auth)
                <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

                <form class="form form--profile" id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <h1 class="form__title h1">Edit Profile</h1>

                    <!-- Основные данные -->
                    <div class="form__section">
                        <h2 class="form__section-title">Basic Information</h2>

                        <div class="form__item" id="nameGroup">
                            <label class="form__label" for="name">Name*</label>
                            <input class="form__input" id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                            <span class="form__error" id="nameError"></span>
                        </div>

                        <div class="form__item" id="contactGroup">
                            <label class="form__label" for="message">Contact details</label>
                            <textarea class="form__input" id="message" name="message" placeholder="How to contact you">{{ old('message', auth()->user()->contact_details) }}</textarea>
                            <span class="form__error" id="messageError"></span>
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
                    </div>

                    <!-- Email изменение -->
                    <div class="form__section">
                        <h2 class="form__section-title">Email Address</h2>
                        <p class="form__section-description">To change your email, we'll send a confirmation link to your new address.</p>

                        <div class="form__item" id="emailGroup">
                            <label class="form__label" for="email">Email*</label>
                            <input class="form__input" id="email" type="email" name="email" value="{{ old('email', auth()->user()->email) }}">
                            <span class="form__error" id="emailError"></span>
                        </div>

                        <div class="form__item" id="currentPasswordForEmailGroup" style="display: none;">
                            <label class="form__label" for="current_password_for_email">Current Password</label>
                            <input class="form__input" id="current_password_for_email" type="password" name="current_password_for_email" placeholder="Enter current password to confirm email change">
                            <span class="form__error" id="current_password_for_emailError"></span>
                        </div>
                    </div>

                    <!-- Пароль изменение -->
                    <div class="form__section">
                        <h2 class="form__section-title">Change Password</h2>
                        <p class="form__section-description">Leave empty if you don't want to change your password.</p>

                        <div class="form__item" id="currentPasswordGroup" style="display: none;">
                            <label class="form__label" for="current_password">Current Password*</label>
                            <input class="form__input" id="current_password" type="password" name="current_password" placeholder="Enter current password">
                            <span class="form__error" id="current_passwordError"></span>
                        </div>

                        <div class="form__item" id="passwordGroup">
                            <label class="form__label" for="password">New Password</label>
                            <input class="form__input" id="password" type="password" name="password" placeholder="Enter new password (min 8 characters)">
                            <span class="form__error" id="passwordError"></span>
                        </div>

                        <div class="form__item" id="password_confirmationGroup" style="display: none;">
                            <label class="form__label" for="password_confirmation">Confirm New Password*</label>
                            <input class="form__input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat new password">
                            <span class="form__error" id="password_confirmationError"></span>
                        </div>
                    </div>

                    <button type="submit" class="form__submit button">Save Changes</button>
                </form>

                @include('components.bids')
            @endif
        </div>
    </main>

@endsection
