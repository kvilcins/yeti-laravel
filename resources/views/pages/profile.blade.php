@extends('layouts.internal')

@section('title', 'Profile')

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

                @if(!auth()->user()->hasVerifiedEmail())
                    <div class="verification-warning">
                        <div class="verification-warning__content">
                            <div class="h3">Email Verification Required</div>
                            <p>To add lots and place bids, please verify your email address. Check your inbox for a verification link.</p>

                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="button">Resend Verification Email</button>
                            </form>
                        </div>
                    </div>
                @endif

                <!-- Profile Edit Form -->
                <form class="form form--profile" id="profileForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    @method('PUT')
                    <h1 class="form__title h1">Edit Profile</h1>

                    <div class="form__section">
                        <h2 class="form__section-title">Basic Information</h2>

                        <div class="form__item" id="nameGroup">
                            <label class="form__label" for="name">Name*</label>
                            <input class="form__input" id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                            <span class="form__error" id="nameError"></span>
                        </div>

                        <div class="form__item">
                            <label class="form__label">Email</label>
                            <input class="form__input form__input--readonly" type="email" value="{{ auth()->user()->email }}" readonly>
                            @if(auth()->user()->hasVerifiedEmail())
                                <span class="form__help form__help--success">✓ Email verified</span>
                            @else
                                <span class="form__help form__help--warning">⚠ Email not verified</span>
                            @endif
                            @if(auth()->user()->isAdmin())
                                <span class="form__help form__help--admin">👑 Administrator</span>
                            @endif
                        </div>

                        <div class="form__item" id="messageGroup">
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
                                <img src="{{ $avatarUrl }}" alt="Image preview" class="form__preview-img" id="avatarPreview">

                                @if(auth()->user()->avatar)
                                    <button type="button" class="form__delete-avatar" id="deleteAvatarBtn" title="Delete avatar">×</button>
                                @endif
                            </div>

                            <span class="form__error" id="avatarError"></span>
                        </div>
                    </div>

                    <div class="form__section">
                        <h2 class="form__section-title">Change Password</h2>
                        <p class="form__section-description">Leave empty if you don't want to change your password.</p>

                        <div class="form__item" id="passwordGroup">
                            <label class="form__label" for="password">New Password</label>
                            <input class="form__input" id="password" type="password" name="password" placeholder="Enter new password (min 6 characters)">
                            <span class="form__error" id="passwordError"></span>
                        </div>
                    </div>

                    <button type="submit" class="form__submit button">Save Changes</button>
                </form>

                <!-- My Lots Section -->
                @if($userLots && $userLots->count() > 0)
                    <section class="profile-section">
                        <h2 class="h2">My Lots ({{ $userLots->count() }})</h2>
                        <div class="lots-grid">
                            @foreach($userLots as $lot)
                                <div class="lot-card">
                                    <div class="lot-card__image">
                                        <img src="{{ asset($lot->img) }}" alt="{{ $lot->title }}">
                                        <div class="lot-card__status lot-card__status--{{ $lot->status }}">
                                            {{ ucfirst($lot->status) }}
                                        </div>
                                    </div>
                                    <div class="lot-card__info">
                                        <h3 class="lot-card__title">{{ $lot->title }}</h3>
                                        <p class="lot-card__price">{{ formatPrice($lot->getCurrentPrice()) }}</p>
                                        <p class="lot-card__category">{{ $lot->category->name }}</p>

                                        <div class="lot-card__actions">
                                            <a href="{{ route('lot.show', [$lot->category->slug, $lot->slug]) }}" class="button button--small">View</a>
                                            <a href="{{ route('lot.edit', $lot->id) }}" class="button button--small button--secondary">Edit</a>

                                            <form method="POST" action="{{ route('lot.toggle-status', $lot->id) }}" style="display: inline;">
                                                @csrf
                                                @method('PATCH')
                                                <button type="submit" class="button button--small {{ $lot->status === 'active' ? 'button--warning' : 'button--success' }}">
                                                    {{ $lot->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                </button>
                                            </form>

                                            @if(auth()->user()->isAdmin())
                                                <form method="POST" action="{{ route('lot.destroy', $lot->id) }}" style="display: inline;" onsubmit="return confirm('Are you sure you want to delete this lot?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="button button--small button--danger">Delete</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- My Bids Section -->
                @if($userBids && $userBids->count() > 0)
                    <section class="profile-section">
                        <h2 class="h2">My Bids ({{ $userBids->count() }})</h2>
                        <div class="bids-list">
                            @foreach($userBids as $bid)
                                <div class="bid-item">
                                    <div class="bid-item__lot">
                                        <img src="{{ asset($bid->lot->img) }}" alt="{{ $bid->lot->title }}">
                                        <div class="bid-item__info">
                                            <h4>{{ $bid->lot->title }}</h4>
                                            <p class="bid-item__category">{{ $bid->lot->category->name }}</p>
                                        </div>
                                    </div>
                                    <div class="bid-item__details">
                                        <p class="bid-item__amount">My bid: {{ formatPrice($bid->bid_amount) }}</p>
                                        <p class="bid-item__time">{{ $bid->bid_time->format('d.m.Y H:i') }}</p>
                                        <a href="{{ route('lot.show', [$bid->lot->category->slug, $bid->lot->slug]) }}" class="button button--small">View Lot</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                <!-- Won Lots Section -->
                @if($wonLots && $wonLots->count() > 0)
                    <section class="profile-section">
                        <h2 class="h2">Won Lots ({{ $wonLots->count() }})</h2>
                        <div class="lots-grid">
                            @foreach($wonLots as $lot)
                                <div class="lot-card lot-card--won">
                                    <div class="lot-card__image">
                                        <img src="{{ asset($lot->img) }}" alt="{{ $lot->title }}">
                                        <div class="lot-card__status lot-card__status--won">Won</div>
                                    </div>
                                    <div class="lot-card__info">
                                        <h3 class="lot-card__title">{{ $lot->title }}</h3>
                                        <p class="lot-card__price">Winning bid: {{ formatPrice($lot->getCurrentPrice()) }}</p>
                                        <a href="{{ route('lot.show', [$lot->category->slug, $lot->slug]) }}" class="button button--small">View</a>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @include('components.bids')

            @endif
        </div>
    </main>
@endsection
