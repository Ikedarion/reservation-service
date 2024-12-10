<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/admin.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    @yield('css')
</head>

<style>
    .h1 {
        color: #0854d8f7;
        padding-left: 1.5%;
        margin: -7px 0px 0px 10px;
        font-size: 28px;
        display: inline-block;
    }
</style>

<body>
    <header class="header">
        <h2 class="header__title">Rese</h2>
        <div class="header__item">@yield('item')</div>
        <div class="header__user-info">
            <span class="user-name">{{ auth()->user()->name}}&nbsp;さん</span>
        </div>
    </header>
    <div class="layout">
        <nav class="menu">
            @if(auth()->check())
            @if(auth()->user()->role === '管理者')
            <div class="menu__content">
                <a class="menu__link" href="/">ホーム</a>
                <a class="menu__link" href="{{ route('admin.index') }}">ユーザー一覧</a>
                <a class="menu__link" href="{{ route('admin.showSendMailForm') }}">メール送信フォーム</a>
                <form class="menu__form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="submit" value="ログアウト">
                </form>
            </div>
            @elseif(auth()->user()->role === '店舗代表者')
            <div class="menu__content">
                <a class="menu__link" href="{{ route('manager.index') }}">予約一覧</a>
                <a class="menu__link" href="{{ route('manager.showReviews') }}">レビュー管理画面</a>
                <a class="menu__link" href="{{ route('manager.detail') }}">店舗詳細</a>
                <a class="menu__link" href="{{ route('reservation.scan') }}">QRCode照合画面</a>
                <form class="menu__form" action="{{ route('logout') }}" method="POST">
                    @csrf
                    <input type="submit" value="ログアウト">
                </form>
            </div>
            @endif
            @endif
        </nav>

    </div>
    <div class="header__item">@yield('item')</div>
    </div>

    <<main class="main__content">
        @yield('content')
        </main>

        @stack('scripts')
</body>

</html>