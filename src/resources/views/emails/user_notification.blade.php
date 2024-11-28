<!DOCTYPE html>
<html lang="ja">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $subject }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e9ecef;
        }

        .email-container {
            width: 93vw;
            max-width: 500px;
            margin: 40px auto;
            background-color: #ffffff;
            padding: 20px 3%;
            border-radius: 8px;
        }

        .email-content {
            font-size: 12px;
            color: #444444;
            padding-bottom: 10px;
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
            border-top: 1px solid #e0e0e0;
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
            <p class="email-content__header">{{ $user_name }}様</p>

            <p>{!! nl2br(e($content)) !!}</p>
        </div>
        <div class="footer">
            <p>このメールは自動送信されています。返信はできませんのでご了承ください。</p>
        </div>
    </div>
</body>

</html>