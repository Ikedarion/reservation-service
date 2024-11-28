<div class="modal {{$errors->hasAny(['rating','comment']) ? 'open' : ' '}}" id="reviewModal{{ $reservation->id }}">
    <div class="review-modal__inner">
        <div class="modal__content">
            <a class="close" href="#">×</a>
            <form class="modal__form" action="{{ route('review', $reservation->id) }}" method="POST">
                @csrf
                <div class="review-modal__group">
                    <label class="review-modal__label" for="rating_{{$reservation->id}}">評価&nbsp;<span>※必須</span></label>
                    <select class="review-modal__input" name="rating" id="rating_{{$reservation->id}}">
                        <option value="" hidden>選択してください</option>
                        <option value="5">5 - とても良い</option>
                        <option value="4">4 - 良い</option>
                        <option value="3">3 - 普通</option>
                        <option value="2">2 - 悪い</option>
                        <option value="1">1 - とても悪い</option>
                    </select>
                </div>
                @error('rating')
                <div class="error">{{ $message }}</div>
                @enderror
                <div class="review-modal__group">
                    <label class="review-modal__label" for="comment_{{$reservation->id}}">本文</label>
                    <textarea class="review-modal__textarea" name="comment" id="comment_{{$reservation->id}}" rows="4"></textarea>
                </div>
                @error('comment')
                <div class="error">
                    {{ $message }}
                </div>
                @enderror
                <input class="res-modal__button" type="submit" value="評価を送信">
            </form>
        </div>
    </div>
</div>