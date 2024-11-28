<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Document</title>
</head>

<style>
    .content {
        height: 100%;
        max-width: 100%;
        margin: 0;
        padding: 0 10px;
    }

    .item {
        width: 100%;
        margin-top: 180px;
        text-align: center;
    }

    .link {
        text-decoration: none;
        color: #1271d0;
        display: block;
        padding: 8px 12px;
        font-weight: bold;
        font-size: 20px;
        margin-bottom: 8px;
    }

    .form input {
        border: none;
        text-decoration: none;
        color: white;
        padding: 6px 14px;
        font-weight: bold;
        font-size: 18px;
        margin: 13px 0;
        background-color: #1271d0;
    }

    .form:hover {
        background-color: #f1f1f1;
    }

    .form input:hover {
        background-color: #00509e;
        color: white;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);
    }

    .link:hover {
        color: #00509e;
        text-shadow: 1.5px 2px 2px rgba(186, 189, 209, 0.6);
        background-color: #f1f1f1;
    }
</style>

<body>
    <div class="content">
        @guest
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="/register">Registration</a>
            <a class="link" href="/login">Login</a>
        </div>
        @endguest


        @auth
        @if(Auth::user()->role == 'ユーザー')
        <div class="item">
            <a class="link" href="/">Home</a>
            <a class="link" href="/user/my-page">Mypage</a>
            <form class="form" action="{{ route('logout') }}" method="POST">
                @csrf
                <input type="submit" value="Logout">
            </form>
        </div>
        @endif
        @endauth
    </div>
</body>

</html>