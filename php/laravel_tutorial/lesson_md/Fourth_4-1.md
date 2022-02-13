# ログイン機能の実装

- 今回は、作成した`Todo` アプリケーションに対してログイン機能を付け加えたいと思います。
- 管理画面の作成は、一旦行わず進めます。
- また最終的には、ログインしているユーザーによって表示される`Todo` を変えるといった紐付ける実装も加えていきます。
  - ここで学ぶのは、データを保存しているそれぞれの`table` 同士を関連付けてあげる`リレーション` という機能を実装します。

## Multi Authを使用し導入します。

- Laravelには、便利な機能がいくつか存在し今回使用する`Multi Auth` もその一つです。
- どんな機能なのか？主だった機能を列挙します。
  - すでに`database/migrations` 以下に存在している`migration file` を使用しユーザー登録の機能
  - またその登録した情報を元にログインの可否を実現
  - ログイン後は、ログアウトするまで実装済みの`Todo` アプリケーションを操作することが可能とする機能
  

## Laravelに`Multi Auth` の雛形をコマンドで作成します。

- 多くのフレームワークでは、コマンド操作によって雛形fileの作成を行う場面が多く見受けられますので慣れましょう。

```shell
php artisan make:auth

The [layouts/app.blade.php] view already exists. Do you want to replace it? (yes/no) [no] # ここでyes と書いてください。
> yes

Authentication scaffolding generated successfuly.
```

コマンドを実行した結果上記のような表示がされたら問題ありません。
では次にこのコマンドで何が作成されたかの確認を行いたいと思います。

## 作成されたfileの確認

- 追加点file
`HomeController.php` も追加されているのが確認できますでしょうか？


- 既存fileへの追加点
`routes/web.php` になります。

```php
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
```
こちらも問題なく確認できましたでしょうか？

## 実際にどう変更が加わったのかの確認をします

まず`php artisan serve` とコマンドを実行しましょう

実行したら`http://127.0.0.1:8000/home` とブラウザに入れアクセスしましょう。

ログイン画面が表示されましたでしょうか？

また上部に`Login` と`Register` という文言が下記画像のように確認できましたでしょうか？

![画像](https://github.com/hironeko/for_laravel_beginner/blob/master/images/login_view.png)

ではURLの`home` の部分を`todo` に変更してみましょう。

するとどうでしょうか？導入前と変わらずに表示されたのが確認できましたでしょうか？
現状は、ページが追加された程度(機能も追加されてますが)だということがわかりましたでしょうか？

ここまでで`Multi Auth` の機能の導入に関しては、終わります。


次章以降は、既存ページへの機能反映と同時に必要な箇所に関しては、テストも書いていきます。
