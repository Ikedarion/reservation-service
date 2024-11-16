<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class ReservationRequest extends FormRequest
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
            'date' => ['required','date',
                        function($attribute,$value,$fail) {
                            $valueDate = Carbon::parse($value);
                            $today = Carbon::today()->format('Y-m-d');
                            if ($value < $today) {
                                $fail('※ご予約は翌日以降から可能です。');
                            } elseif ($valueDate->isToday()) {
                                $fail('※当日のご予約はお受けできません。お手数ですが、お電話にてご確認ください。');
                            }
                        },
                    ],

            'time' => 'required|date_format:H:i',
            'number' =>'required|integer',
        ];
    }

    public function messages()
    {
        return [
            'date.required' => '※予約日を入力してください。',
            'date.date' => '※日付形式（YYYY-MM-DD）で入力してください。',
            'time.required' => '※予約時間を入力してください',
            'time.date_format' => '※HH:mm形式で入力してください。',

            'number.required' => '※予約人数を入力してください。',
            'number.integer' => '※予約人数を整数で入力してください。',
        ];
    }
}
