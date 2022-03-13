## さっそく実装していきます

### 一個前の説明

- 思い返してみてほしいのですが基本的には、今までの実装と基本は、何ら変わりはありませんということです。

### 同時に PHPUnit を学ぶ

- API の実装を行う上でテストの実装は欠かせません。なぜテストが必要か？これは別途記述させていただきます。

- API の実装は、手法がいろいろありますがテスト駆動開発(TDD)が代表的です。
  - 今回は、少し TDD っぽい実装で進めていきたいと思います。
  - 実装手順は、仕様として正しい状態のレスポンスがあらかじめ決まっていますのでレスポンスがその形式になっていないといけない実装にならないといけません
  - 最初に正しい状態にレスポンスとなっている状態のテストケースを記述します。
  - 記述後テストを実行しテストが失敗することを確認します。
  - API の実装していないので失敗していることのは当然です。
  - 次は、テストをパスするような実装をしていきます。
  - 実装が完了したらテストを再度実装し、パスすることを確認する
  - この際に実装を愚直に動く状態を目指すだけの書き方をしている場合リファクタリング等を行います。
  - リファクタリング等を行った場合再度テストを実行してパスすれば実装は完了します。

### 上記手順をもとに実装を行っていきたいと思います

#### テストを行う上での環境を構築する

- DB には、すでに`laravel_test`という DB が存在しています。

  - テストを行う上で使用する DB になります。
  - この DB を使用するための設定をおこなっていきたいと思います。

- 最初に`.env`を複製し以下の様に file 名かつ修正を行います。

> .env.testing

```
APP_ENV=testing
// 省略
DB_DATABASE=laravel_test
// 省略

```

- 修正後に下記コマンドを実行してください

```shell
php artisan migrate --env=testing
```

- migration が実行されたら問題ありません。これでテスト環境の DB 環境が整いました。

#### テストの作成

- テストを作成します。今回は、`Feature`テストを作成します。以下のコマンドを実行してください。

```shell
php artisan make:test Api/TodoControllerTest
```

以下の内容の file が`tests/Feature/Api`ディレクトリ以下に`TodoControllerTest.php`が作成されているとおもいます。

```php
<?php

namespace Tests\Feature\Api;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class TodoControllerTest extends TestCase
{
    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_example()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }
}

```

- この file を編集していきます。

#### テストを実行してみる

```shell
./vendor/bin/phpunit
```

このコマンドをルートディレクトリにて実行してみてください。

```shell
HPUnit 9.5.13 by Sebastian Bergmann and contributors.

...                                                                 3 / 3 (100%)

Time: 00:00.328, Memory: 22.00 MB

OK (3 tests, 3 assertions)
```

上記みたいな結果がターミナルにて表示されたでしょうか？

- 現状すべてのテストが OK の状態になっています。

### TDD っぽく先にテストから書いていきます。

- 今回一覧以外のものに関してテストを専攻して実装していきます。
  - なぜ一覧以外なのかというと本来 API の実装はレスポンス内容(スキーマ)を先に決めてフロントエンド(リクエスト元)ではどのような形式で受け取るかなどを決めてから実装の業務に入ることのが最近では主流になっています。今回一覧のレスポンス内容に関して特に取り決めをしておらず、また最低限ページネーション機能が効いている状態になってればいいという状態での実装になります。一覧だけは API 側の実装を先に行い、テストを書くことにします。

ではさっそく新規作成からテストを書いていきます。不要なもなは削除したいので、実装する前に class 内は空にしてください。

> TodoControllerTest.php

```php
<?php

namespace Tests\Feature\Api;

use App\Models\Todo;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class TodoControllerTest extends TestCase
{
    use DatabaseTransactions;

    public function setUp():void
    {
        parent::setUp();
    }

    /**
     * @test
     */
    public function Todoの新規作成()
    {
        $params = [
            'title' => 'テスト:タイトル',
            'content' => 'テスト:内容'
        ];

        $res = $this->postJson(route('api.todo.create'), $params);
        $res->assertOk();
        $todos = Todo::all();

        $this->assertCount(1, $todos);

        $todo = $todos->first();

        $this->assertEquals($params['title'], $todo->title);
        $this->assertEquals($params['content'], $todo->content);

    }
}
```

実装が終わったらテスト実行します。

```shell
./vendor/bin/phpunit tests/Feature/Api/TodoControllerTest.php
```

> 以下のような結果になるとおもいます

```shell

1) Tests\Feature\Api\TodoControllerTest::Todoの新規作成
Symfony\Component\Routing\Exception\RouteNotFoundException: Route [api.todo.create] not defined.
```

- なぜこのようになっているのか？
  - API の実装を行なっていないからです。
  - エンドポイントが存在していないのにリクエストをおこなっているので当たり前ですね。

### Controller の作成を行います。

```shell
php artisan make:controller Api/TodoController
```

`app/Http/Controllers/Api`以下に TodoController.php が作成されていると思います。

こちらを以下のように編集します。

```php
<?php
declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Models\Todo;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TodoController extends Controller
{
    private Todo $todo;

    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todo->fill($validated)->save();

        return ['message' => 'ok'];
    }

}
```

内容は以前作成した Controller の内容とほぼ一緒ですね。今回は、API なので view を返却する必要性がないのでレスポンスはリクエストが成功したことがわかるメッセージに変えてます。

> 要件によっては作成したデータを返却する場合もあります。

- 次にエンドポイントの追加を行います。

> routes/api.php

```php
<?php

use App\Http\Controllers\Api\TodoController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('todo/create', [TodoController::class, 'store'])->name('api.todo.create');

```

以前 web.php をいじった際は、resource というのを使用しましたが今回は、使用しません。API の実装時は、resource で作成されるメソッドをそのまま使うということはほぼないからです。

- 本来の TDD だと少しずつエンドポイントの実装を行なっていくのですが今回は、一気に行ったのでここまでの実装でテストを実行してみましょう

```shell
./vendor/bin/phpunit tests/Feature/Api/TodoControllerTest.php
```

> 実行結果

```shell
PHPUnit 9.5.13 by Sebastian Bergmann and contributors.

.                                                                   1 / 1 (100%)

Time: 00:00.285, Memory: 24.00 MB

OK (1 test, 4 assertions)
```

- 問題なく pass してますね。

### テストの説明

- assert\*\*\*

  - 原則として、第一引数に期待する値、第二引数に結果を入れます。
  - 使用するアサーションで結果の検証を行なっています。

- 今回は、4 個の検証を行なっています。

1. assertOk

HTTP Status が正しいか検証しています。他のメソッドと異なり、Laravel が用意しているものになります。
多くのテストで使用しますので覚えておきましょう

2. assertCount

配列の数をカウントしています。今回 DB は、空の状態ですので 1 件しかできないはずです。なので全件取得しても 1 件しか取得されないはずです。

3. assertEquals

このアサーションをよく使用されますが注意が必要です。比較する上でこちらは`==`を使用しての比較なので型までは原則としてみていない緩い比較になってます。第一引数と第二引数が一致した値になっているかどうかを検証しています

※個人的には assertSame のが好きです

## 課題

- これまでは、カリキュラムに書かれている内容をなぞるように実装をしてきたとおもいます。今回の API 実装とテストの実装に関しては、自ら考え、実装することを課題とします。

#### やること

- 新規作成が失敗となるテストを実装してください。
  - 新規作成の場合の失敗時というのは、POST される内容が不十分だった場合です
  - ※新規作成のテストとは別で作成という意味になります。
- 更新処理、詳細取得、削除処理これらの API を作成しテストも作成してもらいます。
  - API に関しては、以前実装した部分を流用していいです。
  - 新規作成と同様に失敗時のテストも実装してください
- commit の単位
  - commit タイミングはエンドポイント単位で行なってください
  - これまでは任意のタイミングでの commit でしたが今回の課題は以下のタイミングを必ず守ってください
    - テストを先に実装し終えたタイミングで commit する
    - Controller の記述を行ない commit する
      - テストが通っていなくてもいいです
    - テストが通る状態になったら commit する
      - 必ずテストが通るようにしてください
- テストすること
  - 新規作成での検証を参考にしそれぞれのメソッドに適切と思われる検証を実装してください
    - それぞれのメソッドとは詳細取得、更新、削除のメソッドを指しています

* 作成できたら push して共有してください

* また今回、一覧の API、テストは、実装しなくてもいいです。

## 補足

- 仕様、実装に関しての質問は受け付けておりますので質問等ありましたらお願いします。
