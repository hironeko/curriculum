## 応用編

- 目的とやること
  - より実践的な内容、アプリケーションにするために以下の内容をこれから学習していきます
  - 1. Validation 機能の実装：入力がされていることなどを検証する機能
  - 2. 処理の再利用化：今回は class を作成しほとんどの処理をそこに集約します
  - 3. エラーハンドリング：DB に対しての処理がうまくいかなったり、Validation エラーを画面に出力します

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
