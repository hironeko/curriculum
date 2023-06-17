# 更新の作成

## 現状

- 一覧、新規作成の機能をここまで作成してきました。次にすでにあるデータに対しての更新を行うページの作成を行います。

## 前提

- ターミナルにて src ディレクトリ内にて`php -S localhost:8080` こちらのコマンドを実行してください
- ブラウザにて`http://localhost:8080`にアクセスしてください

## Part 1

- 今回いじる file は、index.php と edit.php になります。

処理フローとしては、一覧ページから更新ページに遷移して Form 内にデータが入力されている状態で変更加え、更新ボタンを押下したら一覧画面にレンダリングされる流れとなります。

※sample を動かしてみるとよりわかると思います。

さっそく触っていきたいと思います。更新用の file は既に用意してあるので新たに追加する必要あありません。

index.php を下記の様に修正します。

```html
<!-- before -->
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

<!-- after -->
<table class="table-auto">
  <thead>
    <tr>
      <th class="px-4 py-2">名前</th>
      <th class="px-4 py-2">住所</th>
      <th class="px-4 py-2">電話番号</th>
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
        <button
          class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
        >
          <a href="<?php echo '/edit.php?id=' . $user['id'] ?>">編集</a>
        </button>
      </td>
    </tr>
    <?php endforeach; ?>
  </tbody>
</table>
```

※インデントは適宜修正お願いします。

上記の様に編集するします。これで更新ページへの遷移は可能になります。しかしまだ Form の中にデータが何も表示されない状態になっているとおもいます。

動作確認を行いましょう。

## Part 2

更新ページで DB に存在しているデータの出力を行います。

今回画面遷移を行う上で`?id=xx`という形にしてます。この`id`の値を使用して DB からデータを取得します。更新ページの file を編集します。

```php
// 最初にDBへの接続を書きます
$dsn = 'mysql:dbname=test;host=127.0.0.1;port=3006;charset=utf8mb4';
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

var_dump($user);  // 確認後消してください
```

上記の様に記述することでデータの取得ができているはずです。

確認してみましょう。

## Part 3

Form 内にデータを出力させます。今回は、`foreach`などを使用する必要性がないので`$user`をそのまま使用します。

Form タグ内に存在している`input`タグの`value`属性に書いていきます。以下を参考に名前、住所、電話番号の value 属性に php を書いていきます。

```html
<input
  class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
  type="text"
  name="name"
  value="<?php echo $user['name'] ?>"
/>
```

すべての input タグの value 属性に記述しましょう。

記述が終わったら画面をリロードしてみてください。データが入力された状態になっていると思います。

## Part 3

更新ページなので入力されている値を変更し、更新ボタンを押下したら更新される様に追記しましょう。

> edit.php の php タグ内です

```php
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
```

基本的には、新規作成の処理と似ています。違う箇所は、`SQL`が`UPDATE`文になっています。また更新対象を`WHERE`句にて絞っています。

それ以外は、新規作成と同じなので直感的に理解ができると思います。

実際に動作させてみましょう。

※更新後は、新規作成時と同様に一覧画面へ遷移させてます
