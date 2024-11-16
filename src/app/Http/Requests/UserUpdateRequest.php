<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $Id = $this->route('id');
        return [
            'name_' . $Id => 'required|string|max:255',
            'email_' . $Id => 'required|string|email|max:255|unique:users,email,' . $Id,
            'role_' . $Id => 'required|in:ユーザー,店舗代表者,管理者'
        ];
    }

    public function messages()
    {
        $Id = $this->route('id');
        return [
            'name_' . $Id . '.required' => '※お名前は必須です。',
            'name_' . $Id . '.string' => '※お名前は文字で入力してください。',
            'name_' . $Id . '.max' => '※お名前を255文字以下で入力してください。',

            'email_' . $Id . '.required' => '※メールアドレスは必須です。',
            'email_' . $Id . '.string' => '※メールアドレスは文字で入力してください。',
            'email_' . $Id . '.email' => '※「ユーザー名@ドメイン」形式で入力してください。',
            'email_' . $Id . '.max' => '※メールアドレスを255文字以内で入力してください。',
            'email_' . $Id . '.unique' => '※このメールアドレスは既に登録されています',
            'role_' . $Id . '.required' => '※役割を選択してください。',
        ];
    }
}
