<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>登録ページ</title>
</head>
<body>
    <div class="container mx-auto px-8">
        <div>
            <p class="text-base mb-10 mt-20">登録</p>
        </div>
        <div class="w-full max-w-xs">
            <div class="mb-4">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                名前
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="grid-last-name" type="text" placeholder="田中 太郎">
            </div>
        </div>
        <div class="w-full max-w-xs">
            <div class="mb-4">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                住所
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="grid-last-name" type="text" placeholder="東京都千代田区1-1-1">
            </div>
        </div>
        <div class="w-full max-w-xs">
            <div class="mb-4">
                <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="grid-last-name">
                電話番号
                </label>
                <input class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                id="grid-last-name" type="text" placeholder="09012345678">
            </div>
        </div>
        <button class="bg-gray-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
        送信
        </button>
    </div>
</body>
</html>