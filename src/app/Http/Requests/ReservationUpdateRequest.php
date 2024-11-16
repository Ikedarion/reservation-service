<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;

class ReservationUpdateRequest extends FormRequest
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
            'date_' . $id => ['required','date',
                    function($attribute,$value,$fail) {
                        $valueDate = Carbon::parse($value);
                        $today = Carbon::today()->format('Y-m-d');
                        if ($value < $today) {
                            $fail('※ご予約は翌日以降から可能です。');
                        } elseif ($valueDate->isToday()) {
                            $fail('※当日のご予約はお受けできません。お手数ですが、お電話にてご確認ください。');
                        }
                    }
                ],

            'time_' . $id => 'required|date_format:H:i',
            'number_' . $id =>'required|integer',
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
        ];
    }
}
