<div class="modal" id="modal">
    <div class="modal__inner">
        <div class="modal__header">レビュー</div>
        <div class="modal__content">
            <div class="modal__heading">
                <div class="modal__heading-items">
                    <div class="modal__text">{{ number_format($averageRating, 2) }}</div>
                    <div class="review-count">({{ $reviews->count()}}件)</div>
                    <div class="star-rating">
                        @for ($i = 1; $i <= 5; $i++)
                            @if ($i <=floor($averageRating))
                            <i class="las la-star gold"></i>
                            @elseif ($i - $averageRating < 1)
                                <i class="las la-star-half-alt gold"></i>
                                @else
                                <i class="las la-star gray"></i>
                                @endif
                                @endfor
                    </div>
                </div>

                <div class="rating-distribution">
                    @foreach ($ratingCounts as $rating => $count)
                    <div class="rating-item">
                        <div class="rating-item-left">
                            <span class="star-rating">★{{ $rating }}</span>
                        </div>
                        <div class="rating-item-right">
                            <div class="rating-bar">
                                @if ($totalReviews > 0)
                                <div class="bar" style="width: {{ ($count / $totalReviews) * 100 }}%"></div>
                                @else
                                <div class="bar" style="width: 0%"></div>
                                @endif
                            </div>
                        </div>
                        <p class="percentage">{{ $count }}件</p>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="form">
                <form action="{{ route('reviews.filter',$restaurant->id ) }}" method="GET">
                    <select class="select-sort_by" name="sort_by" id="sort_by">
                        <option value="newest" {{ request('sort_by') == 'newest' ? 'selected' : ''}} select>新着順</option>
                        <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : ''}}>レビュー評価順</option>
                    </select>

                    <select class="select" name="star_rating" id="star_rating">
                        <option value="">★ 全て</option>
                        <option value="1" {{ request('star_rating') == '1' ? 'selected' : '' }}>★ 1</option>
                        <option value="2" {{ request('star_rating') == '2' ? 'selected' : '' }}>★ 2</option>
                        <option value="3" {{ request('star_rating') == '3' ? 'selected' : '' }}>★ 3</option>
                        <option value="4" {{ request('star_rating') == '4' ? 'selected' : '' }}>★ 4</option>
                        <option value="5" {{ request('star_rating') == '5' ? 'selected' : '' }}>★ 5</option>
                    </select>
                    <button class="filter-submit" type="submit">
                        a
                    </button>
                </form>
            </div>



            <div class="review__content">
                @foreach($reviews as $review)
                <div class="review__container">
                    <div class="review-rating">
                        <span>
                            @for ($i = 0; $i < 5; $i++)
                                @if ($i < $review->rating)
                                <i class="las la-star gold" style="margin-right: -3px; font-size: 12.5px"></i>
                                @else
                                <i class="las la-star gray" style="margin-right: -3px; font-size: 12.5px"></i>
                                @endif
                                @endfor
                        </span>
                        <span class="review-item">{{ $review->created_at->format('Y-m-d') }}</span>
                    </div>
                    <i class="far fa-user-circle"></i><span class="review-name">{{ $review->nickname ?? '匿名ユーザー' }}</span>
                    <p class="review-title">{{ $review->title }}</p>
                    <div class="review-comment">
                        <p>{{ $review->comment }}</p>
                    </div>
                    <div class="report">
                        <p class="report-link">不適切なレビューを報告</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>