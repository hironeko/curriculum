<?php
require_once 'user.php';
$user = new User();

if (!empty($_POST)) {
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    try {
        $user->create($name, $tel, $address);
        header('Location: http://localhost:8080/sample');
    
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
    <title>新規登録ページ</title>
</head>
<body>
    <div class="container w-auto inline-block px-8">
        <div>
            <?php if ($errorMessage): ?>
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline"><?php echo $errorMessage ?></span>
                </div>
            <?php endif; ?>
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
                <button 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    type="submit"
                >
                    登録
                </button>
            </form>
        </div> 
    </div>
</body>
</html>