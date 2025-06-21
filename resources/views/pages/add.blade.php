@extends('layouts.internal')

@section('title', 'Add Lot')

@section('content')
    <main>
        <div class="container">
            <x-partials.breadcrumbs :breadcrumbs="$breadcrumbs" />

            <form class="form form--add-lot {{ $errors->any() ? 'form--invalid' : '' }}"
                  action="{{ route('lot.store') }}"
                  method="POST"
                  enctype="multipart/form-data"
                  novalidate>
                @csrf
                <h1 class="form__title h1">Add Lot</h1>

                <div class="form__item {{ $errors->has('lot_name') ? 'form__item--invalid' : '' }}" id="lot_nameGroup">
                    <label class="form__label" for="lot_name">Name</label>
                    <input class="form__input" id="lot_name" type="text" name="lot_name" placeholder="Enter lot name" value="{{ old('lot_name') }}">
                    <span class="form__error" id="lot_nameError">
                        @error('lot_name'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('category') ? 'form__item--invalid' : '' }}" id="categoryGroup">
                    <label class="form__label" for="category">Category</label>
                    <select class="form__input" id="category" name="category">
                        <option value="">Select category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category['name'] }}" {{ old('category') == $category['name'] ? 'selected' : '' }}>
                                {{ $category['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <span class="form__error" id="categoryError">
                        @error('category'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('message') ? 'form__item--invalid' : '' }}" id="messageGroup">
                    <label class="form__label" for="message">Description</label>
                    <textarea class="form__input" id="message" name="message" placeholder="Write lot description">{{ old('message') }}</textarea>
                    <span class="form__error" id="messageError">
                        @error('message'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item form__item--img {{ $errors->has('lot_img') ? 'form__item--invalid' : '' }}" id="lot_imgGroup">
                    <label class="form__label" for="lot_img">
                        Image
                        <span class="form__file-label">Upload</span>
                    </label>
                    <input class="form__input-file" type="file" name="lot_img" id="lot_img">
                    <div class="form__preview">
                        <img src="" alt="Image preview" class="form__preview-img">
                    </div>
                    <span class="form__error" id="lot_imgError">
                        @error('lot_img'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('lot_rate') ? 'form__item--invalid' : '' }}" id="lot_rateGroup">
                    <label class="form__label" for="lot_rate">Starting Price</label>
                    <input class="form__input" id="lot_rate" type="number" name="lot_rate" placeholder="0" value="{{ old('lot_rate') }}">
                    <span class="form__error" id="lot_rateError">
                        @error('lot_rate'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('lot_step') ? 'form__item--invalid' : '' }}" id="lot_stepGroup">
                    <label class="form__label" for="lot_step">Bid Step</label>
                    <input class="form__input" id="lot_step" type="number" name="lot_step" placeholder="0" value="{{ old('lot_step') }}">
                    <span class="form__error" id="lot_stepError">
                        @error('lot_step'){{ $message }}@enderror
                    </span>
                </div>

                <div class="form__item {{ $errors->has('timer') ? 'form__item--invalid' : '' }}" id="timerGroup">
                    <label class="form__label" for="timer">Auction End Date</label>
                    <input class="form__input form__input--date" id="timer" type="date" name="timer" value="{{ old('timer') }}">
                    <span class="form__error" id="timerError">
                        @error('timer'){{ $message }}@enderror
                    </span>
                </div>

                <button type="submit" class="form__submit button">Add Lot</button>
            </form>
        </div>
    </main>
@endsection
