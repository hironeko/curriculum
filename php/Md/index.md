# 一覧の作成

## 現状

- TOP ページにて表示されている内容は、コード上にハードコードされている内容になっており実際に DB に保存されている内容とは異なります。今回これを削除し、DB に保存されている内容を表示される様に修正します。

## 前提

- ターミナルにて src ディレクトリ内にて`php -S localhost:8080` こちらのコマンドを実行してください
- ブラウザにて`http://localhost:8080`にアクセスしてください

## Part 1

今回いじる file は、`index.php`になります。

DB に存在しているデータを取得する方法として`SELECT`文を使用します。

index.php の上にある php タグ内に以下の記述をしましょう

```php
// 最初にDBへの接続を書きます
$dsn = 'mysql:dbname=test;host=127.0.0.1;port=3006';
$user= 'root';
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
```

前回の新規作成の学習をおこなった手順に SQL についての説明を軽く行いましたがそこで登場した`SELECT`文を使用してます。また前回と異なる点として処理に使うメソッドの種類が異なっております。今回は、以下の様なメソッドを使用しています。

```text
query() // 既にSQL文が確定されている場合に用いることが多いです。返り値は、クエリの結果が返却されます
↓
fetAll() // 結果から配列を取得します。引数にしているのは、配列のキーがカラム名になる指定方法です
```

以上の説明から分かる通り、`$users`には、DB から取得できたデータがカラム名をキーにした配列で取得できることが想定できます。

何事も確認は、大事なので、実際に取得できている内容を確認した方がいいと思いますので確認しましょう

```php
var_dump($users);
```

この書き方で変数に格納されている配列が確認できます。どんな感じに表示されましたでしょうか？以下はの様な表示されましたでしょうか？

> 例

```text
array (size=2)
  0 =>
    array (size=5)
      'id' => string '1' (length=2)
      'name' => string 'test' (length=4)
      'tel' => string '09012345678' (length=11)
      'address' => string 'test' (length=4)
      'del_flg' => string '0' (length=1)
```

## Part 2

一覧表示に必要なデータが取得できました。次はこれを HTML 上に展開していきたいと思います。

ここで使用するのは、`foreach`です。またいじる箇所は、`tbody`タグ内になります。以下の様に修正しましょう。

```html
<!-- before -->

<tbody>
  <tr>
    <td class="border px-4 py-2">田中 太郎</td>
    <td class="border px-4 py-2">東京都千代田区1-1-1</td>
    <td class="border px-4 py-2">09012345678</td>
  </tr>
</tbody>

<!-- after -->

<?php foreach($users as $user): ?>
<tr>
  <td class="border px-4 py-2"><?php echo $user['name'] ?></td>
  <td class="border px-4 py-2"><?php echo $user['address'] ?></td>
  <td class="border px-4 py-2"><?php echo $user['tel'] ?></td>
</tr>
<?php endforeach; ?>
```

※インデントがずれてますが対応していただけると

- 解説

HTML 上に`foreach`を記述したりする場合は、上記の様な書き方を行います。少し特殊になりますが、この書き方をすることですべてを`php`タグで囲う必要がなくなります。

この状態でページをリロードしてみてください。

一覧に DB から取得した内容が表示されましたでしょうか？
