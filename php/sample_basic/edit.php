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

$id = isset($_GET['id']) ? $_GET['id'] : null;

$stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
$stmt->bindValue(':id', $id);
$stmt->execute();
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!empty($_POST)) {
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    $sql = "UPDATE users SET name = :name, address = :address, tel = :tel WHERE id = :id AND del_flg = false";
    $stmt = $db->prepare($sql);
    $stmt->bindValue(':name', $name, PDO::PARAM_STR);
    $stmt->bindValue(':tel', $tel, PDO::PARAM_STR);
    $stmt->bindValue(':address', $address, PDO::PARAM_STR);
    $stmt->bindValue(':id', $id, PDO::PARAM_STR);
    $stmt->execute();
    header('Location: http://localhost:8080');
}
?>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>更新ページ</title>
</head>
<body>
    <div class="container w-auto inline-block px-8">
        <div> 
            <div class="flex justify-between">
                <h2 class="text-base mb-4">更新</h2>
                <button class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow">
                    <a href="/">戻る</a>
                </button>
            </div>
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
                    更新
                </button>
            </form>
        </div> 
    </div>
</body>
</html>