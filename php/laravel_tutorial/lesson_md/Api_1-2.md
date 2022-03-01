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

- Controller の作成を行います。

```shell
php artisan make:controller Api/TodoController
```

`app/Http/Controllers/Api`以下に TodoController.php が作成されていると思います。

こちらを編集していきます。

```php

```
