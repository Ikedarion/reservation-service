@extends('layouts.admin')

@section('link_url','/menu/user')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/manager/reservation.css') }}">
@endsection


@section('content')
<div class="reservation__content">
    <div class="reservation__inner">
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
        <div class="reservation__heading">
            予約一覧
        </div>
        <div class="form__group">
            <form class="res-form" action="{{ route('manager.search') }}" method="get">
                <div class="res-form__row">
                    <div class="res-form__cell">
                        <label class="res-form__label" for="start_date">開始日</label>
                        <input class="res-form__date" type="date" name="start_date" id="start_date" value="<?php echo date('Y-m-d'); ?>" style="color: #888; font-size: 12px;">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="end_date">終了日</label>
                        <input class="res-form__date" type="date" name="end_date" id="end_date" value="" style="color: #888; font-size: 12px;">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="start_time">開始時間</label>
                        <input class="res-form__date" type="time" name="start_time" id="start_time" style="color: #888; font-size: 12px;">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="end_time">終了時間</label>
                        <input class="res-form__date" type="time" name="end_time" id="end_time" style="color: #888; font-size: 12px;">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__search">戻る</label>
                        <input class="res-form__reset" type="button" value="リセット" onclick="location.href='/manager'">
                    </div>

                </div>

                <div class="res-form__row">
                    <div class="res-form__cell">
                        <label class="res-form__label" for="res_id">予約ID</label>
                        <input class="res-form__number" type="number" name="res_id" id="res_id">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="number">人数</label>
                        <input class="res-form__number" type="number" name="number" id="number">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="status">ステータス</label>
                        <select class="res-form__status" name="status" id="status" style="color: #888; font-size: 12px;">
                            <option class="res-form__status-option" value="" hidden>選択</option>
                            <option class="res-form__status-option" value="予約確定">予約確定</option>
                            <option class="res-form__status-option" value="キャンセル">キャンセル</option>
                            <option class="res-form__status-option" value="来店済み">来店済み</option>
                            <option class="res-form__status-option" value="完了">完了</option>
                        </select>
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__label" for="name">キーワード</label>
                        <input class="res-form__input" type="text" name="keyword" id="name" placeholder="Search" style="color: #fff; font-size: 12px;">
                    </div>

                    <div class="res-form__cell">
                        <label class="res-form__search">検索</label>
                        <input class="res-form__submit" type="submit" value="絞り込み">
                    </div>
                </div>
            </form>
        </div>
        <div class="pagination_">
            <div>{{ $reservations->links('vendor.pagination.bootstrap-4') }}</div>
        </div>

        @if($reservations->isEmpty())
        <div class="alert" style="color: #888; margin-top: 30px;">該当するデータがありません</div>
        @else
        <table class="res-table">
            <tr class="table__row">
                <th class="table__header">予約ID</th>
                <th class="table__header">日付</th>
                <th class="table__header">時間</th>
                <th class="table__header">名前</th>
                <th class="table__header">人数</th>
                <th class="table__header">ステータス</th>
                <th class="table__header"></th>
            </tr>
            @foreach($reservations as $reservation)
            <tr class="table__row">
                <td class="table__item">{{ $reservation->id }}</td>
                <td class="table__item">{{ \Carbon\Carbon::parse($reservation->date)->format('Y-m-d') }}</td>
                <td class="table__item">{{ \Carbon\Carbon::parse($reservation->date)->format('H:i') }}</td>
                <td class="table__item">{{ $reservation->user->name }}</td>
                <td class="table__item">{{ $reservation->number }}人</td>
                <td class="table__item">
                    <span class="table__item-status
                    {{ $reservation->status === '予約確定' ? 'reserved' : '' }}
                    {{ $reservation->status === 'キャンセル' ? 'cancelled' : '' }}
                    {{ in_array($reservation->status, ['来店済み', '完了']) ? 'visited' : '' }}"">{{ $reservation->status }}</span>
                </td>
                <td class=" table__item">
                        <div class="table__item-items">
                            <button class="openSidebarButton" data-reservation-id="{{ $reservation->id }}">
                                <i class="fas fa-pencil-alt" style="color: #555;"></i>
                            </button>
                            <form class="res-table__delete" action="{{ route('manager.delete', $reservation->id ) }}" method="post" onsubmit="return confirmDelete('この予約データを削除してよろしいですか？')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="res-table__delete-submit"><i class="fas fa-trash-alt" style="color: #555;"></i></button>
                            </form>
                        </div>
                </td>
            </tr>
            <div class="sidebar {{ $errors->hasAny(['date_' . $reservation->id, 'time_' . $reservation->id, 'number_' . $reservation->id, 'status_' . $reservation->id]) ? 'open' : '' }}" id="sidebar{{ $reservation->id }}">
                <div class="sidebar-content">
                    <h4 class="sidebar-h4">編集</h4>
                    <form class="sidebar-form" action="{{ route('manager.update',$reservation->id)}}" method="post">
                        @csrf
                        @method('PATCH')
                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_id{{ $reservation->id }}">予約ID</label>
                            <input class="sidebar__input-disabled" type="input" id="sidebar_id{{ $reservation->id }}" value="{{$reservation->id}}" disabled>
                        </div>

                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_name{{ $reservation->id }}">お名前</label>
                            <input class="sidebar__input-disabled" type="input" id="sidebar_name{{ $reservation->id }}" value="{{$reservation->user->name}}" disabled>
                        </div>

                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_date{{ $reservation->id }}">日付</label>
                            <input class="sidebar__input" type="date" id="sidebar_date{{ $reservation->id }}" name="date_{{ $reservation->id }}" value="{{ old('date_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('Y-m-d')) }}">
                        </div>
                        @error('date_' . $reservation->id)
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_time{{ $reservation->id }}">時間</label>
                            <input class="sidebar__input" type="time" id="sidebar_time{{ $reservation->id }}" name="time_{{ $reservation->id }}" value="{{ old('time_' . $reservation->id, \Carbon\Carbon::parse($reservation->date)->format('H:i')) }}">
                        </div>
                        @error('time_' . $reservation->id)
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_number{{ $reservation->id }}">人数</label>
                            <input class="sidebar__input" type="number" id="sidebar_number{{ $reservation->id }}" name="number_{{ $reservation->id }}" value="{{ old('number_' . $reservation->id, $reservation->number) }}">
                        </div>
                        @error('number_' . $reservation->id)
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror

                        <div class="sidebar__group">
                            <label class="sidebar__label" for="sidebar_status{{ $reservation->id }}">ステータス</label>
                            <select class="sidebar__select" name="status_{{ $reservation->id }}" id="sidebar_status{{ $reservation->id }}">
                                <option class="sidebar__status-option" value="予約確定" {{ old('status' . $reservation->id, $reservation->status) == '予約確定' ? 'selected' : '' }}>予約確定</option>
                                <option class="sidebar__status-option" value="キャンセル" {{ old('status_' . $reservation->id, $reservation->status) == 'キャンセル' ? 'selected' : '' }}>キャンセル</option>
                                <option class="sidebar__status-option" value="来店済み" {{ old('status' . $reservation->id, $reservation->status) == '来店済み' ? 'selected' : ''}}>来店済み</option>
                                <option class="sidebar__status-option" value="完了" {{ old('status' . $reservation->id, $reservation->status) == '完了' ? 'selected' : ''}}>完了</option>
                            </select>
                        </div>
                        @error('status_' . $reservation->id)
                        <div class="error">
                            {{ $message }}
                        </div>
                        @enderror
                        <input type="submit" class="sidebar-submit" value="保存">
                    </form>
                </div>
            </div>
            @endforeach
        </table>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const openSidebarButtons = document.querySelectorAll('.openSidebarButton');
        openSidebarButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.getAttribute('data-reservation-id');
                const sidebar = document.getElementById('sidebar' + reservationId);
                if (sidebar) {
                    sidebar.classList.add('open');
                }
            });
        });

        document.addEventListener('click', function(event) {
            const sidebar = document.querySelector('.sidebar.open');
            if (sidebar && !sidebar.contains(event.target) && !event.target.closest('.openSidebarButton')) {
                sidebar.classList.remove('open');
            }
        });

    });

    function confirmDelete(message) {
        return confirm(message);
    }
</script>
@endpush