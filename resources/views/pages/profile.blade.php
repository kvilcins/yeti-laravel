@extends('layouts.internal')

@section('title', 'Profile')

@section('content')

    <main class="account">
        <div class="container">
            @if($is_auth)
                <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

                @if(!auth()->user()->hasVerifiedEmail())
                    <div class="profile__verification-warning">
                        <div class="profile__verification-content">
                            <div class="profile__verification-title">Email Verification Required</div>
                            <p class="profile__verification-text">To add items and place bids, please verify your email address. Check your inbox for a verification link.</p>

                            <form method="POST" action="{{ route('verification.send') }}">
                                @csrf
                                <button type="submit" class="profile__verification-btn">Resend Verification Email</button>
                            </form>
                        </div>
                    </div>
                @endif

                <div class="profile">
                    <!-- Tab Navigation -->
                    <div class="profile__nav">
                        <button class="profile__nav-btn profile__nav-btn--active" data-tab="profile">Profile Settings</button>
                        <button class="profile__nav-btn" data-tab="my-lots">My Lots ({{ $userLots ? $userLots->count() : 0 }})</button>
                        <button class="profile__nav-btn" data-tab="my-bids">My Bids ({{ $userBids ? $userBids->count() : 0 }})</button>
                        @if($wonLots && $wonLots->count() > 0)
                            <button class="profile__nav-btn" data-tab="won-lots">Won Lots ({{ $wonLots->count() }})</button>
                        @endif
                    </div>

                    <!-- Tab Content -->
                    <div class="profile__content">

                        <!-- Profile Settings Tab -->
                        <div class="profile__panel profile__panel--active" id="profile-tab">
                            <form class="profile__form" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PUT')
                                <h2 class="profile__title">Profile Settings</h2>

                                <div class="profile__section">
                                    <h3 class="profile__section-title">Basic Information</h3>

                                    <div class="profile__field" id="nameGroup">
                                        <label class="profile__label" for="name">Name*</label>
                                        <input class="profile__input" id="name" type="text" name="name" value="{{ old('name', auth()->user()->name) }}">
                                        <span class="profile__error" id="nameError"></span>
                                    </div>

                                    <div class="profile__field">
                                        <label class="profile__label">Email</label>
                                        <input class="profile__input profile__input--readonly" type="email" value="{{ auth()->user()->email }}" readonly>
                                        @if(auth()->user()->hasVerifiedEmail())
                                            <span class="profile__help profile__help--success">✓ Email verified</span>
                                        @else
                                            <span class="profile__help profile__help--warning">⚠ Email not verified</span>
                                        @endif
                                        @if(auth()->user()->isAdmin())
                                            <span class="profile__help profile__help--admin">👑 Administrator</span>
                                        @endif
                                    </div>

                                    <div class="profile__field" id="messageGroup">
                                        <label class="profile__label" for="message">Contact details</label>
                                        <textarea class="profile__input" id="message" name="message" placeholder="How to contact you">{{ old('message', auth()->user()->contact_details) }}</textarea>
                                        <span class="profile__error" id="messageError"></span>
                                    </div>

                                    <div class="profile__field profile__field--file" id="avatarGroup">
                                        <label class="profile__label" for="avatar">
                                            Avatar
                                            <span class="profile__file-label">Upload</span>
                                        </label>

                                        <input class="profile__file-input" type="file" name="avatar" id="avatar" accept="image/*">

                                        <div class="profile__avatar">
                                            <img src="{{ auth()->user()->avatar_url }}" alt="Avatar preview" class="profile__avatar-img" id="avatarPreview">

                                            @if(auth()->user()->avatar)
                                                <button type="button" class="profile__avatar-delete" id="deleteAvatarBtn" title="Delete avatar">×</button>
                                            @endif
                                        </div>

                                        <span class="profile__error" id="avatarError"></span>
                                    </div>
                                </div>

                                <div class="profile__section">
                                    <h3 class="profile__section-title">Change Password</h3>
                                    <p class="profile__section-description">Leave empty if you don't want to change your password.</p>

                                    <div class="profile__field profile__field--hidden" id="currentPasswordGroup">
                                        <label class="profile__label" for="current_password">Current Password*</label>
                                        <input class="profile__input" id="current_password" type="password" name="current_password" placeholder="Enter current password">
                                        <span class="profile__error" id="current_passwordError"></span>
                                    </div>

                                    <div class="profile__field" id="passwordGroup">
                                        <label class="profile__label" for="password">New Password</label>
                                        <input class="profile__input" id="password" type="password" name="password" placeholder="Enter new password (min 6 characters)">
                                        <span class="profile__error" id="passwordError"></span>
                                    </div>

                                    <div class="profile__field profile__field--hidden" id="password_confirmationGroup">
                                        <label class="profile__label" for="password_confirmation">Confirm New Password*</label>
                                        <input class="profile__input" id="password_confirmation" type="password" name="password_confirmation" placeholder="Repeat new password">
                                        <span class="profile__error" id="password_confirmationError"></span>
                                    </div>
                                </div>

                                <button type="submit" class="profile__submit-btn">Save Changes</button>
                            </form>
                        </div>

                        <!-- My Lots Tab -->
                        <div class="profile__panel" id="my-lots-tab">
                            <h2 class="profile__title">My Lots</h2>
                            @if($userLots && $userLots->count() > 0)
                                <div class="profile__lots-grid">
                                    @foreach($userLots as $lot)
                                        <div class="profile__lot-card">
                                            <div class="profile__lot-image">
                                                <img src="{{ $lot->image_url }}" alt="{{ $lot->title }}" class="profile__lot-img">
                                                <div class="profile__lot-status profile__lot-status--{{ $lot->status }}">
                                                    {{ ucfirst($lot->status) }}
                                                </div>
                                            </div>
                                            <div class="profile__lot-info">
                                                <div class="profile__lot-title h3">{{ $lot->title }}</div>
                                                <p class="profile__lot-price">{{ formatPrice($lot->getCurrentPrice()) }}</p>
                                                <p class="profile__lot-category">{{ $lot->category->name }}</p>

                                                <div class="profile__lot-actions">
                                                    <a href="{{ route('lot.show', [$lot->category->slug, $lot->slug]) }}" class="profile__lot-btn profile__lot-btn--view">View</a>
                                                    <a href="{{ route('lot.edit', $lot->id) }}" class="profile__lot-btn profile__lot-btn--edit">Edit</a>

                                                    <form method="POST" action="{{ route('lot.toggle-status', $lot->id) }}" class="profile__lot-form">
                                                        @csrf
                                                        @method('PATCH')
                                                        <button type="submit" class="profile__lot-btn profile__lot-btn--{{ $lot->status === 'active' ? 'deactivate' : 'activate' }}">
                                                            {{ $lot->status === 'active' ? 'Deactivate' : 'Activate' }}
                                                        </button>
                                                    </form>

                                                    @if(auth()->user()->isAdmin())
                                                        <form method="POST" action="{{ route('lot.destroy', $lot->id) }}" class="profile__lot-form" onsubmit="return confirm('Are you sure you want to delete this lot?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="profile__lot-btn profile__lot-btn--delete">Delete</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="profile__empty">
                                    <p class="profile__empty-text">You haven't created any lots yet.</p>
                                    <a href="{{ route('lot.create') }}" class="profile__empty-btn">Create Your First Lot</a>
                                </div>
                            @endif
                        </div>

                        <!-- My Bids Tab -->
                        <div class="profile__panel" id="my-bids-tab">
                            <h2 class="profile__title">My Bids</h2>
                            @if($userBids && $userBids->count() > 0)
                                <div class="profile__bids-list">
                                    @foreach($userBids as $bid)
                                        <div class="profile__bid-item">
                                            <div class="profile__bid-lot">
                                                <img src="{{ $bid->lot->image_url }}" alt="{{ $bid->lot->title }}" class="profile__bid-img">
                                                <div class="profile__bid-info">
                                                    <div class="profile__bid-title">{{ $bid->lot->title }}</div>
                                                    <p class="profile__bid-category">{{ $bid->lot->category->name }}</p>
                                                </div>
                                            </div>
                                            <div class="profile__bid-details">
                                                <p class="profile__bid-amount">My bid: {{ formatPrice($bid->bid_amount) }}</p>
                                                <p class="profile__bid-time">{{ $bid->bid_time->format('d.m.Y H:i') }}</p>
                                                <div class="profile__bid-actions">
                                                    <a href="{{ route('lot.show', [$bid->lot->category->slug, $bid->lot->slug]) }}" class="profile__bid-btn">View Lot</a>

                                                    @if(auth()->user()->isAdmin() || $bid->user_id === auth()->id())
                                                        <form method="POST" action="{{ route('bids.destroy', $bid->id) }}" class="profile__bid-form" onsubmit="return confirm('Are you sure you want to delete this bid?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <input type="hidden" name="from_profile" value="1">
                                                            <button type="submit" class="profile__bid-btn profile__bid-btn--delete">Delete Bid</button>
                                                        </form>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="profile__empty">
                                    <p class="profile__empty-text">You haven't placed any bids yet.</p>
                                    <a href="{{ route('home') }}" class="profile__empty-btn">Browse Lots</a>
                                </div>
                            @endif
                        </div>

                        <!-- Won Lots Tab -->
                        @if($wonLots && $wonLots->count() > 0)
                            <div class="profile__panel" id="won-lots-tab">
                                <h2 class="profile__title">Won Lots</h2>
                                <div class="profile__lots-grid">
                                    @foreach($wonLots as $lot)
                                        <div class="profile__lot-card profile__lot-card--won">
                                            <div class="profile__lot-image">
                                                <img src="{{ $lot->image_url }}" alt="{{ $lot->title }}" class="profile__lot-img">
                                                <div class="profile__lot-status profile__lot-status--won">🏆 Won</div>
                                            </div>
                                            <div class="profile__lot-info">
                                                <h3 class="profile__lot-title">{{ $lot->title }}</h3>
                                                <p class="profile__lot-price">Winning bid: {{ formatPrice($lot->getCurrentPrice()) }}</p>
                                                <p class="profile__lot-category">{{ $lot->category->name }}</p>
                                                <p class="profile__lot-date">Won on: {{ \Carbon\Carbon::parse($lot->timer)->format('d.m.Y H:i') }}</p>

                                                @if($lot->user->contact_details)
                                                    <div class="profile__lot-contact">
                                                        <strong>Seller contact:</strong>
                                                        <p>{{ $lot->user->contact_details }}</p>
                                                    </div>
                                                @endif

                                                <div class="profile__lot-actions">
                                                    <a href="{{ route('lot.show', [$lot->category->slug, $lot->slug]) }}" class="profile__lot-btn profile__lot-btn--view">View Details</a>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                    </div>
                </div>

            @endif
        </div>
    </main>
@endsection
