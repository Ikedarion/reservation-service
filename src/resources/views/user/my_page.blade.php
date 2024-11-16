@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/my-page.css') }}">
@endsection


@section('content')
<div class="myPage__content">
    <div class="myPage__inner">
        <h3 class="myPage__heading">
            @if(Auth::check())
            {{ Auth::user()->name }}さん
            @else
            Testさん
            @endif
        </h3>

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

        <div class="myPage__items">
            <div class="reservations">
                <div class="res__heading">予約状況</div>

                <div class="scroll">
                    @if($reservations && $reservations->count() > 0)
                    @foreach($reservations as $reservation)
                    <div class="res__container">
                        <form class="res__form" action="{{ route('delete', $reservation->id ) }}" method="post" onsubmit="return confirmDelete('この予約をキャンセルしてよろしいですか？')">
                            @csrf
                            @method('delete')
                            <button class="res__form-submit">
                                <div class="circle">
                                    <div class="circle-x"></div>
                                </div>
                            </button>
                        </form>
                        <div class="res__title">
                            <i class="fas fa-clock"></i>予約&nbsp;{{ $loop->iteration }}
                        </div>
                        <table class="table">
                            <tr class="table__row">
                                <th class="table__header">Shop</th>
                                <td class="table__item" id="name">{{ $reservation->restaurant->name }}</td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Date</th>
                                <td class="table__item" id="date">{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}</td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Time</th>
                                <td class="table__item" id="time">{{ \Carbon\Carbon::parse($reservation->date)->format('H:i') }}</td>
                            </tr>
                            <tr class="table__row">
                                <th class="table__header">Number</th>
                                <td class="table__item" id="number">{{ $reservation->number }}人</td>
                            </tr>
                        </table>
                        <div class="table__header">
                            <a class="modal__button" href="#modal{{$reservation->id}}">
                                <i class="fas fa-edit"></i>&nbsp;変更する
                            </a>
                            <div class="modal {{ $errors->hasAny(['date_' . $reservation->id, 'time_' . $reservation->id, 'number_' . $reservation->id]) ? 'open' : ' ' }}" id="modal{{$reservation->id}}">
                                <div class="modal__inner">
                                    <div class="modal__content">
                                        <a class="close" href="#" onclick="closeModalAndReturn();">×</a>
                                        <form class="modal__form" action="{{ route('update',$reservation->id) }}" method="post">
                                            @csrf
                                            @method('PATCH')
                                            <div class="modal__group">
                                                <input type="hidden" name="restaurant_id" value="{{ $reservation->restaurant->id }}">
                                                <label class="modal__label" for="date_{{$reservation->id}}">日付</label>
                                                <input class="modal__calender" type="date" id="date_{{$reservation->id}}" name="date_{{$reservation->id}}" value="{{ old('date_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}">
                                                @error('date_' . $reservation->id)
                                                <div class="error">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="modal__group">
                                                <label class="modal__label" for="time_{{$reservation->id}}">時間</label>
                                                <input class="modal__input" type="time" id="time_{{$reservation->id}}" name="time_{{$reservation->id}}" value="{{ old('time_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('H:i')) }}" max="23:00" min="06:00">
                                                @error('time_' . $reservation->id)
                                                <div class="error">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                            </div>
                                            <div class="modal__group">
                                                <label class="modal__label" for="number_{{$reservation->id}}">人数</label>
                                                <select class="modal__select" name="number_{{$reservation->id}}" id="number_{{$reservation->id}}">
                                                    <option value="" selected hidden>人数</option>
                                                    @foreach($numbers as $number)
                                                    <option value="{{ $number }}" {{ old('number_' . $reservation->id ,$reservation->number) == $number ? 'selected' : '' }}>{{ $number }}人</option>
                                                    @endforeach
                                                </select>
                                                @error('number_' . $reservation->id)
                                                <div class="error">
                                                    {{ $message }}
                                                </div>
                                                @enderror
                                                <input class="res-modal__button" type="submit" value="登録"></input>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        現在、予約情報はありません。
                    </div>
                    @endif
                </div>
            </div>

            <script>
                function confirmDelete(message) {
                    return confirm(message);
                }

                function closeModalAndReturn() {
                    const openModal = document.querySelector('.modal.open');
                    if (openModal) {
                        openModal.classList.remove('open');
                    }
                }
            </script>
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const openModal = document.querySelector('.modal.open');
                    if (openModal) {
                        openModal.classList.add('open');
                    }
                });
            </script>

            <div class="favorites">
                <div class="fav__heading">お気に入り店舗</div>
                <div class="fav__container">
                    @if($restaurants->isNotEmpty())
                    @foreach( $restaurants as $restaurant )
                    <div class="card">
                        <div class="card__image">
                            <img src="{{ $restaurant->image }}" alt="Image">
                        </div>
                        <div class="card__content">
                            <div class="card__heading">
                                {{ $restaurant->name }}
                            </div>
                            <div class="card__tag">
                                @php
                                preg_match('/^(東京都|北海道|.{2,3}県|.{2}府)/u', $restaurant->address, $matches);
                                $prefecture = $matches[0] ?? '';
                                @endphp
                                <p class="tag-p" class="tag">#{{ $prefecture }}</p>
                                <p class="tag-p" class="tag">#{{ $restaurant->genre }}</p>
                            </div>
                            <div class="card__content-item">
                                <form action="{{ route('detail',$restaurant->id ) }}" method="get">
                                    <input class="details__link" type="submit" value="詳しくみる">
                                </form>
                                <form class="favorite" action="{{ Route('favorite',$restaurant->id) }}" method="post">
                                    @csrf
                                    <input class="favorite__button" type="submit" value="">
                                    @if($restaurant)
                                    <div class="heart-click"><i class="fa-solid fa-heart"></i></div>
                                    @else
                                    <div class="heart"><i class="fa-solid fa-heart"></i></div>
                                    @endif
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        該当する店舗はありません。
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@endsection