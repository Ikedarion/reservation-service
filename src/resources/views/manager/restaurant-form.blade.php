@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager/restaurant-form.css') }}">
@endsection

@section('content')
<div class="manager__content">
    <div class="restaurant__inner">
        @if(empty($restaurant))
        <div id="modal" class="restaurant__header">
            <div class="modal__inner">
                <button id="show-form-btn" class="create-button"><i class="fa-solid fa-plus"></i>&nbsp;店舗を作成する
                </button>
            </div>
        </div>
        <div class="restaurant__grope">
            <div id="create-form" class="restaurant__create-form {{ $errors->any() ? 'open' : '' }}">
                <form id="create-restaurant-form" class="create-form" action="{{ route('manager.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <a id="close-form-btn" class="close">×</a>
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
                        <label class="create-form__label" for="auto-tag">タグ</label>
                        <input id="auto-tag" class="create-form__input" type="text" name="tag" disabled>
                    </div>

                    <div class="create-form-group">
                        <label class="create-form__label" for="description">説明</label>
                        <textarea class="create-form__textarea" name="description" id="description" rows="4">{{ old('description') }}</textarea>
                        @error('description')
                        <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="create-form__group-submit">
                        <button id="preview-button" class="preview__submit" type="submit">プレビューを表示</button>
                        <button id="submit-button" class="create-form__submit" type="submit">登録する</button>
                    </div>
                </form>
            </div>

            <div id="preview-card" style="display: none;" class="preview__content">
                <a id="close-preview-btn" class="close-preview">閉じる</a>
                <div class="new__card">
                    <div class="header__item">
                        <p class="home__link" href="">
                            <
                                </p>
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

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const genreSelect = document.getElementById("genre");
                const addressInput = document.getElementById("address");
                const autoTagInput = document.getElementById("auto-tag");

                function extractPrefecture(address) {
                    const prefecturePattern = /(東京都|北海道|(?:京都|大阪)府|[一-龯]{2,3}県)/;
                    const match = address.match(prefecturePattern);
                    return match ? match[0] : "";
                }

                // タグの更新
                function updateAutoTags() {
                    const genre = genreSelect.value.trim();
                    const prefecture = extractPrefecture(addressInput.value).trim();

                    const autoTags = [genre ? `#${genre}` : "", prefecture ? `#${prefecture}` : ""].filter(tag => tag);

                    autoTagInput.value = autoTags.join(" ");
                    console.log("Auto Tag Input Value after update:", autoTagInput.value);
                }

                genreSelect.addEventListener("change", updateAutoTags);
                addressInput.addEventListener("input", updateAutoTags);

                document.getElementById("preview-button").addEventListener("click", function(event) {
                    event.preventDefault(); // フォーム送信を防ぐ

                    const name = document.getElementById("name").value;
                    const genre = document.getElementById("genre").value;
                    const address = document.getElementById("address").value;
                    const description = document.getElementById("description").value;
                    const image = document.getElementById("image").files[0];

                    document.getElementById("preview-name").textContent = name;
                    document.getElementById("preview-genre").textContent = `#${genre}`;

                    // 住所から都道府県だけを抽出して表示
                    const prefecture = extractPrefecture(address);
                    document.getElementById("preview-address").textContent = `#${prefecture}`;

                    document.getElementById("preview-description").textContent = description;

                    const previewImageTag = document.getElementById("preview-image-tag");

                    if (image) {
                        const reader = new FileReader();
                        reader.onload = function(e) {
                            previewImageTag.src = e.target.result;
                            document.getElementById("preview-card").style.display = "block";
                        };
                        reader.readAsDataURL(image);
                    } else {
                        previewImageTag.src = "https://coachtech-matter.s3-ap-northeast-1.amazonaws.com/image/sushi.jpg";
                        document.getElementById("preview-card").style.display = "block";
                    }
                });

                const showFormBtn = document.getElementById("show-form-btn");
                const createForm = document.getElementById("create-form");
                const modal = document.getElementById("modal");
                const previewCard = document.getElementById("preview-card");
                const closePreviewBtn = document.getElementById("close-preview-btn");

                if (createForm.classList.contains('open')) {
                    createForm.style.display = "block";
                    modal.style.display = "none";
                } else {
                    createForm.style.display = "none"; // 'open' がない場合、フォームを非表示
                    modal.style.display = "block"; // モーダルを表示
                }

                showFormBtn.addEventListener('click', function() {
                    modal.style.display = "none";
                    createForm.style.display = "block";
                    createForm.classList.add('open');
                });

                document.getElementById("close-form-btn").addEventListener("click", function() {
                    createForm.style.display = "none";
                    modal.style.display = "block";
                    createForm.classList.remove('open');
                });

                closePreviewBtn.addEventListener("click", function() {
                    previewCard.style.display = "none";
                });

            });
        </script>
    </div>
</div>

@endsection