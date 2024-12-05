@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
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
                    @if($filteredReservations && !$filteredReservations->isEmpty())
                    @foreach($filteredReservations as $reservation)
                    <div class="res__container">
                        <form class="res__form" action="{{ route('delete', $reservation->id ) }}" method="post" @if($reservation->status === '予約確定')
                            onsubmit="return confirmDelete('この予約をキャンセルしてよろしいですか？')"
                            @endif>
                            @csrf
                            @method('delete')
                            <button class="res__form-submit">
                                <div class="circle">
                                    <div class="circle-x"></div>
                                </div>
                            </button>
                        </form>

                        <div class="res__title">
                            <i class="fa-solid fa-thumbtack"></i>予約&nbsp;{{ $loop->iteration }}
                            <div class="table__header">
                                @if($reservation->status === '来店済み')
                                <a class="modal__button" href="#reviewModal{{$reservation->id}}">
                                    <i class="fa-regular fa-thumbs-up"></i>&nbsp;評価する
                                </a>
                                @include('components.review-form-modal',['reservation' => $reservation,'restaurant' => $restaurant])
                                @elseif($reservation->status == '予約確定')
                                <a class="modal__button" href="#modal{{$reservation->id}}">
                                    <i class="fas fa-edit"></i>&nbsp;変更する
                                </a>
                                @include('components.reservation-edit-modal',['reservation' => $reservation, 'numbers' => $numbers,'restaurant' => $restaurant])
                                @endif
                            </div>
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

                        @if ($reservation->status == '予約確定')
                        <div class="res-items">
                            <div class="qrCode">
                                <a class="qrCode-link" href="{{ route('reservation.QrCode', $reservation->id) }}" target="_blank">
                                    QRコードを開く
                                </a>
                                <span class="material-icons">
                                    open_in_new
                                </span>
                            </div>
                        </div>
                        @endif

                    </div>
                    @endforeach
                    @else
                    <div class="empty-state">
                        現在、予約情報はありません。
                    </div>
                    @endif
                </div>
            </div>

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

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reviewLinks = document.querySelectorAll('.modal__button');
        reviewLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                const targetModalId = link.getAttribute('href').slice(1);
                const targetModal = document.getElementById(targetModalId);
                if (targetModal) {
                    targetModal.classList.add('open');
                }
            });
        });

        const closeButtons = document.querySelectorAll('.close');
        closeButtons.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const modal = link.closest('.modal');
                if (modal) {
                    modal.classList.remove('open');
                }
            });
        });
    });

    function confirmDelete(message) {
        return confirm(message);
    }
</script>
@endpush