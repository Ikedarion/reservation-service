@extends('layouts.admin')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/manager/restaurant-detail.css') }}">
@endsection

@section('content')
@if(session('success'))
<div class="message message--success">
    {{ session('success')}}
</div>
@endif
@if(session('error'))
<div class="message message--error">
    {{ session('error')}}
</div>
@endif
<p class="heading">
    店舗詳細
</p>
<div class="manager__content">
    <div id="detail" class="detail-card">
        <div class="detail-card__inner">
            <div class="card__header">
                <div class="card__heading">
                    <p class="card__name">{{ $restaurant->name }}</p>
                </div>
            </div>
            <div class="card__inner">
                <div class="card__image">
                    <img src="{{ $restaurant->images}}" alt="Image">
                </div>
                <div class="card__content">
                    <div class="card__tag">
                        @php
                        preg_match('/^(東京都|北海道|.{2,3}県|.{2}府)/u', $restaurant->address, $matches);
                        $prefecture = $matches[0] ?? '';
                        @endphp
                        <p class="tag-p">#{{ $prefecture }}</p>
                        <p class="tag-p">#{{ $restaurant->genre }}</p>
                    </div>
                    <div class="card__address">
                        <p class="card__address-p"></p>
                    </div>
                    <div class="card__description">
                        <p class="card__description-p">{{ $restaurant->description }}</p>
                    </div>
                </div>
            </div>
        </div>
        <button class="edit__submit" id="edit-btn">Edit</button>
    </div>



    <div class="restaurant__group">
        <div id="create-form" class="restaurant__create-form {{ $errors->any() ? 'open' : '' }}">
            <a id="close-form-btn" class="close">
                < 戻る</a>
                    <form id="create-restaurant-form" class="create-form" action="{{ route('manager.updateDetail') }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PATCH')
                        <div class="create-form-group">
                            <label class="create-form__label" for="image">画像</label>
                            <input class="create-form__image" type="file" name="image" id="image" value="{{ old('image',$restaurant->image) }}">
                            @error('image')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="create-form-group">
                            <label class="create-form__label" for="name">店名</label>
                            <input class="create-form__input" type="text" name="name" id="name" value="{{ old('name',$restaurant->name) }}">
                            @error('name')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="create-form-group">
                            <label class="create-form__label" for="genre">ジャンル</label>
                            <select class="create-form__select" name="genre" id="genre">
                                <option value="" hidden>選択</option>
                                @foreach($genres as $genre)
                                <option value="{{ $genre }}" {{ old('genre',$restaurant->genre) == $genre ? 'selected' : ''}}>{{ $genre }}</option>
                                @endforeach
                                @error('genre')
                                <div class="error-message">{{ $message }}</div>
                                @enderror
                            </select>
                        </div>

                        <div class="create-form-group">
                            <label class="create-form__label" for="address">住所</label>
                            <input class="create-form__text" type="text" name="address" id="address" value="{{ old('address',$restaurant->address) }}">
                            @error('address')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="create-form-group">
                            <label class="create-form__label" for="description">説明</label>
                            <textarea class="create-form__textarea" name="description" id="description" rows="4">{{ old('description', $restaurant->description) }}</textarea>
                            @error('description')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                        </div>

                        <button id="submit-button" class="create-form__submit" type="submit">登録する</button>
                    </form>
        </div>


        <div id="preview-card" style="display: none;" class="preview__content">
            <div class="new-card">
                <div class="card__header">
                    <div class="card__heading">
                        <p id="preview-name"></p>
                    </div>
                </div>
                <div class="card__inner">
                    <div class="card__image" id="preview-image" data-image="{{ $restaurant->image ?? 'https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg' }}">
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
            <button id="preview-button" class="preview-btn">プレビューを更新</button>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="{{ asset('js/restaurant-detail.js') }}"></script>
@endpush