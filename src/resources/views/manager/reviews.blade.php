@extends('layouts.app')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="{{ asset('css/manager/reviews.css') }}">
@endsection

@section('content')
<div class="reviews__content">
    <din class="reviews__inner">
        <div class="reviews_container">
            <table class="reviews__table">
                <tr class="table__row">
                    
                </tr>
            </table>
        </div>
    </din>
</div>
@endsection