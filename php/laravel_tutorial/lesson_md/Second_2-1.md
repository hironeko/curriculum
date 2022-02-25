# データの投入

## DB の設定を行います。

まずは、今回の Laravel 用の*database*を作成します。

## Laravel 側で DB を使用するための記述を行う

- Laravel に今回使用する DB は、XXX だよと DB の接続情報を教えてあげる必要があります
- Laravel のプロジェクト直下に*.env*という file がありますがこれに情報を書いていきます

```shell
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3006
DB_DATABASE=laravel          # 編集
DB_USERNAME=your_name        # 編集 DBを作成した際のUser名
DB_PASSWORD=your_password    # 編集 DBを作成した際のUserのPassword
# 省略
```

上記のように変更することによって Laravel で databaese が使用可能になります。

## 次に table の内容をコードとして書くことをしていきます

今回はマイグレーションというバージョン管理のような機能を使ってテーブルを作成します。

- migration file というものに書き込んでいきます。
- また今回、このタイミングで`Model`, `Factory`と今後必要になる file も一緒に作成してしまいます。

file の作成を行うコマンド

```shell
php artisan make:model Todo -m -f
```

上記コマンドを実行したら*database/migrations/20yy_mm_dd_xxxxxx_create_todos_table.php*という file が作成されていると思います。

> Model は、app/Models ディレクトリ以下。Factory は、database/factories 以下に作成される

この作成された migration file の編集を行い table の構成を書いていきます。

```php
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTodosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('todos', function (Blueprint $table) {
            $table->id();
            $table->text('title');  /* 追加 */
            $table->text('content');  /* 追加 */
            $table->timestamps();
            $table->softDeletes(); /* 追加 */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('todos');
    }
}
```

上記のように編集が終わったら実際に DB に反映をします。

```shell
php artisan migrate
```

このコマンドを実行し下記のような表示がされたら問題なく databases の反映が終わったことになります。

```shell
Migration table created successfully.
Migrating: 2014_10_12_000000_create_users_table
Migrated:  2014_10_12_000000_create_users_table
Migrating: 2014_10_12_100000_create_password_resets_table
Migrated:  2014_10_12_100000_create_password_resets_table
Migrating: 20yy_mm_dd_xxxxxx_create_todos_table
Migrated:  20yy_mm_dd_xxxxxx_create_todos_table
```

- 上記結果が出たら問題なく反映が行われています。
