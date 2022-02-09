<?php
require_once 'user.php';
$user = new User();
$users = $user->index();
var_dump($users);
$errorMessage = null;
if (!empty($_GET['id'])) {
    try {
        $user->delete($_GET['id']);
        header('Location: http://localhost:8080');
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
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
        <?php if ($errorMessage): ?>
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                <span class="block sm:inline"><?php echo $errorMessage ?></span>
            </div>
        <?php endif; ?>
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
                            <td class="border px-4 py-2">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                    <a href="<?php echo '/edit.php?id=' . $user['id'] ?>">編集</a>
                                </button>
                            </td>
                            <td class="border px-4 py-2">
                                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                                    <a href="<?php echo '?id=' . $user['id'] ?>">削除</a>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>