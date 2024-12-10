@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/reviews.css') }}">
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
<p class="filter__header">
    レビュー管理
</p>
<div class="review__container">
    <form class="filter-form" action="{{ route('review.filter') }}" method="GET">
        <label for="sort_by" class="filter-form__label">店舗名</label>
        <select name="sort_by" id="sort_by" class="filter-select">
            <option class="status-option" value="" hidden>選択</option>
            @foreach($restaurants as $restaurant )
            <option value="{{ $restaurant->id }}">{{ $restaurant->name }}</option>
            @endforeach
        </select>

        <label for="name" class="filter-form__label">キーワード</label>
        <input class="filter-input" type="text" name="keyword" id="name" value="{{ request('keyword'), old('keyword') }}">

        <label for="sort_by" class="filter-form__label">並び替え</label>
        <select name="sort_by" id="sort_by" class="filter-select">
            <option class="status-option" value="" hidden>選択</option>
            <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : '' }}>新着順</option>
            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>レビュー評価順</option>
        </select>
        <label for="star_rating" class="filter-form__label">星評価</label>
        <select name="star_rating" id="star_rating" class="filter-select">
            <option value="">★ 全て</option>
            @for ($i = 1; $i <= 5; $i++)
                <option value="{{ $i }}" {{ request('star_rating') == $i ? 'selected' : '' }}>★ {{ $i }}</option>
                @endfor
        </select>
        <label for="status" class="filter-form__label">ステータス</label>
        <select class="filter-select" name="status" id="status">
            <option class="status-option" value="" hidden>選択</option>
            <option class="status-option" value="unReplied" {{ request('status') == 'unReplied' ? 'selected' : '' }}>未返信</option>
            <option class="status-option" value="replied" {{ request('status') == 'replied' ? 'selected' : '' }}>返信済み</option>
        </select>

        <label class=" filter-form__label" for="start_date">開始日</label>
        <input class="filter-input" type="date" name="start_date" id="start_date" value="{{ request('start_date', old('start_date')) }}">
        <label class="filter-form__label" for="end_date">終了日</label>
        <input class="filter-input" type="date" name="end_date" id="end_date" value="{{ request('end_date', old('end_date')) }}">
        <div class="filter-button">
            <button type="submit" class="filter-submit">探す</button>
        </div>
    </form>

    <div class="review-list">
        <div class="scroll">
            @foreach($reviews as $review)
            <div class="review-item">
                <div class="review-details">
                    <div class="rating-stars">
                        <span class=" review-user">{{ $review->nickname ?? '匿名ユーザー' }}&nbsp;&nbsp;</span>
                        <span class="rating-stars__item">
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < $review->rating)
                                <i class="las la-star gold"></i>
                                @else
                                <i class="las la-star gray"></i>
                                @endif
                                @endfor
                        </span>
                    </div>
                    <span class="review-date">{{ $review->created_at->format('Y-m-d') }}</span>
                    <div class="review-title">{{ $review->title }}</div>
                    <p class="review-comment">{{ $review->comment}}</p>
                    <div class="review-meta">
                        <div>
                            <button class="reply-button" data-modal-id="modal-{{ $review->id }}">
                                <i class="fa-solid fa-reply"></i>返信</button>
                        </div>

                        @if($review->reply)
                        <span class="response-status">返信済み</span>
                        @else
                        <span class="response-status-red">未返信</span>
                        @endif
                    </div>
                </div>
            </div>

            <div class="modal {{ $errors->has('reply_' . $review->id) ? 'open' : '' }}" id="modal-{{ $review->id }}">
                <div class="modal__inner">
                    <div class="modal-content">
                        <form class="modal-form" action="{{ route('reviews.reply',$review->id )}}" method="POST">
                            @csrf
                            @method('PATCH')
                            <span class="modal__label">評価:</span>
                            <span class="modal__text-stars">
                                @for ($i = 0; $i < 5; $i++)
                                    @if ($i < $review->rating)
                                    <i class="las la-star gold"></i>
                                    @else
                                    <i class="las la-star gray"></i>
                                    @endif
                                    @endfor
                            </span>
                            <span class="modal__label">ニックネーム:</span>
                            <span class="modal__text">{{ $review->nickname ?? '匿名ユーザー' }}</span>

                            <span class="modal__label">投稿日:</span>
                            <span class="modal__text">{{ $review->created_at->format('Y-m-d') }}</span>

                            <span class="modal__label">レビュー:</span>
                            <span class="modal__text">{{ $review->comment }}</span>

                            @if($review->reply)
                            <label class="modal__label">返信:</label>
                            <div class="modal__reply">{{ $review->reply }}</div>
                            @else
                            <label class="modal__label">返信を入力:</label>
                            <textarea class="modal__textarea" name="reply_{{ $review->id }}" id="reply-{{ $review->id }}" rows="4">{{ old('reply_'. $review->id) }}</textarea>
                            @error('reply_' . $review->id)
                            <div class="error">{{ $message }}</div>
                            @enderror
                            <button type="submit" class="submit-reply">送信する</button>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modalTriggers = document.querySelectorAll('.reply-button');
        const closeButtons = document.querySelectorAll('.modal-close');
        const Modals = document.querySelectorAll('.modal');

        modalTriggers.forEach(button => {
            button.addEventListener('click', function() {
                const targetModalId = button.getAttribute('data-modal-id');
                const targetModal = document.getElementById(targetModalId);
                if (targetModal) {
                    targetModal.classList.add('open');
                }
            });
        });

        Modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('open');
                }
            });
        });
    });
</script>
@endpush