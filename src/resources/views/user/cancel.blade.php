@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/user/cancel.css') }}">
@endsection

@section('content')
<div class="cancel-container">
    <h3 class="cancel-h3">予約がキャンセルされました</h3>
    <p class="cancel-p">決済がキャンセルされたため、予約は確定しませんでした。</p>

    <a href="{{ route('detail',$restaurantId) }}" class="btn btn-primary">予約を再試行</a>
    <a href="/" class="btn btn-home">ホーム</a>
</div>
@endsection