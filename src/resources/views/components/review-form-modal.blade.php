<div class="modal {{$errors->hasAny(['nickname_' . $reservation->id, 'rating_' . $reservation->id, 'comment_' . $reservation->id, 'title_' . $reservation->id]) ? 'open' : ''}}
" id="reviewModal{{ $reservation->id }}">
    <div class="review-modal__inner">
        <div class="review-modal__content">
            <form class="review-modal__form" action="{{ route('review', $reservation->id) }}" method="POST">
                @csrf
                <p class="modal-close">×</p>
                <div class="review-modal__header">
                    <h4 class="h4">レビューを投稿</h4>
                </div>
                <div class="review-modal__header">
                    <div class="review-modal__image"><img src="{{ $reservation->restaurant->image }}" alt=""></div>
                    <div class="review-modal__restaurant">
                        <p class="review-modal__p">{{ $reservation->restaurant->name }}</p>
                        <span class="review-modal__p">来店日</span>
                        <span class="review-modal__p">{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}</span>
                    </div>
                </div>

                <div class="review-modal__group">
                    <label class="review-modal__label" for="nickname">ニックネーム</label>
                    <input class="review-modal__input" type="text" name="nickname_{{ $reservation->id }}" id="nickname_{{ $reservation->id }}" value="{{ old('nickname_' . $reservation->id) }}">
                </div>
                @error('nickname_' . $reservation->id)
                <div class="error">{{ $message }}</div>
                @enderror

                <div class="review-modal__group">
                    <label class="review-modal__label">評価</label>
                    <div class="star-rating">
                        <input type="radio" name="rating_{{ $reservation->id }}" value="5" id="1-star-{{ $reservation->id }}" class="star-input">
                        <label for="1-star-{{ $reservation->id }}" class="star">&#9733;</label>

                        <input type="radio" name="rating_{{ $reservation->id }}" value="4" id="2-star-{{ $reservation->id }}" class="star-input">
                        <label for="2-star-{{ $reservation->id }}" class="star">&#9733;</label>

                        <input type="radio" name="rating_{{ $reservation->id }}" value="3" id="3-star-{{ $reservation->id }}" class="star-input">
                        <label for="3-star-{{ $reservation->id }}" class="star">&#9733;</label>

                        <input type="radio" name="rating_{{ $reservation->id }}" value="2" id="4-star-{{ $reservation->id }}" class="star-input">
                        <label for="4-star-{{ $reservation->id }}" class="star">&#9733;</label>

                        <input type="radio" name="rating_{{ $reservation->id }}" value="1" id="5-star-{{ $reservation->id }}" class="star-input">
                        <label for="5-star-{{ $reservation->id }}" class="star">&#9733;</label>
                    </div>
                </div>
                @error('rating_' . $reservation->id)
                <div class="error">{{ $message }}</div>
                @enderror

                <div class="review-modal__group">
                    <label class="review-modal__label" for="title_{{$reservation->id}}">タイトル</label>
                    <input class="review-modal__input" type="text" name="title_{{ $reservation->id }}" value="{{ old('title_' . $reservation->id) }}" id="title_{{$reservation->id}}"></input>
                </div>
                @error('title_' . $reservation->id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror

                <div class="review-modal__group">
                    <label class="review-modal__label" for="comment_{{$reservation->id}}">本文</label>
                    <textarea class="review-modal__textarea" name="comment_{{ $reservation->id }}" id="comment_{{$reservation->id}}" rows="4">{{ old('comment_' . $reservation->id)}}</textarea>
                </div>
                @error('comment' . $reservation->id)
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input class="review-modal__button" type="submit" value="送信する">
            </form>
        </div>
    </div>
</div>