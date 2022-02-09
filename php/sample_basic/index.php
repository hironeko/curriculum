<?php

$dsn = 'mysql:dbname=test;host=127.0.0.1;port=3006';
$user = 'root';
$password = 'root';

try {
    $db  = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "接続に失敗しました：" . $e->getMessage() . "\n";
    exit();
}

// 次に取得すrための記述を行います。
$users = $db->query("SELECT * FROM users WHERE del_flg = false") // del_flgは削除の有無を保持しています。デフォルトでは、0が入っているためfalseを指定することで削除されていないデータが取得対象になります。
           ->fetchAll(PDO::FETCH_ASSOC);
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
            <button 
                class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
                <a href="/create.php">新規登録</a>
            </button>
        </div>
        <div>
            <table class="table-auto">
                <thead>
                    <tr>
                        <th class="px-4 py-2">名前</th>
                        <th class="px-4 py-2">住所</th>
                        <th class="px-4 py-2">電話番号</th>
                        <th class="px-4 py-2"></th>
                        <th class="px-4 py-2"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($users as $user): ?>
                        <tr>
                            <td class="border px-4 py-2"><?php echo $user['name'] ?></td>
                            <td class="border px-4 py-2"><?php echo $user['address'] ?></td>
                            <td class="border px-4 py-2"><?php echo $user['tel'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>