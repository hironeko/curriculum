<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>TOPページ</title>
</head>
<body>
    <div class="container w-auto inline-block px-8">
        <div class="mt-20 mb-10 flex justify-between">

            <h1 class="text-base">登録者一覧</h1>
            <div>
                <button>新規登録</button>
            </div>
        </div>
        <table class="table-auto">
            <thead>
                <tr>
                <th class="px-4 py-2">名前</th>
                <th class="px-4 py-2">住所</th>
                <th class="px-4 py-2">電話番号</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="border px-4 py-2">田中 太郎</td>
                    <td class="border px-4 py-2">東京都千代田区1-1-1</td>
                    <td class="border px-4 py-2">09012345678</td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html>