<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ManagerReservationRequest extends FormRequest
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
        $id = $this->route('id');
        return [
            'date_' . $id => 'required|date',
            'time_' . $id => 'required|date_format:H:i',
            'number_' . $id => 'required|integer',
            'status_' . $id => 'required',
        ];
    }

    public function messages()
    {
        $id = $this->route('id');
        return [
            'date_' . $id . '.required' => '※予約日を入力してください。',
            'date_' . $id . '.date' => '※日付形式（YYYY-MM-DD）で入力してください。',
            'time_' . $id . '.required' => '※予約時間を入力してください',
            'time_' . $id . '.date_format' => '※HH:mm形式で入力してください。',
            'number_' . $id . '.required' => '※予約人数を入力してください。',
            'number_' . $id . '.integer' => '※予約人数を整数で入力してください。',
            'status_' . $id . '.required' => 'ステータスは必須です。',
        ];
    }
}
