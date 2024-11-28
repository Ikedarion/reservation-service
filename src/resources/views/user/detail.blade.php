@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/user/detail.css') }}">
<script src="https://js.stripe.com/v3/"></script>
<meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('content')
<div class="detail__content">
    <div class="detail__inner">
        <div class="detail__items">
            <div class="card__container">
                <div class="detail__heading">
                    <a class="home__link" href="/">
                        &lt;
                    </a>
                    <p class="detail__heading-p">{{ $restaurant->name }}</p>
                </div>
                <div class="card">
                    <div class="card__image">
                        <img src="{{ $restaurant->image }}" alt="">
                    </div>
                    <div class="card__content">
                        <div class="card__tag">
                            @php
                            preg_match('/^(東京都|北海道|.{2,3}県|.{2}府)/u', $restaurant->address, $matches);
                            $prefecture = $matches[0] ?? '';
                            @endphp
                            <p class="tag-p" class="tag">#{{ $prefecture }}</p>
                            <p class="tag-p" class="tag">#{{ $restaurant->genre }}</p>
                        </div>
                        <div class="card__address">
                            <p class="address-p"></p>
                        </div>
                        <div class="card__description">
                            <p class="description-p">{{ $restaurant->description }}</p>
                        </div>
                        <div class="card__rating">
                            @if($reviews->isNotEmpty())
                            <a href="#modal" class="review">レビューを見る</a>
                            <span class="rating-text"><i class="fa-solid fa-star" style="color: #ffcc00; "></i>&nbsp;{{ number_format($averageRating, 1) }}&nbsp;({{ $reviews->count()}}件)</span>
                            @else
                            <p class="review">レビュー(0件)</p>
                            @endif
                        </div>
                    </div>
                </div>

                <div class="modal" id="modal">
                    <div class="modal__inner">
                        <div class="close">×</div>
                        <h4>レビュー</h4>
                        <div class=" modal__content">
                            @foreach($reviews as $review)
                            <div class="review-rating">
                                <span>
                                    @for ($i = 0; $i < 5; $i++)
                                        @if ($i < $review->rating)
                                        <i class="fa-solid fa-star" style="color: #ffcc00; "></i>
                                        @else
                                        <i class="fa-solid fa-star" style="color: #cfd9e2; "></i>
                                        @endif
                                        @endfor
                                </span>
                                <span class="review-item">{{ $review->created_at->format('Y-m-d') }}</span>
                            </div>
                            <div class="review-comment">
                                <p>{{ $review->comment }}</p>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>


            <div class="reservation">
                <div class="res__container">
                    <div class="res__heading">予約</div>
                    <form class="res__form" action="{{ route('payment.createCheckoutSession') }}" method="post">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                        <input class="res__calender" type="date" id="res-date" name="date" value="{{ old('date') }}">
                        <div class="error" id="error-date"></div>

                        <input class="res__input" type="time" id="res-time" name="time" value="{{ old('time') }}" max="23:00" min="06:00">
                        <div class="error" id="error-time"></div>

                        <select class="res__select" name="number" id="res-number">
                            <option value="" selected hidden>人数</option>
                            @foreach($numbers as $number)
                            <option value="{{ $number }}" {{ old('number') == $number ? 'selected' : '' }}>{{ $number }}人</option>
                            @endforeach
                        </select>
                        <div class="error" id="error-number"></div>

                        <table class="table">
                            <tr class="table__row">
                                <th class="table__header">Shop</th>
                                <td class="table__item" id="name">{{ $restaurant->name }}</td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Date</th>
                                <td class="table__item" id="date"></td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Time</th>
                                <td class="table__item" id="time"></td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Number</th>
                                <td class="table__item" id="number"></td>
                            </tr>
                        </table>
                        <input class="res__button" type="submit" value="予約する"></input>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const dateInput = document.getElementById('res-date');
        const timeInput = document.getElementById('res-time');
        const numberInput = document.getElementById('res-number');
        const dateCell = document.getElementById('date');
        const timeCell = document.getElementById('time');
        const numberCell = document.getElementById('number');

        dateInput.addEventListener('change', function() {
            dateCell.textContent = dateInput.value;
        });
        timeInput.addEventListener('change', function() {
            timeCell.textContent = timeInput.value;
        });
        numberInput.addEventListener('change', function() {
            numberCell.textContent = numberInput.value + '人';
        });

        const reviewLinks = document.querySelectorAll('.review');
        const closeButtons = document.querySelectorAll('.close');

        reviewLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetModalId = link.getAttribute('href').slice(1);
                const targetModal = document.getElementById(targetModalId);
                if (targetModal) {
                    targetModal.classList.add('open');
                }
            });
        });
        closeButtons.forEach(button => {
            button.addEventListener('click', function() {
                const modal = button.closest('.modal');
                if (modal) {
                    modal.classList.remove('open');
                }
            });
        });

    });
</script>
<script src="{{ asset('js/stripe.js') }}" defer></script>
@endpush