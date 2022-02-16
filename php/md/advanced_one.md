## 応用編

- 目的とやること
  - より実践的な内容、アプリケーションにするために以下の内容をこれから学習していきます
    - Validation 機能の実装：入力がされていることなどを検証する機能
    - 処理の再利用化：今回は class を作成しほとんどの処理をそこに集約します
    - エラーハンドリング：DB に対しての処理がうまくいかなったり、Validation エラーを画面に出力します

# 共通化と Validation

## Part 1

- Validation が必要な場所
  - 新規作成と更新機能

現状は、何も用意しておらずから文字でも登録が可能になっている状態だと思います。Web サービスなどを触っていると入力する際に`必須`になっていたりする箇所が存在していると思います。これらは、比較的一般的な機能になりシステムやサービスを作る上で基本的な機能の一つと言えるでしょう。今回は、これらを学びます。またこの過程で共通化を行います。

## Part 2

- 最初に共通化や Class 化を行います。

### やること

- Class 化

- 最初に DB への接続に関しての共通化を行います。

該当箇所

```php
$dsn = 'mysql:dbname=test;host=127.0.0.1;port=3006';
$user = 'root';
$password = 'root';

try {
    $db  = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    echo "接続に失敗しました：" . $e->getMessage() . "\n";
    exit();
}
```

上記記述が index, create, edit にそれぞれ存在していると思います。重複された記述は、再利用性できるように一箇所にまとめて、修正する際に一箇所だけを修正するだけにとどめることがより保守性のあるコードに近づけます。

同じディレクトリに`base.php`という file を作成しましょう。作成したら以下のように記述しましょう。

> base.php

```php
<?php

class Base
{
    const DNS = 'mysql:dbname=test;host=127.0.0.1;port=3006;charset=utf8';
    const USER = 'root';
    const PASSWORD = 'root';
    protected PDO $db;

    public function connection()
    {
        try {
            $this->db  = new PDO(
                self::DNS,
                self::USER,
                self::PASSWORD
            );
        } catch (PDOException $e) {
            echo "接続に失敗しました：" . $e->getMessage() . "\n";
            exit();
        }
    }

    public function disconnection()
    {
        $this->db = null;
    }
}
```

- Class 化するにあたり、今回 DB に接続するための情報は、`const` で定数として定義します。Laravel に限らずですが FW を使用する際には、環境変数として一つの file にまとめたりします。今回は、Class に書いて使用します。

- `protected PDO $db;`

この書き方なのですが`PHP version7.4`以降から書ける様になったタイプヒンティングというものになります。元々 PHP は、型に緩い言語なのですが型に緩いと何が問題かというと予期せぬ引数や代入が行われて不具合のもにになります。例えば PHP ですと object を代入すると文字列に暗黙に変換されたりします。こういった予期せぬ挙動での不具合を防ぐ手法になってます。理想は、厳密な型制約を設ける方がいいのですが今回は、もうけてません。また厳密な型制約を設けるには PHP タグのすぐ後に`declare(strict_types=1);`と宣言します。

やっていることは、あまり変わりなく DB に接続する記述を Class にまとめたに過ぎませんが、共通化の 1 例として認識しておいてください。
各 file に記述されている DB に接続する処理は消してください。

## Part 3

- DB への接続に関しての共通化がおわりました。現状 DB への接続を Class にまとめただけでは使用できません。また各 file からも記述を削除したので動作しなくなっていると思います。

### 共通化した Class を使える様にしよう

今回 User に関連する情報を登録するので`user.php`という file を作成しましょう。この file の中に処理をまとめていきます。

```php

class User
{

}
```

上記の様に書いたなら色々書いていきます。具体的にどんなことを書いていくかというと以下を書いていきます。

- 一覧取得の処理
- 新規作成処理
- 更新処理
- 削除処理
- Validation 処理

以上になります。共通化ではないじゃないかとおもいますが、同じ DB への操作かつ同じ Table に対しての処理なので一箇所にまとめるのはよくあることです。所謂オブジェクト指向プログラミングです。

> ※今回は、Table も一個でまとめる単位として都合がいいですが本来ですとドメイン単位など意味ある単位でまとめたりするのが普通です。

## Part 4

- 共通化するための Class の作成はおわりました。がこのままではこの Class は DB への接続が行えません。なので`継承`というのを行います。

```php
<?php
require_once './base.php';

class User extends Base
{
```

上記のように記述することで DB への接続を行うことが可能になります。

- require_once 'file_name';
  require_once と書くことで外部 file を一度だけ読み込むことが可能になります。これをすることによって`class Base`を読み込んで継承することが可能になります。

> FW を使用する場合や`composer`を使用して`namespace`などが使えることによって`require_once`を使うことは、稀です。がエンジニアをしていると使う場面があったりするので覚えておきましょう。

## Part 5

- class Base を継承して DB への接続が使える状態には、なりました。しかし今の状態ですと DB に接続は行えてません。理由は以下です。
  - 1. 各 file で class User を使う様に記述していない
  - 2. class Base 内で設定した DB への接続処理が動かせていない

### 接続処理を class User で使える様にしよう

```php
<?php
require_once './base.php';

class User extends Base
{
    public function __construct()
    {
        $this->connection();
    }
```

- マジックメソッドと呼ばれる`__construct`メソッドを定義します。これは、class をインスタンス化した際に実行されるメソッドになっております。なので class User をインスタンス化したら自動的に DB への接続が完了している状態になります。

## Part 6

### 実際に一覧画面で使用できる様に実装しましょう

- 先に index.php で記述してある`削除`機能以外を削除しましょう。

> user.php

```php
<?php
require_once './base.php';

class User extends Base
{
    public function __construct()
    {
        $this->connection();
    }

    public function index()
    {
        return $this->db
            ->query("SELECT * FROM users WHERE del_flg = false")
            ->fetchAll(PDO::FETCH_ASSOC);
    }
```

- 上記のように修正したら次に`index.php`の修正も行います。

> index.php

```php
require_once 'user.php';
$user = new User();
$users = $user->index();
```

- 上記の様に修正したら実際にアプリケーションを動かしてみましょう。

* 動きましたでしょうか？

## Part 7

- 一覧の実装では、削除の機能もあるのでこちらも実装しましょう。
- またこのタイミングでエラーハンドリングも行える様に修正します。

まずは、削除の SQL 部分の移植します

> user.php

```php
<?php
require_once './base.php';

class User extends Base
{
    public function __construct()
    {
        $this->connection();
    }

    public function index()
    {
        return $this->db
            ->query("SELECT * FROM users WHERE del_flg = false")
            ->fetchAll(PDO::FETCH_ASSOC);
    }

    // 今後は以下のように追加していきましょう
    public function delete(string $id)
    {
        $sql = "UPDATE users SET del_flg = true WHERE id = :id AND del_flg = false";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        if (!$stmt->execute()) {
            throw new Exception('削除できませんでした');
        }
    }
```

- 関数化をおこなっているだけになります。また SQL の実行の結果削除ができなかった場合`execute()`は、`false`を返すので`Exception`を実行します。

* 次に`index.php`に以下を追記します。

```php
$errorMessage = null;
if (!empty($_GET['id'])) {
    try {
        $user->delete($_GET['id']);
        header('Location: http://localhost:8080');
    } catch (Exception $e) {
        $errorMessage = $e->getMessage();
    }
}
```

- 処理としては、URL にパラメーターが存在したら削除を行うとなります。丁寧な実装なら js などで confirm を出してあげたりしますが、今回は割愛しております。

- 今回 class User で削除されなかったら exception を実行する様にしているので index.php 側でハンドリングする必要性があります。なので今回`try catch`を使用しています。また今回は、変数に格納していますが、FW などでこの様に変数に入れて処理を内々に済ませることは推奨しません。理由として、エラーを握りつぶしているのとの同じになるからです。適宜適切な処理を心がけましょう。

### エラー内容の表示を行う

- Exception が実行されて try catch でハンドリングし変数に格納したのでエラーがある場合は、画面に表示される様に修正したいとおもいます。

> index.php

```html
<?php if ($errorMessage): ?>
<div
  class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
  role="alert"
>
  <span class="block sm:inline"><?php echo $errorMessage ?></span>
</div>
<?php endif; ?>
```

- 上記の記述を登録者一覧の文字列がある div の上に追記しましょう

これでエラーが起きた場合は、画面に表示される様になります。

## Part 8

- 新規作成の処理の移行

- やることはこれまでとほとんど似ています。
  - 同時に validation の作成等も行います

### Exception の拡張

- php の組み込みである Exception は、第一引数が文字列のみになってしまうため今回の様な複数の要素を検証し、複数のエラーが発生し複数の文字列を配列にして受け取ることができません。配列を json 化して無理くり使うことも可能ですが、try catch で受け取る側がその事情を知る方法が実装者以外実装を見に行かないとわからないということになってしまい保守性や可読性という観点でふさわしくありません。なので今回 Exception を拡張し独自の Exception を作成します。以下の様に新たな file を作成し記述しましょう。

> validationException.php

```php
<?php

class ValidationException extends Exception
{
    public function __construct(array $message = null, int $code = 0, Exception $previous = null) {
        parent::__construct(json_encode($message), $code, $previous);
    }

    public function getArrayMessage($assoc = true) {
        return json_decode($this->getMessage(), $assoc);
    }
}
```

- このように拡張することによって利用する側は、配列を渡すだけでこの Exception を使用することができます。また try catch で受け取る側もメソッド名を見れば配列で取得することができるというのがわかるので再利用性、保守性、可読性に優れたものになります。

> user.php

```php
<?php
require_once './base.php';
require_once './validationException.php';

class User extends Base
{
    private string $name;
    private string $tel;
    private string $address;

    public function __construct()
    {
        $this->connection();
    }
    // ~~ 省略 ~~

    // 以下すべて追記
    public function create(string $name, string $tel, string $address)
    {
        $this->name = $name;
        $this->tel = $tel;
        $this->address = $address;
        $this->validation();
        $sql = "INSERT INTO users (name, address, tel) VALUES (:name, :address, :tel)";
        $stmt = $this->db->prepare($sql);
        if (!$this->createOrUpdate($stmt)) {
            throw new Exception('登録できませんでした');
        }
    }

    private function validation()
    {
        $errorMessage = [];
        if (empty($this->name)) {
            $errorMessage[] = '名前が入力されてません';
        }

        if (empty($this->tel)) {
            $errorMessage[] = '電話番号が入力されてません';
        }

        if (empty($this->address)) {
            $errorMessage[] = '住所が入力されてません';
        }

        if (!empty($errorMessage)) {
            throw new ValidationException($errorMessage, 422);
        }
    }

    private function createOrUpdate(PDOStatement $stmt, ?string $id = null)
    {
        $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindValue(':tel', $this->tel);
        $stmt->bindValue(':address', $this->address, PDO::PARAM_STR);
        if (!is_null($id)) {
            $stmt->bindValue(':id', $id, PDO::PARAM_STR);
        }
        return $stmt->execute();
    }
```

上記の様に追記しましょう。タイプヒンティングは、最初の方に説明した通りなのですが他の処理に関して説明します。

- プロパティ

  - 本来 private にして使いまわさなくてもいいのですが、学習として学びます。またなぜ private なのかというとこの Class 以外で使うことがないもになるためです。

- validation

  - それぞれの変数に値がない場合は、exception を使い例外をスローします。また 422 という数字は、validation error の際によく用いられます。エンドポイントとしては正しいけどサーバー側が返答できないリクエストという意味になります
  - 最初に作成した拡張 Class を使うことで実際にエラーを配列で渡すことができます

- createOrUpdate
  - こちらは、引数に ID があるかないかで新規作成なのか更新なのかを判断して処理をおこなっています。また実行結果に応じて呼び出し元では、exception をスローしています

Exception を使っているということは、ハンドリングを行わないといけません。この簡易アプリケーションの学習後に学ぶ`Laravel`では、ハンドリング用の class が用意されておりそこに処理が集約されハンドリングはそこで行いますが、今回そういう class がありません。なので`try catch`を使用してハンドリングを行います。

### 以下の様に修正しましょう

> create.php

```php
require_once 'user.php';
require_once 'validationException.php';
$user = new User();

$errorMessage = [];
if (!empty($_POST)) {
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    try {
        $user->create($name, $tel, $address);
        header('Location: http://localhost:8080');

    } catch (ValidationException $e) {
        $errorMessage = $e->getArrayMessage();
    }
}
```

- また削除処理の時同様にエラーメッセージを出す記述を書きましょう
  > create.php

```html
<?php if (!empty($errorMessage)): ?>
<?php foreach($errorMessage as $message): ?>
<div
  class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
  role="alert"
>
  <span class="block sm:inline"><?php echo $message ?></span>
</div>
<?php endforeach; ?>
<?php endif; ?>
```

- 新規登録の文字列が書かれた div の上に追記してください。これで validation エラーの表示が可能になりました。

この段階で動作確認を行うことが可能だと思います。実際に入力を行わないで動作させましょう。

## Part 9

- 最後になります。更新処理の機能を移行しましょう。

### やること

- URL が不正な場合は、不正と表示する
- 登録者が存在しない場合は、存在しないと表示する
- 新規作成と同様に validation を通す様にする
  - class へ処理を移行する

以上を行います。

> user.php

```php
   public function show(string $id)
    {
        $stmt = $this->db
            ->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(string $id, string $name, string $tel, string $address)
    {
        $this->name = $name;
        $this->tel = $tel;
        $this->address = $address;
        $this->validation();
        $sql = "UPDATE users SET name = :name, address = :address, tel = :tel WHERE id = :id AND del_flg = false";
        $stmt = $this->db->prepare($sql);
        if (!$this->createOrUpdate($stmt, $id)) {
            throw new Exception('更新できませんでした');
        }
    }
```

上記内容を`validation`メソッドの前に書きましょう。すでに新規作成時に作成した`createOrUpdate`メソッドを使用して SQL の実行を行っています。ほとんど処理の移行でしかないので説明することはここではあまりありません。

- 次に edit.php の修正を行います。

> user.php

```php
require_once 'user.php';
require_once 'validationException.php';

$class = new User();
$id = isset($_GET['id']) ? $_GET['id'] : null;
$errorMessage = [];
$user = null;

if (is_null($id)) {
    $errorMessage[] = 'URLが不正です';
} else {
    $user = $class->show($id);
}

if (!is_null($id) && empty($user)) {
    $errorMessage[] = '登録者が存在しません';
}

if (!empty($_POST)) {
    $name = $_POST['name'];
    $tel = $_POST['tel'];
    $address = $_POST['address'];

    try {
        $class->update($id, $name, $tel, $address);
        header('Location: http://localhost:8080');

    } catch (ValidationException $e) {
        $errorMessage = $e->getArrayMessage();
    }
}
```

上記の様に修正しましょう。こちらもこれまでの学習を行ってきたことをしか出てきてません。

エラーメッセージを表示するため HTML の方にも手を加えます。

> edit.php

```html
<?php if (!empty($errorMessage)): ?>
<?php foreach($errorMessage as $message): ?>
<div
  class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative"
  role="alert"
>
  <span class="block sm:inline"><?php echo $message ?></span>
</div>
<?php endforeach; ?>
<?php endif; ?>
<div class="flex justify-between">
  <h2 class="text-base mb-4">更新</h2>
  <button
    class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
  >
    <a href="/">戻る</a>
  </button>
</div>
<?php if($user): ?>
<form method="POST">
  <div class="mb-4">
    <label
      class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
    >
      名前
    </label>
    <input
      class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
      type="text"
      name="name"
      value="<?php echo $user['name'] ?>"
    />
  </div>
  <div class="mb-4">
    <label
      class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
    >
      住所
    </label>
    <input
      class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
      type="text"
      name="address"
      value="<?php echo $user['address'] ?>"
    />
  </div>
  <div class="mb-4">
    <label
      class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2"
    >
      電話番号
    </label>
    <input
      class="appearance-none block w-full bg-white text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
      type="text"
      name="tel"
      value="<?php echo $user['tel'] ?>"
    />
  </div>
  <button
    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
    type="submit"
  >
    更新
  </button>
</form>
<?php endif; ?>
```

- 追記しましたでしょうか？さっそく動作確認を行いたいと思いますので実際に動作させましょう！

* また今回、データが存在しないときは Form 自体を非表示にする様にしています。

## Last

- これにて簡易アプリケーション作成の全工程を終えます。ここまで習ってきたことは、あくまでの基礎の「き」になります。しかしどんなことでもそうですがこの基礎が頭にはいっているかどうかの違いはものすごく大きいです。このあとは、Laravel の学習に移行しますが Laravel はとても便利な FW ですがその分 Laravel がやってくれていることが多くわからないまななんとなく使っているという人が多くいます。他のエンジニアと差をつけるのは、「どうしてこうなっているのか」「どこをみれば何がわかるのか」などといった思考と解決方法を把握していることがとても強みになります。引き続き学習を頑張っていきましょう。
