<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $data['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        .email-container {
            width: 95vw;
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px 3%;
            border-radius: 8px;
        }

        .email-content {
            font-size: 12px;
        }

        .email-content__header {
            padding: 15px 0 10px;
            line-height: 1.5;
        }

        .footer {
            text-align: center;
            font-size: 11px;
            color: #777;
            padding: 10px;
        }

        @media screen and (max-width: 600px) {
            .email-content {
                font-size: 11px;
            }

            .footer {
                font-size: 9px;
            }
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-content">
            <p class="email-content__header">
                {{ $reservation->user->name }} 様、ご予約ありがとうございます。
            </p>
            <p>本日はご予約日となります。ご確認ください。</p>
            <p><strong>予約詳細</strong></p>
            <p>店舗: {{ $reservation->restaurant->name }}</p>
            <p>予約日: {{ $reservation->date }}</p>
            <p>時間: {{ $reservation->time }}</p>
            <p>人数: {{ $reservation->number }}</p>
        </div>
        <div class="footer">
            <p>このメールは自動送信されています。返信はできませんのでご了承ください。</p>
        </div>
    </div>
</body>

</html>