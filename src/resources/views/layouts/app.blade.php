<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">
    @yield('css')
</head>

<body>
    <header class="header">
        <div class="app">
            @if(auth()->check())
            @if(auth()->user()->role === '管理者')
            <div class="menu">
                <a href="#" class="menu__link" onclick="toggleMenu(event)">
                    <div class="square">
                        <div class="line long"></div>
                        <div class="line medium"></div>
                        <div class="line short"></div>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <div class="admin-item">
                        <h2 class="h2">Rese</h2>
                        <a class="admin__link" href="/">ホーム</a>
                        @if (auth()->user()->role === '管理者' && auth()->user()->restaurant)
                        <a class="manager__link" href="{{ route('manager.index') }}">予約一覧</a>
                        <a class="manager__link" href="{{ route('manager.showReviews') }}">レビュー管理画面</a>
                        <a class="manager__link" href="{{ route('manager.detail') }}">店舗詳細</a>
                        <a class="manager__link" href="{{ route('reservation.scan') }}">QRCode照合画面</a>
                        @endif
                        <a class="admin__link" href="{{ route('admin.index') }}">ユーザー一覧</a>
                        <a class="admin__link" href="{{ route('admin.showSendMailForm') }}">メール送信フォーム</a>
                        <form class="admin-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <input type="submit" value="ログアウト">
                        </form>
                    </div>
                </div>
            </div>
            @elseif(auth()->user()->role === '店舗代表者')
            <div class="menu">
                <a href="#" class="menu__link" onclick="toggleMenu(event)">
                    <div class="square">
                        <div class="line long"></div>
                        <div class="line medium"></div>
                        <div class="line short"></div>
                    </div>
                </a>
                <div class="dropdown-menu">
                    <div class="manager-item">
                        <h2 class="h2">Rese</h2>
                        <a class="manager__link" href="{{ route('manager.index') }}">予約一覧</a>
                        <a class="manager__link" href="{{ route('manager.showReviews') }}">レビュー管理画面</a>
                        <a class="manager__link" href="{{ route('manager.detail') }}">店舗詳細</a>
                        <a class="manager__link" href="{{ route('reservation.scan') }}">QRCode照合画面</a>
                        <form class="manager-form" action="{{ route('logout') }}" method="POST">
                            @csrf
                            <input type="submit" value="ログアウト">
                        </form>
                    </div>
                </div>
            </div>
            @elseif(auth()->user()->role === 'ユーザー')
            <a href="@yield('link_url')" class="user-menu__link">
                <div class="square">
                    <div class="line long"></div>
                    <div class="line medium"></div>
                    <div class="line short"></div>
                </div>
            </a>
            @endif
            @else
            <a href="@yield('link_url')" class="user-menu__link">
                <div class="square">
                    <div class="line long"></div>
                    <div class="line medium"></div>
                    <div class="line short"></div>
                </div>
            </a>
            @endif

            <h1 class="h1">Rese</h1>
        </div>
        <div class="header__item">@yield('item')</div>
    </header>

    <main>
        @yield('content')
    </main>

    <script src="{{ asset('js/common.js') }}" defer></script>
    @stack('scripts')
</body>

</html>