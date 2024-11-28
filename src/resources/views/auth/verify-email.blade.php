<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <style>
        .container {
            padding: 150px 20px;
            text-align: center;
        }

        h1 {
            color: #3c3c3c;
        }

        .alert-success {
            margin-bottom: 10px;
            color: #3C3C3C;
        }

        p {
            color: #3c3c3c;
        }

        .btn-primary {
            background-color: #3A6EA5;
            color: white;
            padding: 10px 13px;
            border-radius: 3px;
            border: none;
            margin-top: 70px;
        }
    </style>

    <div class="container">
        <h1>メール認証が必要です。</h1>
        <p>認証メールを確認し、メール内のリンクをクリックしてください。</p>

        @if(session('status') == 'verification-link-sent')
        <div class="alert alert-success">
            新しい認証リンクが送信されました。
        </div>
        @endif

        <form action="{{ route('verification.send') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-primary">
                認証メールを再送信
            </button>
        </form>
    </div>
</body>

</html>