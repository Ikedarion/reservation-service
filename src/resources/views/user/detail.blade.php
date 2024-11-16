@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/detail.css') }}">
@endsection

@section('content')
<div class="detail__content">
    <div class="detail__inner">
        <div class="detail__items">
            <div class="detail__heading">
                <a class="home__link" href="/">
                    < </a>
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
                        <p class="address-p">{{ $restaurant->address }}</p>
                    </div>
                    <div class="card__description">
                        <p class="description-p">{{ $restaurant->description }}</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="reservation">
            <div class="res__container">
                <div class="res__heading">予約</div>
                <form class="res__form" action="{{ route('reservation.store') }}" method="post">
                    @csrf
                    <input type="hidden" name="restaurant_id" value="{{ $restaurant->id }}">
                    <input class="res__calender" type="date" id="res-date" name="date" value="{{ old('date') }}">
                    @error('date')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <input class="res__input" type="time" id="res-time" name="time" value="{{ old('time') }}" max="23:00" min="06:00">
                    @error('time')
                    <div class="error">{{ $message }}</div>
                    @enderror
                    <select class="res__select" name="number" id="res-number">
                        <option value="" selected hidden>人数</option>
                        @foreach($numbers as $number)
                        <option value="{{ $number }}" {{ old('number') == $number ? 'selected' : '' }}>{{ $number }}人</option>
                        @endforeach
                    </select>
                    @error('number')
                    <div class="error">{{ $message }}</div>
                    @enderror
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
                console.log('Time changed: ', timeInput.value);
            });

            numberInput.addEventListener('change', function() {
                numberCell.textContent = numberInput.value + '人';
                console.log('Number changed: ', numberInput.value);
            });
        });
    </script>
</div>

@endsection