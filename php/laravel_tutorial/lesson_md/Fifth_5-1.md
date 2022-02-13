# リレーションとは？

- 今回使用しているDBがMySQLというDBです。これはRDBMSの一種です。では、RDBMSとはどういったものでしょうか？
  - 簡単に説明するとDBに存在しているテーブル同士を主キーを元に関連付けさも一つのテーブルのように扱うことを可能にするものです。
  - これはDB設計にも大きく関わってくることですがあらかじめ割り振った一意な物を割り振ることによって実現が可能です。(主にID カラムを用意したりする)


## リレーションを活用しデータの関連付けを行います。

- どのようなことをするのかというとログインしているユーザーに投稿されたデータを紐付け投稿したユーザーのみにしか表示がされないようにします。
- また技術的な話で本来ですとアソシエーションというものを使用し`ORM` の特性を使いより簡単にデータの取得を目指す手法は、とりません。

まず最初に`migration file` の作成をします。

```shell
php artisan make:migrate add_user_id_to_todos_table --table=todos
```

作成できたfileに対して追記を行います。

```php
<?php

use Illuminate\Support\Facades\Schema;
// 省略
    public function up()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');  // 追記
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('todos', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');  // 追記
        });
    }
}
```

何をどこに追加しているかというと`todos table` に対して`user_id` というカラムを追加してます。

todoを作成する際にログインしているユーザー固有の`id` をtodos tableに格納しtodos tableとusers tableを関連づけます。
なぜ`unsignedBigInteger`なのか？これは、`users`というtableの`id`の型に合わせているためです。

  
新たに`migration file` を追加した際は、必ずそれを`DB` へ反映させるために`php artisan migrate` を実行します。このコマンドを実行することによって`DB` への反映が完了します。

DB関連の処理に関しては、以上で問題なく動作をさせることができるのでDBに保存する`Controller` と`Model` の処理に移行します。


まず最初に保存する際に保存対象として許可するために`Model` に対して以下のように追記を行います。

```php
<?php
//  省略
    /**
    * @var array
    */
    protected $fillable = [
        'title',
        'user_id'  // 追記
    ];
    // ここから
    /**
    * @param int $id
    * @return Todo
    */
    public function getAll($id): Todo
    {
        return $this->where('user_id', $id)->get();
    }
    // ここまで追記
}
```

これを書くことで保存することが可能になり、またユーザーに紐づいたデータ取得のための記述も終えました。

次に保存と編集の処理を追加していきます。


```php
<?php
decalre(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;
use Auth;  // 追記

// 省略
    /**
    * index function
    * @return Response
    */
    public function index(): Response
    {
        $todos = $this->todo->getAll(Auth::id());  // 追記
        return view('todo.index', compact('todos'));
    }
    // 省略
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): Response
    {
        $input = $request->all();
        $input['user_id'] = Auth::id();  // 追記
        $this->todo->fill($input)->save();
        return redirect()->to('todo');
    }
    // 省略
}
```


- `use Auth;`

ログインしているユーザーを`Auth::id()` という形で取得を可能にするために追記しました。


他の箇所に関しては、解説を加えるほどのことをしているわけではないので省略します。


この状態で動作確認をしましょう。

ログインしたユーザーが投稿した内容が表示され、また別のユーザーでログインした際は、他者が投稿した内容が見えないようになっていることが確認できたでしょうか？

