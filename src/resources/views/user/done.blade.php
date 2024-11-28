@extends('layouts.app')

@section('link_url','/menu/user')

<style>
    .h1 {
        margin: 10px 0px 0px 10px;
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
        box-shadow: 3px 3px 5px #cccccc;
        border-radius: 5px;
    }

    .heading {
        padding-top: 90px;
        font-size: 20px;
        margin-bottom: 45px;
        letter-spacing: 1px;
        color: #333;
    }

    .home__link {
        text-decoration: none;
        background-color: rgb(28, 93, 255);
        color: white;
        padding: 6px 16px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        margin-right: 10px;
        transition: background-color 0.2s;
        cursor: pointer;
    }

    .myPage__link {
        text-decoration: none;
        color: rgb(28, 93, 255);
        border: 1.3px solid rgb(28, 93, 255);
        padding: 6px 16px;
        border-radius: 5px;
        font-size: 13px;
        font-weight: bold;
        display: inline-block;
        margin-right: 10px;
        transition: background-color 0.2s;
        cursor: pointer;
    }

    .home__link:hover {
        background-color: rgb(56, 112, 255);
    }

    .myPage__link:hover {
        background-color: #fafafa;
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
            font-size: 17px;
            margin-bottom: 30px;
        }

        .home__link {
            padding: 4px 13px;
            border-radius: 5px;
            font-size: 13px;
        }

        .home__link:active {
            transform: scale(0.85);
        }
    }
</style>


@section('content')
<div class="content">
    <div class="container">
        <div class="heading">
            決済が完了しました<br>
            ご予約ありがとうございます
        </div>
        <div class="link">
            <a class="home__link" href="/">ホーム</a>
            <a class="myPage__link" href="/user/my-page">マイページ</a>
        </div>
    </div>
</div>

@endsection