<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateRestaurantRequest extends FormRequest
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
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:1024',
            'name' => 'required|string|max:255',
            'genre' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'description' => 'required|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'name.required' => '店名は必須です。',
            'name.string' => '店名は文字列で入力してください。',
            'genre.required' => 'ジャンルは必須です。',
            'address.required' => '住所は必須です。',
            'name.string' => '住所は文字列で入力してください。',
            'description.required' => '説明は必須です。',
            'description.required' => '説明は文字列で入力してください。',
            'image.image' => '画像は画像ファイルでなければなりません。',
            'image.mimes' => '画像はjpeg、png、jpg、gifの形式です。',
            'image.max' => '画像は1MB以内でなければなりません。',
        ];
    }
}
