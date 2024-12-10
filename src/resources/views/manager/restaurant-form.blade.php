@extends('layouts.admin')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/manager/restaurant-form.css') }}">
@endsection

@section('content')
<div class="restaurant__content">
    @if(empty($restaurant))
    <div id="modal" class="restaurant__header">
        <div class="modal__inner">
            <button id="show-form-btn" class="create-button"><i class="fa-solid fa-plus"></i>&nbsp;店舗を作成する
            </button>
        </div>
    </div>
    <p class="heading">
        店舗詳細
    </p>
    <a id="close-form-btn" class="close">< 戻る</a>
    <div class="restaurant__group">
        <div id="create-form" class="restaurant__create-form {{ $errors->any() ? 'open' : '' }}">
            <form id="create-restaurant-form" class="create-form" action="{{ route('manager.store') }}" method="post" enctype="multipart/form-data">
                @csrf
                <div class="create-form-group">
                    <label class="create-form__label" for="image">画像</label>
                    <input class="create-form__image" type="file" name="image" id="image" value="{{ old('image') }}">
                    @error('image')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="create-form-group">
                    <label class="create-form__label" for="name">店名</label>
                    <input class="create-form__input" type="text" name="name" id="name" value="{{ old('name') }}">
                    @error('name')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="create-form-group">
                    <label class="create-form__label" for="genre">ジャンル</label>
                    <select class="create-form__select" name="genre" id="genre">
                        <option value="" hidden>選択</option>
                        @foreach($genres as $genre)
                        <option value="{{ $genre }}" {{ old('genre') == $genre ? 'selected' : ''}}>{{ $genre }}</option>
                        @endforeach
                        @error('genre')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </select>
                </div>

                <div class="create-form-group">
                    <label class="create-form__label" for="address">住所</label>
                    <input class="create-form__text" type="text" name="address" id="address" value="{{ old('address') }}">
                    @error('address')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="create-form-group">
                    <label class="create-form__label" for="description">説明</label>
                    <textarea class="create-form__textarea" name="description" id="description" rows="4">{{ old('description') }}</textarea>
                    @error('description')
                    <div class="error-message">{{ $message }}</div>
                    @enderror
                </div>

                <div class="create-form__group-submit">
                    <button id="preview-button" class="preview__submit" type="submit">プレビューを更新</button>
                    <button id="submit-button" class="create-form__submit" type="submit">登録する</button>
                </div>
            </form>
        </div>

        <div id="preview-card" style="display: none;" class="preview__content">
            <div class="new__card">
                <div class="header__item">
                    <div class="card__heading">
                        <p id="preview-name"></p>
                    </div>
                </div>
                <div class="card__inner">
                    <div class="card__image" id="preview-image">
                        <img src="" alt="Image" id="preview-image-tag">
                    </div>
                    <div class="card__content">
                        <div class="card__tag">
                            <span class="tag-p" id="preview-address"></span>
                            <span class="tag-p" id="preview-genre"></span>
                        </div>
                        <div class="card__description">
                            <p class="card__description-p" id="preview-description"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/restaurant-form.js') }}"></script>
@endpush