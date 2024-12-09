@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/line-awesome@1.3.0/dist/line-awesome/css/line-awesome.min.css">
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
                            @if($reviews)
                            <a href="#modal" class="review">
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <=floor($averageRating))
                                    <i class="las la-star gold star"></i>
                                    @elseif ($i - $averageRating < 1)
                                        <i class="las la-star-half-alt gold star"></i>
                                        @else
                                        <i class="las la-star gray star"></i>
                                        @endif
                                        @endfor
                                        <span class="average">{{ number_format($averageRating, 1) }}</span>
                                        ({{ $reviews->count()}}件)
                            </a>
                            @endif
                        </div>
                    </div>
                </div>
                <x-review-modal :ratingCounts="$ratingCounts" :restaurant="$restaurant" :reviews="$reviews" :averageRating="$averageRating" :totalReviews="$totalReviews" />
            </div>


            <div class="reservation">
                <div class="res__container">
                    <div class="res__heading">予約</div>
                    <form class="res__form" action="{{ route('payment.createCheckoutSession') }}" method="post">
                        @csrf
                        <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                        <input class="res__calender" type="date" id="res-date" name="date" value="{{ old('date') }}">
                        <div class="error" id="error-date"></div>

                        <input class="res__input" type="time" id="res-time" name="time" value="{{ old('time') }}" max="22:00" min="09:00">
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
        const Modals = document.querySelectorAll('.modal');

        reviewLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetModalId = link.getAttribute('href').slice(1);
                const targetModal = document.getElementById(targetModalId);
                if (targetModal) {
                    targetModal.classList.add('open');

                    sessionStorage.setItem('modalId', targetModalId);
                }
            });
        });
        Modals.forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    modal.classList.remove('open');
                    sessionStorage.removeItem('modalId');
                }
            });
        });

        window.onload = function() {
            const modalId = sessionStorage.getItem('modalId');

            if (modalId) {
                const targetModal = document.getElementById(modalId);
                if (targetModal) {
                    targetModal.classList.add('open');
                }
            }
        };
    });
</script>
<script src="{{ asset('js/stripe.js') }}" defer></script>
@endpush