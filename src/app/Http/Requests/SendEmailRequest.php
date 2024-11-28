<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SendEmailRequest extends FormRequest
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
            'user_ids' => 'required|array|min:1',
            'user_ids.*' => 'exists:users,id',
            'subject' => 'required|string|max:100',
            'message' => 'required|string|max:1000',
        ];
    }

    public function messages()
    {
        return [
            'user_ids.required' => 'ユーザーを選択してください。',
            'subject.required' => '件名は必須です。',
            'subject.string' => '件名は文字列で入力してください。',
            'subject.max' => '件名は100文字以下で入力してください。',
            'message.required' => '本文を入力してください。',
            'message.string' => '本文は文字列で入力してください。',
            'message.max' => '本文は1000文字以下で入力してください。',
        ];
    }
}
