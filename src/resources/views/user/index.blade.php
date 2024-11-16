@extends('layouts.app')

@if(Auth::check())
@section('link_url','/menu/user')
@endif

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/index.css') }}">
@endsection

@section('item')
<form action="{{ route('search') }}" class="form" method="get">
    <select class="select" name="area">
        <option class="responsive-area" value="" select hidden></option>
        <option value="">全て</option>
        @foreach($areas as $area)
        <option value="{{ $area }}">{{ $area }}</option>
        @endforeach
    </select>
    <select class="select" name="genre">
        <option class="responsive-genre" value="" select hidden></option>
        <option value="">全て</option>
        @foreach($genres as $genre)
        <option value="{{ $genre }}">{{ $genre }}</option>
        @endforeach
    </select>
    <script>
        function updateOptions() {
            const areaOption = document.querySelector('.responsive-area');
            const genreOption = document.querySelector('.responsive-genre');

            // ウィンドウの幅が480px以下の場合のみテキストを変更
            if (window.innerWidth <= 480) {
                areaOption.textContent = "area";
                genreOption.textContent = "genre";
            } else {
                areaOption.textContent = "All area";
                genreOption.textContent = "All genre";
            }
        }
        document.addEventListener('DOMContentLoaded', updateOptions);
        window.addEventListener('resize', updateOptions);
    </script>
    <button class="submit" type="submit" name="button">
        <i class="fas fa-search"></i>
    </button>
    <input class="input" name="keyword" type="text" placeholder="Search...">
</form>
@endsection

@section('content')
<div class="home_content">
    <div class="home__inner">
        <div class="card__container">
            @foreach( $restaurants as $restaurant )
            <div class="card">
                <div class="card__image">
                    <img src="{{ $restaurant->image }}" alt="">
                </div>
                <div class="card__content">
                    <div class="card__heading">
                        {{ $restaurant->name }}
                    </div>
                    @php
                    preg_match('/^(東京都|北海道|.{2,3}県|.{2}府)/u', $restaurant->address, $matches);
                    $prefecture = $matches[0] ?? '';
                    @endphp
                    <div class="card__tag">
                        <p class="tag-p" class="tag">#{{ $prefecture }}</p>
                        <p class="tag-p" class="tag">#{{ $restaurant->genre }}</p>
                    </div>
                    <div class="card__content-item">
                        <form action="{{ route('detail',$restaurant->id ) }}" method="get">
                            <input class="details__link" type="submit" value="詳しくみる">
                        </form>
                        <form class="favorite" action="{{ route('favorite', $restaurant->id) }}" method="post">
                            @csrf
                            <input type="submit" class="favorite__button" name="favorite" value=''>
                            @if(in_array($restaurant->id,$favoriteIds))
                            <div class="heart-click"><i class="fa-solid fa-heart"></i></div>
                            @else
                            <div class="heart"><i class="fa-solid fa-heart"></i></div>
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