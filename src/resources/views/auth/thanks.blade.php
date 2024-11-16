@extends('layouts.app')

@section('link_url','/menu/public')

<style>
    .h1 {
        margin: 0px 0px 0px 10px;
    }

    .content {
        max-width: 1400px;
        height: 100%;
        text-align: center;
    }

    .container {
        width: 420px;
        height: 280px;
        background-color: white;
        margin: 0 auto;
        margin-top: 80px;
        box-shadow: 3px 3px 6px #aaaaaa;
        border-radius: 15px;
    }

    .heading {
        padding-top: 94px;
        font-size: 20px;
        letter-spacing: 3px;
        margin-bottom: 45px;
        color: #393939;
    }

    .login_link {
        text-decoration: none;
        background-color: rgb(28, 93, 255);
        color: white;
        padding: 8px 16px;
        border-radius: 5px;
        font-size: 14px;
        margin-right: 10px;
        transition: transform 0.2s;
        cursor: pointer;
    }

    .login_link:hover {
        transform: scale(0.9);
    }

    @media(max-width: 480px) {
        .container {
            width: 300px;
            height: 200px;
            margin-top: 80px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            border-radius: 15px;
        }

        .heading {
            padding-top: 65px;
            font-size: 15px;
            margin-bottom: 30px;
        }

        .login_link {
            padding: 6px 14px;
            border-radius: 5px;
            font-size: 13px;
        }

        .login_link:active {
            transform: scale(0.85);
        }

    }
</style>


@section('content')
<div class="content">
    <div class="container">
        <div class="heading">
            会員登録ありがとうございます
        </div>
        <a class="login_link" href="/login">ログイン</a>
    </div>
</div>

@endsection