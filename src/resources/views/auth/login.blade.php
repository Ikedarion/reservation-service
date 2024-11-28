@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/auth/login.css') }}">
@endsection

@section('link_url','/menu/user')

@section('content')
@if(session('success'))
<div class="message message--success">
    {{ session('success')}}
</div>
@endif
@if(session('error'))
<div class="message message--error">
    {{ session('error')}}
</div>
@endif
<div class="login-form">
    <h5 class="form__heading">Login</h5>
    <div class="form__inner">
        <form action="/login" class="form" method="post">
            @csrf
            <div class="form__group">
                <label class="form__label" for="email">
                    <i class="fas fa-envelope"></i>
                </label>
                <input type="email" class="form__input" name="email" placeholder="Email" id="email">
            </div>
            <p class="error">
                @error('email')
                {{ $message }}
                @enderror
            </p>
            <div class="form__group">
                <label for="password" class="form__label">
                    <i class="fa-solid fa-unlock-keyhole"></i>
                </label>
                <input type="password" class="form__input" name="password" placeholder="Password" id="password">
            </div>
            <p class="error">
                @error('password')
                {{ $message }}
                @enderror
            </p>
            <input class="form__button" type="submit" value="ログイン"></input>
        </form>
    </div>
</div>
@endsection
