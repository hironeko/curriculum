# Route について

前回の章までで view のベースとなるものの作成は終わったものの画面遷移などに関しては、エラーが出るような状況になってます。

ここまでの流れと実装した内容を振り返ります。

- DB の作成とダミーデータとして使うためのデータの生成
- Laravel の動作確認
- Controller への記述
- View のベースの作成、表示の確認、View の分割、Form の変更
  以上を行ってきました。

Form の変更を行った際に見慣れない記述が出たと思います。
特に `route` の箇所です。

まずは、 `php artisan route:list` で Route の一覧表示を行いましょう。
以下のように表示がされましたでしょうか？

```shell
+--------|-----------|------------------|--------------|---------------------------------------------|--------------+
| Domain | Method    | URI              | Name         | Action                                      | Middleware   |
+--------|-----------|------------------|--------------|---------------------------------------------|--------------+
|        | GET|HEAD  | /                |              | Closure                                     | web          |
|        | GET|HEAD  | api/user         |              | Closure                                     | api,auth:api |
|        | GET|HEAD  | todo             | todo.index   | App\Http\Controllers\TodoController@index   | web          |
|        | POST      | todo             | todo.store   | App\Http\Controllers\TodoController@store   | web          |
|        | GET|HEAD  | todo/create      | todo.create  | App\Http\Controllers\TodoController@create  | web          |
|        | GET|HEAD  | todo/{todo}      | todo.show    | App\Http\Controllers\TodoController@show    | web          |
|        | PUT|PATCH | todo/{todo}      | todo.update  | App\Http\Controllers\TodoController@update  | web          |
|        | DELETE    | todo/{todo}      | todo.destroy | App\Http\Controllers\TodoController@destroy | web          |
|        | GET|HEAD  | todo/{todo}/edit | todo.edit    | App\Http\Controllers\TodoController@edit    | web          |
+--------|-----------|------------------|--------------|---------------------------------------------|--------------+
```

見慣れない箇所として `Name` `Action` `Middleware` かと思います。他の `Method` `URI` に関しては、イメージはつくかと思いますが `URI` という単語は、初耳かと思います。
ここでいう`URI` は、ドメイン以下要するに `127.0.0.1:8000` 以下をさしてます。なので URL を `http://127.0.0.1:8000/todo` とすることで画面には、`TodoController` の `index` メソッドで定義した View が表示されます(処理されます)。

- `Name` ：この `URI` を使用する際は、この `Name` を使用すれば対象 `Action` のメソッドが使用されますよ。という意味になります。

  - 使い方としては、以下のような使い方をします。

  ```php
  route('todo.index');
  // routeは、Laravelが用意しているものになります。
  ```

- `Middleware` ：使用している Middleware の記載を行なってます。 `web` は必ずないし基本的には使用されると考えて問題ないです。ただ上から 2 個目のように `api` を別で定義している場合は、このように表示されます。
