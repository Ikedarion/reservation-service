<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9]).{8,}$/',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '※お名前は必須です。',
            'name.string' => '※お名前は文字で入力してください。',
            'name.max' => '※お名前を255文字以下で入力してください。',

            'email.required' => '※メールアドレスは必須です。',
            'email.string' => '※メールアドレスは文字で入力してください。',
            'email.email' => '※「ユーザー名@ドメイン」形式で入力してください。',
            'email.max' => '※メールアドレスを255文字以内で入力してください。',
            'email.unique' => '※このメールアドレスは既に登録されています',

            'password.required' => '※パスワードを入力してください。',
            'password.string' => '※パスワードは文字で入力してください。',
            'password.min' => '※パスワードは8文字以上で入力してください',
            'password.regex' => '※パスワードは8文字以上、英大文字、英小文字、数字が必須です。',
        ];
    }
}
