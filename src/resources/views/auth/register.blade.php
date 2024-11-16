@extends('layouts.app')

@section('link_url','/menu/public')

@section('css')
<link rel="stylesheet" href="{{ asset('css/auth/register.css') }}">
@endsection

@section('content')
<div class="register-form">
    <h5 class="form__heading">Registration</h5>
    <div class="form__inner">
        <form action="/register" class="form" method="post">
            @csrf
            <div class="form__group">
                <label class="form__label" for="name">
                    <i class="fas fa-user"></i>
                </label>
                <input class="form__input" name="name" placeholder="Username" type="text" id="name">
            </div>
            <p class="error">
                @error('name')
                {{ $message }}
                @enderror
            </p>
            <div class="form__group">
                <label class="form__label" for="email">
                    <i class="fas fa-envelope"></i>
                </label>
                <input class="form__input" name="email" placeholder="Email" type="email" id="email">
            </div>
            <p class="error">
                @error('email')
                {{ $message }}
                @enderror
            </p>
            <div class="form__group">
                <label class="form__label" for="password">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </label>
                <input class="form__input" name="password" placeholder="Password" type="password" id="password">
            </div>
            <p class="error">
                @error('password')
                {{ $message }}
                @enderror
            </p>
            <input type="submit" class="form__button" value="登録">
        </form>
    </div>
</div>

@endsection