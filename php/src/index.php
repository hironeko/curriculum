<?php
?>
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
        </div>
        <div class="border-solid border-b-2 border-gry-500 p-2 mb-2">
            <h2 class="text-base mb-4">新規登録</h2>
            <form method="POST">
                <div class="mb-4">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    名前
                    </label>
                    <input 
                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="text"
                        name="name"
                        placeholder="鈴木 太郎"
                    >
                </div>
                <div class="mb-4">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    住所
                    </label>
                    <input 
                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="text"
                        name="address"
                        placeholder="東京都千代田区1-1-1"
                    >
                </div>
                <div class="mb-4">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    電話番号
                    </label>
                    <input
                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="text"
                        name="tel"
                        placeholder="09012345678"
                    >
                </div>
                <button class="rounded-none" type="submit">登録</button>
            </form>
        </div>
        <div>
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
    </div>
</body>
</html>