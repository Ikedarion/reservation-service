<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<style>
    .content {
        height: 100%;
        max-width: 1400px;
    }

    .item {
        width: 100%;
        margin-top: 180px;
        text-align: center;
    }

    .link {
        text-decoration: none;
        display: block;
        color: rgb(48, 93, 255);
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 20px;
        cursor: pointer;
    }

    .form input {
        border: none;
        background-color: white;
        color: rgb(48, 93, 255);
        margin-bottom: 15px;
        font-weight: bold;
        font-size: 20px;
        cursor: pointer;
    }

    .form input:hover {
        transform: scale(1.2);
    }

    .link:hover {
        transform: scale(1.2);
    }
</style>

<body>
    <div class="content">
        @if(!Auth())
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="/register">Registration</a>
            <a class="link" href="/login">Login</a>
        </div>
        @endif

        <!-- ユーザー -->
        @if( Auth::check() && Auth::user()->role == 'ユーザー')
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="/user/my-page">Mypage</a>
            <form class="form" action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="submit" value="Logout">
            </form>
        </div>
        @endif

        <!-- 店舗代表者 -->
        @if( Auth::check() && Auth::user()->role == '店舗代表者')
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="{{ route('manager.index') }}">Reservations</a>
            <a class="link" href="{{ route('manager.detail') }}">Shop Details</a>
            <form class="form" action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="submit" value="Logout">
            </form>
        </div>
        @endif

        <!-- 管理者 -->
        @if ( Auth::check() && Auth::user()->role == '管理者')
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="{{ route('admin.index') }}">User List</a>
            <form class="form" action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="submit" value="Logout">
            </form>
        </div>
        @endif
    </div>
</body>

</html>