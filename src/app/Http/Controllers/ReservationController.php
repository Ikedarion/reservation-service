<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Models\Reservation;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Stripe\Stripe;
use Illuminate\Support\Facades\Validator;
use App\jobs\SendReservationConfirmation;



class ReservationController extends Controller
{
    public function createCheckoutSession(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => [
                'required',
                'date',
                function ($attribute, $value, $fail) {
                    $valueDate = Carbon::parse($value);
                    $today = Carbon::today()->format('Y-m-d');
                    if ($value < $today) {
                        $fail('※ご予約は翌日以降から可能です。');
                    } elseif ($valueDate->isToday()) {
                        $fail('※当日のご予約はお受けできません。お電話にてご確認ください。');
                    }
                },
            ],

            'time' => 'required|date_format:H:i',
            'number' => 'required|integer',
        ], [
            'date.required' => '※予約日を入力してください。',
            'date.date' => '※日付形式（YYYY-MM-DD）で入力してください。',
            'time.required' => '※予約時間を入力してください',
            'time.date_format' => '※HH:mm形式で入力してください。',

            'number.required' => '※予約人数を入力してください。',
            'number.integer' => '※予約人数を整数で入力してください。',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422); // バリデーションエラーの返却
        }


        try {
            Stripe::setApiKey(env('STRIPE_SECRET_KEY'));

            $restaurantId = $request->input('restaurant_id');
            $date = Carbon::parse($request->date)->format('Y-m-d');
            $time = Carbon::parse($request->time)->format('H:i:s');
            $dateTime = $date . ' ' . $time;
            $number = $request->input('number');

            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'restaurant_id' => $restaurantId,
                'date' => $dateTime,
                'number' => $number,
                'status' => '未払い',
            ]);

            $userEmail = auth()->user()->email;

            // Stripe Checkout セッションの作成
            $session = \Stripe\Checkout\Session::create([
                'payment_method_types' => ['card'],
                'line_items' => [[
                    'price_data' => [
                        'currency' => 'jpy',
                        'product_data' => [
                            'name' => "Reservation at Restaurant {$restaurantId}",
                        ],
                        'unit_amount' => 1000,  // 価格を適切な金額に設定
                    ],
                    'quantity' => 1,
                ]],
                'mode' => 'payment',
                'customer_email' => $userEmail,
                'success_url' => route('payment.success', ['reservationId' => $reservation->id]),
                'cancel_url' => route('payment.cancel', ['reservationId' => $reservation->id]),
            ]);

            return response()->json(['id' => $session->id]);
        } catch (\Exception $e) {
            Log::error('Stripe Checkout セッション作成エラー: ' . $e->getMessage());
            return response()->json(['予期しないエラーが発生しました。後ほど再試行してください。'], 500);
        }
    }

    public function success($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = '予約確定';
        $reservation->save();

        return view('user.done', compact('reservation'));
    }

    public function cancel($reservationId)
    {
        $reservation = Reservation::findOrFail($reservationId);
        $reservation->status = 'キャンセル';
        $reservation->save();
        $restaurantId = $reservation->restaurant->id;

        return view('user.cancel', compact('reservation','restaurantId'));
    }




    public function generateQrCode($id)
    {
        $reservation = Reservation::with('user')->findOrFail($id);
        $verificationUrl = route('reservation.verify', [
            'id' => $reservation->id,
        ]);
        $qrCode = QrCode::format('svg')->size(300)->generate($verificationUrl);

        return view('user/qr', compact('reservation','qrCode'));
    }

    public function showQrScanner()
    {
        return view('manager/qr-verify');
    }

    public function verifyReservation(Request $request)
    {
        $qrData = $request->input('qrData');
        $params = [];
        $parsedUrl = parse_url($qrData);
        if ($parsedUrl && isset($parsedUrl['query'])) {
            parse_str($parsedUrl['query'], $params);
        }
        $reservationId = $params['id'] ?? null;

        $errorMessages = [];
        if (!$reservationId || !($reservation = Reservation::find($reservationId))) {
            $errorMessages[] = '予約IDが無効または存在しません。';
        }

        if ($reservation && $reservation->date < today()) {
            $errorMessages[] = '予約が期限切れです。';
        }

        if ($reservation && $reservation->status === '来店済み') {
            $errorMessages[] = '予約はすでに確認済みです。';
        }

        if (!empty($errorMessages)) {
            Log::warning('Reservation verification failed', ['errors' => $errorMessages, 'qrData' => $qrData]);
            return response()->json(['success' => false, 'message' => '予約の確認ができませんでした。']);
        }

        $reservation->update(['status' => '来店済み']);

        return response()->json([
            'success' => true,
            'message' => '予約が確認されました。',
            'data' => [
                'id' => $reservation->id,
                'restaurant_name' => $reservation->restaurant->name,
                'date' => $reservation->date,
                'number' => $reservation->number,
            ],
        ]);
    }
}



