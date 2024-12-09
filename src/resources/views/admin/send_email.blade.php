@extends('layouts.admin')

@section('link_url','/menu/user')
@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/send_email.css') }}">
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css" rel="stylesheet" />
@endsection

@section('content')
<div class="mail__content">
    @if(session('success'))
    <div class="message message--success">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="message message--error">
        {{ session('error') }}
    </div>
    @endif

    <h3 class="mail__header">メッセージ作成</h3>
    <div class="mail__inner">
        <div class="mail-form">
            <div class="mail-form__group">
                <label for="user_ids" class="form__label">宛先 :</label>
                <span class="error-message">
                    @error('user_ids')
                    {{ $message }}
                    @enderror
                </span>
                <button type="button" id="toggle-checkboxes" class="checkboxes__button">ユーザーを選択</button>
                <div class="checkbox-group {{ $errors->any() ? 'open' : '' }}" id="checkbox-group">
                    <div class="group__items-user">
                        <button type="button" class="form__button">一般ユーザーを全て選択</button>
                        <button type="button" class="form__button">店舗代表者を全て選択</button>
                        <button type="button" class="form__button">管理者を全て選択</button>
                    </div>
                    <div id="selected-users-list"></div>

                    <form class="form" action="{{ route('admin.sendMail') }}" method="post">
                        @csrf
                        <div class="group">
                            <div class="group__header">一般ユーザー</div>
                            <div class="checkbox-list">
                                @foreach($users as $user)
                                <div class="checkbox">
                                    <input type="checkbox" name="user_ids[]" id="user_{{ $user->id }}" value="{{ $user->id }}"
                                        @if(in_array($user->id, old('user_ids', []))) checked @endif>
                                    <label for="user_{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="group">
                            <div class="group__header">店舗代表者</div>
                            <div class="checkbox-list">
                                @foreach($managers as $user)
                                <div class="checkbox">
                                    <input type="checkbox" name="user_ids[]" id="manager_{{ $user->id }}" value="{{ $user->id }}"
                                        @if(in_array($user->id, old('user_ids', []))) checked @endif>
                                    <label for="manager_{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                        <div class="group">
                            <div class="group__header">管理者</div>
                            <div class="checkbox-list">
                                @foreach($admins as $user)
                                <div class="checkbox">
                                    <input type="checkbox" name="user_ids[]" id="admin_{{ $user->id }}" value="{{ $user->id }}"
                                        @if(in_array($user->id, old('user_ids', []))) checked @endif>
                                    <label for="admin_{{ $user->id }}">{{ $user->name }} - {{ $user->email }}</label>
                                </div>
                                @endforeach
                            </div>
                        </div>
                </div>

                <div class="form__group">
                    <label for="subject" class="form__label">件名 :</label>
                    <span class="error-message">
                        @error('subject')
                        {{ $message }}
                        @enderror
                    </span>
                    <input type="text" name="subject" class="form__input" value="{{ old('subject') }}">
                </div>

                <div class="form__group">
                    <label for="message" class="form__label">本文 :</label>
                    <span class="error-message">
                        @error('message')
                        {{ $message }}
                        @enderror
                    </span>
                    <textarea name="message" rows="5" class="form__textarea" id="message">{{ old('message') }}</textarea>
                </div>

                <button class="form__submit" type="submit">
                    <i class="fas fa-paper-plane"></i> 送信する
                </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const selectAllButtons = document.querySelectorAll('.form__button');

        selectAllButtons.forEach(button => {
            button.addEventListener('click', function() {
                let checkboxList;
                if (this.textContent.includes('店舗代表者')) {
                    checkboxList = document.querySelectorAll('.checkbox-list input[id^="manager_"]');
                } else if (this.textContent.includes('一般ユーザー')) {
                    checkboxList = document.querySelectorAll('.checkbox-list input[id^="user_"]');
                } else if (this.textContent.includes(('管理者'))) {
                    checkboxList = document.querySelectorAll('.checkbox-list input[id^="admin_"]');
                }
                const isChecked = this.textContent.includes('選択');

                checkboxList.forEach(checkbox => {
                    checkbox.checked = isChecked;
                });

                this.textContent = isChecked ? this.textContent.replace('選択', '解除') : this.textContent.replace('解除', '選択');
            });
        });

        const toggleButton = document.getElementById('toggle-checkboxes');
        const checkboxGroup = document.getElementById('checkbox-group');

        toggleButton.addEventListener('click', function() {
            if (checkboxGroup.style.display === 'none' || checkboxGroup.style.display === '') {
                checkboxGroup.style.display = 'block';
                toggleButton.textContent = '閉じる';
            } else {
                checkboxGroup.style.display = 'none';
                toggleButton.textContent = 'ユーザーを選択';
            }
        });

    });
</script>
@endpush