# 既存ページに機能反映

- ユーザーの作成をしてみる
- 既存のページに機能を反映します

## ユーザー登録を行う

前回同様サーバを立ち上げてブラウザでアクセスを行なってください。
> URLは、同じく`http://127.0.0.1:8000/home` です

上部に存在する`Register` という箇所をクリックし画面遷移をしてください。

必要項目を入力の上`Register` というボタンをクリックしてください。

画面が遷移し下記のように表示されたら問題ありません。

![画像](https://github.com/hironeko/for_laravel_beginner/blob/master/images/login_success.png)

`php artisan migrate` を行なっていないのになぜユーザー登録ができたかというと以前に`Todostable` を作成する際に`php artisan migrate` を実行しすでにDataBaseにTableが存在しているからです。


## 既存のページに機能を反映します。

今の状態ですとURLを変更しただけでアクセスが可能になってしまっているのでそれじゃ折角導入した機能が勿体無いですし意味をなさないので機能するように追記をします。

`app/Http/controllers/TodoController.php` を編集します。

```php
<?php

namespace App\Http\Controllers;
// 省略

    public function __construct(Todo $instanceClass)
    {
        $this->todo = $instanceClass;
        $this->middleware('auth');  // 追記
    }

```

たった1行追加するだけでログインしていない場合は、`Todo` の一覧が表示されなくなりました。

## 便利だが手を加えます。

### 手を加える理由
- 導入の段階で既存の`resource/views/layouts/app.blade.php` を上書きしているので上部にMenuが存在している状況ですが`Todo` 一覧画面のレイアウトが崩れてしまってます。
- 次に`Logout` した際の遷移先がLaravel導入時の画面になってます。
- また`login` した後の遷移先も理想は、Todoの一覧ページなのですがそうではありません。

これらの理由があるためより理想に近い状態に近づけるために手を加えていきます。


### 遷移先の修正

- ここから難しくなっていきますのでしっかりと読んでいきましょう。

編集file `app/Http/Constrollers/Auth/LoginController.php` です。

```php
<?php

namespace App\Http\Controllers\Auth;
// 省略

    protected $redirecTo = '/todo';  // '/home' から変更
```

該当fileの28行目付近に記載ある箇所の修正をします。

これで`Login` した後の遷移先が`/todo` に変更できました。

次に`Logout` した際の遷移先ですが`Auth` 以下のfileには、記載ありません。

まずどのControllerのどのメソッドが使用されているかを確認する必要があります。
確認するには、コマンドを実行します。

```shell
php artisan route:list | grep logout
|     |  POST  | logout       | logout     | App\Http\Controllers\Auth\LoginController@logout   |  web       |
```
上記のように表示がされましたでしょうか？

実際に該当fileを見てみると`logout` というメソッドは、記載ありません。

記載がなくても動いているのを理解するには、`use Illuminate\Foundation\Auth\AuthenticatesUsers;` と`use AuthenticatesUsers;`を把握する必要があります。

- 一つ一つ順を追ってみましょう。

まず`use` という箇所です。これは、`use 名前空間+file名` とすることにより指定fileを使用することが可能になる記述になります。

指定fileというのは、`class` や`trait` で書かれたfileになります。

この機能は、プログラミングをより抽象的に機能単位で分け使う際は、`use` で使えるようにし再利用性を可能にしたオブジェクト指向な設計と言えるでしょう。


では、どういった動きかと言いますと次に着目するべき点は、`use Illuminate\Foundation\Auth\AuthenticatesUsers` の箇所です。

```php
Illuminate\Foundation\Auth\AuthenticatesUsers
// <-ここから    ここまでPATH-><- Class名        ->
```
これは、`use` している`trait` fileまでの`PATH` となってます。

`Illuminate\Foundation\Auth` という箇所が`namespace` という機能を使用して定義されたおり、実態は、`vendor/laravel/framework/src/Illuminate/Fundation/Auth/AuthenticatesUsers.php` にあるfileをこの1行で指定してます。

`use ~` と書くことによって使えるようになると話しましたが利用できているのは、`class` の中に書かれている`use AuthenticatesUsers;` の箇所があるからです。これを書くことによって`class` 内で使用できます。

この`trait file` に書かれているメソッドは、`LoginController.php` にあるメソッドと継承元の`class` のメソッドと同じように使えると思ってください。

ただ注意すべき点があります。どれも同じ優先度で使用できるとPCは理解ができません。なのでそれぞれのfileのメソッドを使用するには、優先順位があらかじめ決められています。

使用している`classのメソッド` > `useしているtraitのメソッド` > `継承しているclassのメソッド` という順に優先度が決められています。

ここは、他のphpフレームワーク等でも使用される箇所になるのでしっかりと把握しましょう。

- ではLogoutメソッドを使用する方法です

先ほど`trait` fileは、`class` のメソッドとして使用できると話しました。さらに`class` 内にて`use AuthenticatesUsers;` と書くことによって使えるようになると話しました。

ここまでは、`trait` の使用方法です。(traitに限った話しではありませんがここでは、traitを中心に話します)

メソッドを使うには、ものすごく簡単で参照先のfileのメソッド名をそのまま使用するだけです。

実際に書いてみましょう。
編集fileは、`app/Http/Controller/Auth/LoginController.php` です。

```php
<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;  // 追記

// 省略
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
// ここから
    /**
     * Log the user out of the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->invalidate();

        return redirect('/login');
    }
// ここまで追記
}
```

上記のように書いたら実際に動かしてみましょう。

一度ブラウザで確認しましょう。

> URLは、`http://127.0.0.1:8000/login` にアクセスしてください

では、一度登録したユーザーでログインをしログアウトをしましょう。

ログアウト後の遷移先は、ログイン画面になりましたでしょうか？


ログイン機能に関しては、以上となります。


次章以降にログインしているユーザーと投稿内容の紐付ける実装を行って行きたいと思います。
