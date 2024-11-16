<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>

<body>
    <form action="{{ route('restaurants.importCsv') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <input type="file" name="file" accept=".xlsx, .csv" required>
        <button type="submit">インポート</button>
    </form>

    <form action="{{ route('image.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="image">画像を選択してください:</label>
        <input type="file" name="image" id="image" required>

        <button type="submit">アップロード</button>
    </form>

</body>

</html>