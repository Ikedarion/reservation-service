@extends('layouts.admin')

@section('css')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
<link rel="stylesheet" href="{{ asset('css/admin/user.css') }}">
@endsection

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
<div class="user__content">
    <div class="user-form__header">
        <div class="user__heading">ユーザー一覧</div>
    </div>
    <div class="user__inner">
        <div class="tab-titles">
            <div class="tab-items">
                <div class="tabs">
                    <div class="tab-link" data-tab="users">ユーザー</div>
                    <div class="tab-link" data-tab="managers">店舗代表者</div>
                    <div class="tab-link" data-tab="admins">管理者</div>
                </div>
                <div class="admin-form">
                    <a class="admin-form__submit" href="#modal_create"><i class="fas fa-user-plus"></i>&nbsp;新規作成</a>
                </div>
            </div>
            <form class="user-form" action="{{ route('admin.search') }}" method="get">
                <input type="hidden" name="tab" value="{{ request()->get('tab','users')}}">
                <input class="user-form__input" type="text" name="keyword" value="{{ request()->get('keyword') }}" placeholder="Search Users">
                <button class="user-form__search-submit" type="submit"><i class="fas fa-search"></i></button>
                <a class="user-form__reset-link" href="/admin/user">リセット</a>
            </form>

            <div class="modal {{ $errors->has('name') || $errors->has('email') || $errors->has('password') || $errors->has('role') ? 'open' : ' ' }}" id="modal_create">
                <div class="modal__inner">
                    <div class="modal__content">
                        <a href="#" onclick="closeModalAndReturn();" class="modal__close">×</a>
                        <form action="{{ route('admin.store') }}" method="POST" class="modal-form">
                            @csrf
                            <label for="name" class="modal__label">名前</label>
                            <input type="text" class="modal__input" name="name" value="{{ old('name') }}" id="name">

                            @error('name')
                            <div class="error-message">{{ $message }}</div>
                            @enderror

                            <label for="email" class="modal__label">メールアドレス</label>
                            <input type="email" class="modal__input" name="email" value="{{ old('email') }}" id="email">

                            @error('email')
                            <div class="error-message">{{ $message }}</div>
                            @enderror

                            <label for="password" class="modal__label">パスワード</label>
                            <input type="password" class="modal__input" name="password" value="{{ old('password') }}" id="password">

                            @error('password')
                            <div class="error-message">{{ $message }}</div>
                            @enderror

                            <label class="modal__label" for="manager">店舗代表者</label>
                            <input class="modal__radio" type="radio" name="role" value="店舗代表者" id="manager" {{ old('role') == '店舗代表者' ? 'checked' : '' }}>

                            <label class="modal__label" for="admin">管理者</label>
                            <input class="modal__radio" type="radio" name="role" value="管理者" id="admin" {{ old('role') == '管理者' ? 'checked' : '' }}>

                            @error('role')
                            <div class="error-message">{{ $message }}</div>
                            @enderror
                            <input class="modal__submit" type="submit" value="登録">
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="tab-content" id="users">
            <table class="user__table">
                <tr class="user__row">
                    <th class="user__header">ID</th>
                    <th class="user__header">名前</th>
                    <th class="user__header">メールアドレス</th>
                    <th class="user__header">パスワード</th>
                    <th class="user__header"></th>
                    <th class="user__header"></th>
                </tr>
                @foreach($users as $user)
                <tr class="user__row">
                    <td class="user__item">{{ $user->id }}</td>
                    <td class="user__item">{{ $user->name }}</td>
                    <td class="user__item">{{ $user->email }}</td>
                    <td class="user__item">* * * * *</td>
                    <td class="user__item">{{ $user->role }}</td>
                    <td class="user__item">
                        <div class="user__item-items">
                            <a class="modal__link" href="#modal_user{{$user->id}}"><i class="fas fa-edit"></i></a>
                            <form class="modal-form__delete" action="{{ route('admin.delete',$user->id) }}" method="post" onsubmit="return confirmDelete('このユーザーを削除してよろしいですか？')">
                                @csrf
                                @method('delete')
                                <button class="modal__delete-submit" type="submit"><i class="fas fa-trash-alt" style="color: #444;"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <x-user-tab-modal :user="$user" />
                @endforeach
            </table>
        </div>

        <div class="tab-content" id="managers">
            <table class="user__table">
                <tr class="user__row">
                    <th class="user__header">ID</th>
                    <th class="user__header">名前</th>
                    <th class="user__header">メールアドレス</th>
                    <th class="user__header">パスワード</th>
                    <th class="user__header">店舗</th>
                    <th class="user__header"></th>
                    <th class="user__header"></th>
                </tr>
                @foreach($managers as $manager)
                <tr class="user__row">
                    <td class="user__item">{{ $manager->id }}</td>
                    <td class="user__item">{{ $manager->name }}</td>
                    <td class="user__item">{{ $manager->email }}</td>
                    <td class="user__item">* * * * *</td>
                    <td class="user__item">
                        @if($manager->restaurant)
                        {{ $manager->restaurant->name }}
                        @else
                        店舗なし
                        @endif
                    </td>
                    <td class="user__item">{{ $manager->role }}</td>
                    <td class="user__item">
                        <div class="user__item-items">
                            <a class="modal__link" href="#modal_manager{{$manager->id}}"><i class="fas fa-edit"></i></a>
                            <form class="modal-form__delete" action="{{ route('admin.delete',$manager->id) }}" method="post" onsubmit="return confirmDelete('このユーザーを削除してよろしいですか？')">
                                @csrf
                                @method('delete')
                                <button class="modal__delete-submit" type="submit"><i class="fas fa-trash-alt" style="color: #444;"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <x-manager-tab-modal :manager="$manager" />
                @endforeach
            </table>
        </div>


        <div class="tab-content" id="admins">
            <table class="user__table">
                <tr class="user__row">
                    <th class="user__header">ID</th>
                    <th class="user__header">名前</th>
                    <th class="user__header">メールアドレス</th>
                    <th class="user__header">パスワード</th>
                    <th class="user__header"></th>
                    <th class="user__header"></th>
                </tr>
                @foreach($admins as $admin)
                <tr class="user__row">
                    <td class="user__item">{{ $admin->id }}</td>
                    <td class="user__item">{{ $admin->name }}</td>
                    <td class="user__item">{{ $admin->email }}</td>
                    <td class="user__item">* * * * *</td>
                    <td class="user__item">{{ $admin->role }}</td>
                    <td class="user__item">
                        <div class="user__item-items">
                            <a class="modal__link" href="#modal_admin{{$admin->id}}"><i class="fas fa-edit"></i></a>
                            <form class="modal-form__delete" action="{{ route('admin.delete',$admin->id) }}" method="post" onsubmit="return confirmDelete('このユーザーを削除してよろしいですか？')">
                                @csrf
                                @method('delete')
                                <button class="modal__delete-submit" type="submit"><i class="fas fa-trash-alt" style="color: #444;"></i></button>
                            </form>
                        </div>
                    </td>
                </tr>
                <x-admin-tab-modal :admin="$admin" />
                @endforeach
            </table>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const tabLinks = document.querySelectorAll('.tab-link');
        const tabContents = document.querySelectorAll('.tab-content');

        // URLのパラメータからタブの選択状態を取得
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'users';

        // タブの状態をURLに反映
        document.querySelector(`.tab-link[data-tab="${activeTab}"]`).classList.add('active');
        document.getElementById(activeTab).classList.add('active');

        tabLinks.forEach(link => {
            link.addEventListener('click', function(event) {
                // すべてのタブリンクとタブコンテンツからactiveクラスを削除
                tabLinks.forEach(link => link.classList.remove('active'));
                tabContents.forEach(content => content.classList.remove('active'));

                // クリックされたタブにactiveクラスを追加
                const targetTab = event.target.getAttribute('data-tab');
                event.target.classList.add('active');
                document.getElementById(targetTab).classList.add('active');

                window.history.replaceState({}, '', `?tab=${targetTab}`);
            });
        });

        if (document.querySelector('.modal.open')) {
            document.querySelector('.modal.open').classList.add('open');
        }
    });

    function closeModalAndReturn() {
        const urlParams = new URLSearchParams(window.location.search);
        const activeTab = urlParams.get('tab') || 'users';

        window.location.href = `?tab=${activeTab}`;
    }

    function confirmDelete(message) {
        return confirm(message);
    }
</script>
@endpush