<div class="modal {{ $errors->has('name_' . $user->id) || $errors->has('email_' . $user->id) || $errors->has('role_' . $user->id) ? 'open' : '' }}" id="modal_user{{$user->id}}">
    <div class="modal__inner">
        <div class="modal__content">
            <a href="#" onclick="closeModalAndReturn();" class="modal__close">×</a>
            <form action="{{ route('admin.update',$user->id ) }}" method="post" class="modal-form">
                @csrf
                @method('patch')
                <label for="name_{{$user->id}}" class="modal__label">名前</label>
                <input type="text" class="modal__input" name="name_{{$user->id}}" value="{{ old('name_' . $user->id, $user->name) }}" id="name_{{$user->id}}">

                @error('name_' . $user->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="email_{{ $user->id }}" class="modal__label">メールアドレス</label>
                <input type="text" class="modal__input" name="email_{{ $user->id }}" value="{{ old('email_' . $user->id, $user->email) }}" id="email_{{ $user->id }}">

                @error('email_' . $user->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label class="modal__label" for="role_user_{{ $user->id }}">ユーザー</label>
                <input class="modal__radio" type="radio" id="role_user_{{ $user->id }}" name="role_{{ $user->id }}" value="ユーザー" {{ old('role_' . $user->id, $user->role) == 'ユーザー' ? 'checked' : '' }}>

                <label class="modal__label" for="role_manager_{{ $user->id }}">店舗代表者</label>
                <input class="modal__radio" type="radio" id="role_manager_{{ $user->id }}" name="role_{{ $user->id }}" value="店舗代表者" {{ old('role_' . $user->id, $user->role) == '店舗代表者' ? 'checked' : '' }}>

                <label class="modal__label" for="role_admin_{{ $user->id }}">管理者</label>
                <input class="modal__radio" type="radio" id="role_admin_{{ $user->id }}" name="role_{{ $user->id }}" value="管理者" {{ old('role_' . $user->id, $user->role) == '管理者' ? 'checked' : '' }}>

                @error('role_' . $user->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <input class="modal__submit" type="submit" value="登録">
            </form>
        </div>
    </div>
</div>