<div class="modal {{ $errors->has('name_' . $manager->id) || $errors->has('email_' . $manager->id) || $errors->has('role_' . $manager->id) ? 'open' : '' }}" id="modal_manager{{$manager->id}}">
    <div class="modal__inner">
        <div class="modal__content">
            <a href="#" onclick="closeModalAndReturn();" class="modal__close">×</a>
            <form action="{{ route('admin.update',$manager->id ) }}" method="post" class="modal-form">
                @csrf
                @method('patch')
                <label for="name_{{$manager->id}}" class="modal__label">名前</label>
                <input type="text" class="modal__input" name="name_{{$manager->id}}" value="{{ old('name_' . $manager->id, $manager->name) }}" id="name_{{$manager->id}}">

                @error('name_' . $manager->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label for="email_{{ $manager->id }}" class="modal__label">メールアドレス</label>
                <input type="text" class="modal__input" name="email_{{ $manager->id }}" value="{{ old('email_' . $manager->id, $manager->email) }}" id="email_{{ $manager->id }}">

                @error('email_' . $manager->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <label class="modal__label" for="role_user_{{ $manager->id }}">ユーザー</label>
                <input class="modal__radio" type="radio" id="role_user_{{ $manager->id }}" name="role_{{ $manager->id }}" value="ユーザー" {{ old('role_' . $manager->id, $manager->role) == 'ユーザー' ? 'checked' : '' }}>

                <label class="modal__label" for="role_manager_{{ $manager->id }}">店舗代表者</label>
                <input class="modal__radio" type="radio" id="role_manager_{{ $manager->id }}" name="role_{{ $manager->id }}" value="店舗代表者" {{ old('role_' . $manager->id, $manager->role) == '店舗代表者' ? 'checked' : '' }}>

                <label class="modal__label" for="role_admin_{{ $manager->id }}">管理者</label>
                <input class="modal__radio" type="radio" id="role_admin_{{ $manager->id }}" name="role_{{ $manager->id }}" value="管理者" {{ old('role_' . $manager->id, $manager->role) == '管理者' ? 'checked' : '' }}>

                @error('role_' . $manager->id)
                <div class="error-message">{{ $message }}</div>
                @enderror

                <input class="modal__submit" type="submit" value="登録">
            </form>
        </div>
    </div>
</div>