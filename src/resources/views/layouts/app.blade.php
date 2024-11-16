<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/line-awesome/1.3.0/line-awesome/css/line-awesome.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@400;700&display=swap" rel="stylesheet">

    @yield('css')
</head>
<style>
    body {
        font-family: 'Noto Sans JP', sans-serif;
    }
</style>

<body>
    <header class="header">
        <div class="app">
            <a href="@yield('link_url')" class="menu__link">
                <div class="square">
                    <div class="line long"></div>
                    <div class="line medium"></div>
                    <div class="line short"></div>
                </div>
            </a>
            <h1 class="h1">Rese</h1>
        </div>
        <div class="header__item">@yield('item')</div>
    </header>

    <main>
        @yield('content')
    </main>
</body>

</html>