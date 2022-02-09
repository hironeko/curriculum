<?php
require_once 'user.php';
$class = new User();
$id =isset($_GET['id']) ? $_GET['id'] : null;
$errorMessage = null;

if (is_null($id)) {
    $errorMessage = 'URLが不正です';
}

$user = $class->show($id);
if (empty($user)) {
    $errorMessage = '登録者が存在しません';
}

if (!empty($_POST)) {
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    try {
        $class->update($id, $name, $tel, $address);
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
            <?php if($user): ?>
            <h2 class="text-base mb-4">更新</h2>
            <form method="POST">
                <div class="mb-4">
                    <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2">
                    名前
                    </label>
                    <input 
                        class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
                        type="text"
                        name="name"
                        value="<?php echo $user['name'] ?>"
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
                        value="<?php echo $user['address'] ?>"
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
                        value="<?php echo $user['tel'] ?>"
                    >
                </div>
                <button 
                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
                    type="submit"
                >
                    登録
                </button>
            </form>
            <?php endif; ?>
        </div> 
    </div>
</body>
</html>