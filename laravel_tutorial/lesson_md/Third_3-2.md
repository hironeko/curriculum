# PHPUnit の実装

- Laravel に最初から付随している PHPUnit を使用します。

## 早速実行してみましょう

Laravel が存在しているディレクトリにて以下のコマンドを実行してみてください。

```shell
./vendor/bin/phpunit
PHPUnit 6.4.4 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 122 ms, Memory: 12.00MB

OK (2 tests, 2 assertions)
```

という結果になりましたでしょうか？
これを一つずつ解説していきます。こちらは、テストの実行結果になってます。
ではどこをテストしどこにそのテストのコードが記載されているのかそれらを含め少しずつ追っていきたいと思います。

### テストのコードはどこに？

`tests/Feature/ExampleTest.php`
`tests/Unit/ExampleTest.php`
です。

Laravel に付属している PHPUnit を使用してのテストを書く場合は、上記の場所に格納します。またそれぞれ意味があるのでそれらも一緒に把握をしていきましょう。

- `Feature`

これは、日本語的には、「特徴・特性」といった意味になりますがプログラマーとしての意味は、「機能」と訳した方がしっくりくるかもしれません。
また近しい言葉に`function` がありますがこちらは、より技術的な側面を持っているものになっております。

ここに格納されるテストコードは、広義の意味での機能テストとなります。
実際に中を覗くとわかると思いますがどの`URI` に対してのレクエストを送りその結果どうなったかをテストする内容になってます。

- `Unit`

日本語的には、「単位」といった意味になります。
どんな時に使うのか？を知った際にとてもしっくりくるかと思います。
テスト対象は、最小単位のコードです。

> 中身は、後ほど一緒に見ていきましょう。

- まとめ

上記踏まえ`Feature` で広義にテストコードを書き`Unit` に対して最小単位のメソッドないし機能に対してのテストを書くことによって結合テストの時間を減らせることが容易に想像できます。

また結合テストというのは、前章で述べたようにブラウザにて人力でデータの入力等を行い期待通りの結果になるかどうかのテストをここでは指しています。

なのでこのカリキュラムでは、まず最初に`Feature` にてリクエスト関連のテストを行い、`Unit` にて機能レベルのテストを行なっていきたいと思います。

### テストコードを読んでみる

- では、早速テストのコードを見ていきましょう。

`tests/Feature/ExampleTest.php` からみていきましょう。

```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
* ExampleTest class
*/
class ExampleTest extends TestCase
{
    /**
    * @const int 成功時のステータスコード
    */
    const SUCCESS_STATUS_CODE = 200;  // 定数は、必ずclass内に書きます。

    /**
     * topページの検証
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(self::SUCCESS_STATUS_CODE);
    }
}
```

実際にこれらは、何をテストしているのか確認していきましょう。
`extends TestCase` の箇所は、お作法だと思ってください。なので新規にテストコードを書く際も必ず記載がないとなりません。

`public function testBasicTest` の中を見ていきましょう。

PHPUnit の基本としてメソッドを書く際には、二通りの書き方が存在してます。(version で異なる)

`test` という接頭辞から`function` を始める場合
`function` 名に`test` と含めずにアノテーションでテストコードですよと宣言する場合

この二通りが存在してます。

では、実際にアノテーションで書いて実行して見ましょう。

```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
* ExampleTest class
*/
class ExampleTest extends TestCase
{
    /**
    * @const int 成功時のステータスコード
    */
    const SUCCESS_STATUS_CODE = 200;

    /**
     * topページの検証
     * @test  // 追記
     */
    public function basicTest() // 変更
    {
        $response = $this->get('/');

        $response->assertStatus(self::SUCCESS_STATUS_CODE);
    }
}
```

変更が完了したらテストを実行しましょう。

```shell
./vendor/bin/phpunit
PHPUnit 6.4.4 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 125 ms, Memory: 12.00MB

OK (2 tests, 2 assertions)
```

問題なくテストが行えましたね。
これで 2 通りの書き方が学べました。
では、次に中身を見ていきましょう。

- `$response = $this->get('/');`

まず`$this->get('/')` ですがこれは、get リクエストで引数の URI にアクセスするものになります。その結果の返り値を`$response` に格納してます。
これは通常のリクエスト結果が格納されているに等しいと認識して今は、問題ありません。
その返り値に対して`$response->assertStatus(200)` という記述が書かれてます。これは、リクエスト結果のステータスを確認してます。

`assetStatus` これは、`responceのstatusには、()内の値を期待する` ということです。つまり()内の指定の数値以外は、全てが異常系という扱いになります。それは、機能としてよろしくないということになります。

なのでここでは、問題なく http 通信での GET リクエストが問題なく行われているかどうかのテストを行なっています。

`tests/Unit/ExampleTest.php` の中を見ていきましょう。

```php
<?php
declare(strict_types=1);

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
* ExampleTest class
*/
class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest()
    {
        $this->assertTrue(true);
    }
}
```

先ほどと同様にアノテーションでの書き換えも可能です。
こちらのテストに関しては、どこに対してのテストなのか？また何のテストをしているのかは、これだと読み解けないです。

ただし`true` を`false` に変えたら`FAILURES!` となります。
こちらの方では、特段何かしらに対してのテストかは、言及しませんが`assertTrue` という箇所は、覚えておきましょう。
他のテストを作成する上で使うことが想定されるメソッドになっています。

## 実践していきましょう

### `Feature` のテスト

今回作成した Todo Application へのテストとなります。
最初に`Feature` 側に対してのテストを実装していきたいと思います。

```shell
php artisan make:test TodoTest
```

`tests/Feature/` 以下に TodoTest.php が作成されましたでしょうか？
作成された File は、以下のようになっているはずです。

```php
<?php
declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

/**
* TodoTest class
*/
class TodoTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function testBasicTest()
    {
        $this->assertTrue(ture);
    }
}
```

この作成された File の`fucntion` を編集し`TodoController` の`index` メソッドに対してテストを書きたいと思います。

```php
    /**
    * @const int 成功時のステータスコード
    */
    const SUCCESS_STATUS_CODE = 200;

    /**
    * @const string アクセスするURI
    */
    const ACCESS_URI = '/todo';

    /**
     * todoページの検証
     * @test  // 追記
     */
    public function indexTest()  // 変更
    {
    　　// 以下変更と追記
        $response = $this->get(self::ACCESS_URI);
        $response->assertStatus(self::SUCCESS_STATUS_CODE);
    }
```

ではテストを実行して見ましょう。

```shell
./vendor/bin/phpunit tests/Feature/TodoTest.php
```

最初に実行したコマンドと異なり今回は、File の指定を行なっています。
こうすることにより指定した File のみテストが実行可能になります。

では、結果はどうなりましたでしょうか？
問題なく通過したのではないでしょうか？

> もし`F`という文言があったならば`MySQL` の起動の有無を確認してください。

これで`index` メソッドに対してのテストは、行われました。
`index` メソッドにより表示されるページというのは、Todo の一覧画面となってますが実際にどんな値が渡っているかどうかは、テストしてません。

このまま他の画面に対してのテストも実装していきたいのですが今の状態でのテストの実装は、`create` メソッドまでになります。
というのもこれ以降の画面に対しての処理には、データが必要になってきますのでテストで使用する`DB` の設定を行いたいと思います。

```php
// 省略
    /** @test */
    public function createTest()
    {
        $uri = self::ACCESS_URI . '/create';
        $response = $this->get($uri);
        $response->assertStatus(self::SUCCESS_STATUS_CODE);
    }
}
```

## テスト用の`DB` 設定を行う

- テスト用の DB の作成を先に行ってください。
  - 作成というのは、mysql サーバーに接続し`create database test_db;` を実行し作成をするということです。
- ネット上には、いくつかの設定方法がありますが一旦は、わかりやすいもので設定を行いたいと思います。
  `phpunit.xml` を編集します。

```xml
<!-- 省略 -->
  <php>
  <!-- 省略 -->
      <env name="DB_DATABASE" value="test_db"/> <!-- 追記 今回は、test_dbという名前に設定 -->
  </php>
</phpunit>
```

上記のように追加してください。

次にテストが実行される際に`migrate` が走るように設定を行います。

そのために編集、追記を行う File は、以下の 2 個です。
`tests/CreatesApplication.php`
`tests/TestCase.php`

`tests/CreateApplication.php` を編集します。

```php
<?php
declare(strict_types=1);

namespace Tests;

use Illuminate\Contracts\Console\Kernel;
use Artisan; // 追記
use App\Todo; // 追記

/**
* CreateApplication trait
*/
trait CreateApplication
{
    /**
     * Creates the application.
     *
     * @return \Illuminate\Foundation\Application
     */
    public function createApplication()
    {
        $app = require __DIR__.'/../bootstrap/app.php';

        $app->make(Kernel::class)->bootstrap();

        return $app;
    }

    // ここから追記
    /**
    * artisanコマンドを実行しmigrationを行う
    * 実行後テストに必要なデータを投入する
    * @return void
    */
    public function prepareForTests(): void
    {
        Artisan::call('migrate');
        if(!Todo::all()->count()){
            Artisan::call('db:seed');
        }
    }
    // ここまで追記
}
```

- 解説
  `use Artisan;`
  Laravel の学習をするにあたり何度も出てきた`Artisan` コマンドを使えるようにするための記述です。

`use App\Todo;`
model に当たる`app/Todo.php` を使用できるようにし`table` への操作を行うのが目的です。

それぞれの使い方は、`public function prepareForTests` の中をご覧ください。

編集を加えたことによって使用可能になるのかと思いきやこのままでは、使用できません。使用するには、`TestCase.php` を編集します。
では、早速編集しましょう。

```php
<?php
declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

/**
* TestCase class
*/
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, DatabaseTransactions;

    /**
    * setUp
    */
    public function setup()
    {
        parent::setup();
        $this->prepareForTests();
    }
}
```

さて編集が完了したので実際に動かしてみましょう。

```shell
./vendor/bin/phpunit
```

問題なく完了したら次に`DB` の中を確認してみましょう。

テスト用の`DB` に対して`seed` まで完了できていることが確認できたら問題ありません。

追記した箇所から説明をしていきたいと思います。

`tests/TestCase.php`

- `use Illuminate\Foundation\Testing\DatabaseTransactions`
  まずは、`Transaction` という物の説明をします。
  よく例え話に出てくるのは、`ATM` ですがもっと身近なもので例えるなら`自動販売機` になります。
  自動販売機というのは、お金を入れ > 商品を選び(ボタンを押す) > 受け取るまでが一つの処理の単位として考えられます。
  お金は入れたが商品を選ばずに返却レバーを押すとお金が返ってきますよね？
  また商品選択を行なった後商品が出てくる際にお金が自動販売機のお金をストックする箱に落ちる音が聞こえると思います。
  一つの処理の単位とみなせるということは、どこかで問題が起きても取り消せる状態でなければいけないということです。
  `DB` に対しての処理では、このようなことに対処するために`Transaction` という仕組みをあらかじめ定義することができるようになってます。
  `DB` 的な言葉に変えれ`DB` に対しての一つ以上の更新ないし挿入処理のことをさします。
  今回この`Transaction` を使用する理由は、この後に説明すること、また次章で実装することに大きく関わってきます。
  `tests/CreateApplication.php` で`migration、seed` を記述したのですがそれ以外の`DB` に対しての挿入・更新の一連の処理をテストが終わった際になかったことにしテスト用の`DB` を綺麗な状態に保つためです。
  `class` 内にて`use` をしてあげることにより動作してくれるようになります。

- `public function setup(){ ~ }`
  まずは、記述の確認をしましょう。

```php
// 省略
    /**
    * setUp
    */
    public function setup()
    {
        parent::setup();
        $this->prepareForTests();
    }
// 省略
```

`function` 内に`parent::setup();` という記述がありますがこれを書かなければいけない理由は、この class が継承している`BaseTestCase` に記載があるメソッドを使用しますよという宣言になっています。
また`setup` という名称になっている理由も継承元のメソッドを使う際は、同じ名称にしなければならないというルールだからです。このメソッドはなんのためのメソッドかというとテストを実行する前に行いたい処理をまとめるメソッドになっています。
もし仮に`parent::setup();` を書いていなかったら動きません。

- `$this->prepareForTests();`
  いきなり`$this->` と出てきました。これは、本来 class 自身をオブジェクトとして使う際に出てきます。

例えば`class Hoge` というのがありその class 内に`public function fuga(){ ~ }` と`public function test(){ ~ }` と 2 種類のメソッドが存在したとします。`fuga` で`test` を使用したいという場面があった場合に`$this->test();` と書きます。

このように書くことによって`fuga` で`test` を使用可能になります。

今回の場合なのですがこの class 内にて`prepareForTests` というメソッドは、定義されてません。では、どこにあるというのでしょうか？
その答えは、`use CreateApplication` と書かれた箇所に存在してます。

では、この流れで`tests/CreateApplication.php` についても学んでいきましょう。

- `tests/CreateApplication.php`

この file を編集した際の追記箇所に`prepareForTests` というメソッドが存在してます。先ほどの`tests/TestCase.php` の解説の中で出てきた`$this->prepareForTests();` は、このメソッドをさしてます。
ではなぜ継承してないのに自身の class 内にも定義してないのに使えるのか？

それは、`trait` というのは、単純にいくつかのメソッド(機能)群をまとめたものになります。trait は、`use` してあげることによりその class 内のメンバーとなることができます。また class が継承している親 class のメソッドよりも優先されます。

この trait という機能は、コードの再利用、また多重継承などのにより起こりうる複雑な問題なども解決してくれます。

ただデメリットも存在していますがここでは、割愛します。興味がある方は、以下の参照 URL からご確認ください。

> [参照 URL](https://secure.php.net/manual/ja/language.oop5.traits.php)

この trait は、Laravel でももちろんですが多くの場面で使用されてますので`use` = `trait` の可能性もあるんじゃね？って考えるようにしましょう。
もちろんそれ以外の場合もありますのでこの限りでは、ありません。

- `public function prepareForTests(){ ~ }`
  file 上部に記載ある`use` に関しては、このメソッドで使用するものになりますのでまとめて説明します。
  まずこの function 内で行われているのは、`DB` への`migrate` と`seed` です。
  テストで使用するための`defaultのデータ`と`table` の作成を行なっています。
  そのため DB に対しての migrate を実行するには、`artisan` コマンドが使えなければなりません。なので`use Artisan;` と記述し使えるよにしてます。
  また今回は、`Todo table` に対してテストを実行する毎に`seed` を行う必要がないので`Todo table` にデータが存在しているかの記述を加えてます。
  もし存在してなければ`seed` が走るようになってます。

## まとめ

- どの章よりも長くなってしまいましたが DB への処理の設定を終えたところで次章に持ち越したいと思います。

- また難易度がグッと上がったように感じますが今までに習ってきたことの延長線上ですし`テスト` に限った内容ではないことを忘れないでおきましょう。

- 次章：今回やっていない`Unit` 以下に配置するテストの実装と`Feature` 以下にある file のテストの続きとなります。
