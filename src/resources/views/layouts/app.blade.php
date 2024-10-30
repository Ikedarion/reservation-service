<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rese</title>
    <link rel="stylesheet" href="{{ asset('css/layouts/common.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sanitize.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    @yield('css')
</head>

<body>
    <div class="app">
        <header class="header">
            <a href="@yield('link_url')" class="menu__link">
                <div class="square">
                    <div class="line long"></div>
                    <div class="line medium"></div>
                    <div class="line short"></div>
                </div>
            </a>
            <h1 class="header__heading">Rese</h1>
        </header>
        <main>
            @yield('content')
        </main>
    </div>
</body>

</html>