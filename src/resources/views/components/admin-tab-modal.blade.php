<div class="modal {{ $errors->has('name_' . $admin->id) || $errors->has('email_' . $admin->id) || $errors->has('role_' . $admin->id) ? 'open' : '' }}" id="modal_admin{{$admin->id}}">
    <div class="modal__inner">
        <div class="modal__content">
            <a href="#" onclick="closeModalAndReturn();" class="modal__close">×</a>
            <form action="{{ route('admin.update',$admin->id) }}" method="post" class="modal-form">
                @csrf
                @method('patch')
                <label for="name_{{$admin->id}}" class="modal__label">名前</label>
                <input type="text" class="modal__input" name="name_{{$admin->id}}" value="{{ old('name_' . $admin->id, $admin->name) }}" id="name_{{$admin->id}}">

                @error('name_' . $admin->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="email_{{$admin->id}}" class="modal__label">メールアドレス</label>
                <input type="text" class="modal__input" name="email_{{$admin->id}}" value="{{ old('email_' . $admin->id,$admin->email) }}" id="email_{{$admin->id}}">

                @error('email_' . $admin->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label class="modal__label" for="role_user_{{$admin->id}}">ユーザー</label>
                <input class="modal__radio" type="radio" id="role_user_{{$admin->id}}" name="role_{{$admin->id}}" value="ユーザー"
                    {{ old('role_' . $admin->id, $admin->role) == 'ユーザー' ? 'checked' : '' }}>

                <label class="modal__label" for="role_manager_{{$admin->id}}">店舗代表者</label>
                <input class="modal__radio" type="radio" id="role_manager_{{$admin->id}}" name="role_{{$admin->id}}" value="店舗代表者"
                    {{ old('role_' . $admin->id, $admin->role) == '店舗代表者' ? 'checked' : '' }}>

                <label class="modal__label" for="role_admin_{{$admin->id}}">管理者</label>
                <input class="modal__radio" type="radio" id="role_admin_{{$admin->id}}" name="role_{{$admin->id}}" value="管理者"
                    {{ old('role_' . $admin->id, $admin->role) == '管理者' ? 'checked' : '' }}>

                @error('role_' . $admin->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <input class="modal__submit" type="submit" value="登録">
            </form>
        </div>
    </div>
</div>