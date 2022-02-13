# View の分割を行う

前回までは、一つの file にごそっと書いたままでしたがこの章では、分割を行います。
この章での目的は、機能毎に View の file を用意し、共通部分は、テンプレートを使用し画面の大枠の完成を目指します。

## ページごとに file を作成します。

今回必要となるページは、新規作成・更新/編集・一覧画面の以上 3 ページになると思います。
前回の章で作成したのは、一覧画面に使える html を作成しました。なので前回作成したものを流用し作成していきたいと思います。

Mac の方はコマンドで file を作成しましょう。

```shell
touch resources/views/todo/index.blade.php
```

作成された file をエディタで開き記述を行います。この際、前回作成した file から流用しますがそれに伴い前回の file の変更も行います。

編集 file `resources/views/todo/index.blade.php`

```html
<div class="container">
  <h2 class="page-header">ToDo一覧</h2>

  <p class="pull-right">
    <a class="btn btn-success" href="/todo/create">追加</a>
  </p>

  <table class="table table-hover todo-table">
    <thead>
      <tr>
        <th>やること</th>
        <th>作成日時</th>
        <th>更新日時</th>
        <th></th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>静的なTodoです</td>
        <td>2017-01-01 00:00:00</td>
        <td>2017-01-10 00:00:00</td>
        <td><a class="btn btn-info" href="">編集</a></td>
        <td><button class="btn btn-danger" type="submit">削除</button></td>
      </tr>
    </tbody>
  </table>
</div>
```

ただしこのままでは、使用ができません。
なので使用できるように記述を加えていきます。初めてみる単語などが出てきますが file の編集が終わり次第説明をします。

その前にテンプレートの方にも手を加えます。

対象 file `resources/views/layouts/app.blade.php` です。

```html
<!DOCTYPE html>
<html lang="ja">
  <head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Laravel</title>

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet" />

    <!-- Fonts -->
    <link
      href="//fonts.googleapis.com/css?family=Roboto:400,300"
      rel="stylesheet"
      type="text/css"
    />

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <style>
      .todo-table td {
        vertical-align: middle !important;
      }
    </style>
  </head>
  <body>
    <div class="container">
      @yield('content')
      <!-- 追記 -->
    </div>
    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
  </body>
</html>
```

対象 file `resources/views/todo/index.blade.php` です。

```html
@extends('layouts.app')
<!-- 追記 -->
@section('content')
<!-- 追記 -->

<h2 class="page-header">ToDo一覧</h2>

<p class="pull-right">
  <a class="btn btn-success" href="/todo/create">追加</a>
</p>

<table class="table table-hover todo-table">
  <thead>
    <tr>
      <th>やること</th>
      <th>作成日時</th>
      <th>更新日時</th>
      <th></th>
      <th></th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>静的なTodoです</td>
      <td>2017-01-01 00:00:00</td>
      <td>2017-01-10 00:00:00</td>
      <td><a class="btn btn-info" href="">編集</a></td>
      <td><button class="btn btn-danger" type="submit">削除</button></td>
    </tr>
  </tbody>
</table>

@endsection
<!-- 追記 -->
```

追記箇所は、3 箇所となります。

- 以下に出てくる用語の共通点
  - 外部読み込みが可能です。要するに別の file を読み込み使用することが可能ということです。
- @yield
  - 継承は、できずデフォルト値の設定が可能です。なので今回は、テンプレートになる file のみに使用し引数として中には、`'content'`と記入することによって次に説明する section で宣言された名称の file を読み込み表示することが可能です。
- @section
  - 継承ができ、デフォルト値の設定が可能です。おおよその概念としては、yield に近いですが異なる点として親 section というものが定義可能です。この場合親として扱われる section は、通常の section と異なり閉じタグが `@show` となります。※今回は使用しません。
  - 基本的な使い方としては、ページごとに表示分ける際などに各ページの始まりと終わりに`@section('キーワード')` という書き方ではじめ、終わりに `@endsection` または、 `@stop` と書く必要があります。
  - `@yield('キーワード')` で該当するキーワードの `@section('キーワード')` のキーワードが一致する file が読み込まれ表示されます。
- @include

  - 今回は、使用しませんがこちらは、継承はできずまたデフォルト値の設定ができません。ただし変数の受け渡しが可能です。
  - 用途としては、エラー文言の出力など形式、見た目などが同じで値によって表示非表示を行いたい場合に変数を渡しその変数の値を元に表示を行う場合などが想定されます。
  - もちろん他にも用途は、あげられると思います。

- 外部 file の読み込み以外にも多くのメソッドが存在します。
  - `foreach` と `if文` の性質をもつ `@forelse` など便利なメソッドも多くあるので一度公式サイトなどを見てみることをオススメします。

view の実装が一旦終わりました。このままブラウザで確認したいのですが現状だとエラーが出ると思います。
その理由は、実装した view を表示するための記述を Controller に行なっていないためです。なので表示させるために Controller の編集を行いましょう。

編集 file `app/Http/Controllers/TodoController.php` です

```php
// 省略
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('todo.index');  // 追記
    }
// 以下省略
```

これで問題なく表示されます。では早速ブラウザで確認しましょう。

## 同じ方法で他のページの作成も行いましょう。

作成するページが残り 2 ページです。まずは、記述するための file を用意しましょう。

```shell
touch resources/views/todo/create.blade.php resources/views/todo/edit.blade.php
```

編集 file `resources/views/todo/create.blade.php` です。

```html
@extends('layouts.app') @section('content')

<h2 class="page-header">ToDo作成</h2>
<form>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="ToDo内容" />
  </div>
  <button type="submit" class="btn btn-success pull-right">追加</button>
</form>

@endsection
```

編集 file `resources/views/todo/edit.blade.php` です。

```html
@extends('layouts.app') @section('content')

<h2 class="page-header">ToDo編集</h2>
<form>
  <div class="form-group">
    <input type="text" class="form-control" placeholder="ToDo内容" />
  </div>
  <button type="submit" class="btn btn-success pull-right">更新</button>
</form>

@endsection
```

これで view の file のベースが出来上がりました。

## View で使用する Form タグ変更する

Form タグは、Form タグのままでもいいのですがせっかくなのでより便利に扱えるようにしたいと思います。そのためのライブラリが存在してますので導入を行います。

`laravelcollective/html` というものを使用します。
これを `composer` 経由で install します。

```shell
composer require laravelcollective/html
```

実行した後しばらくしたら install が始まります。

終わったら各 view file に記載ある Form タグを変えていきます。この際、Form タグ内で使用されている Input タグも書き換えていきます。
[リファレンス](https://laravelcollective.com/docs/master/html#opening-a-form)

使い方は、リンクに記載ある方法を用いて行います。
view の file を編集する前に導入したものを使用可能にするために設定を行います。

編集 file `config/app.php` です。

```php
  'providers' => [
    // ...
    Collective\Html\HtmlServiceProvider::class, // 追記
    // ...
  ],
  'aliases' => [
    // ...
      'Form' => Collective\Html\FormFacade::class,  // 追記
      'Html' => Collective\Html\HtmlFacade::class,  // 追記
    // ...
  ],
```

上記のように変更しましょう。
これで使えるようになります。ただし `config` 以下の file を変更した場合は、`サーバの再起動` を行いましょう。
理由：サーバが立ち上がった時に一度だけ読み込まれる設定 file となります。

では、実際に書いていきましょう。その後に解説を入れたいと思います。

編集 file `resources/views/todo/create.blade.php`

```html
<!-- 省略 -->
{!! Form::open(['route' => 'todo.store']) !!}
<div class="form-group">
  {!! Form::input('text', 'title', null, ['required', 'class' => 'form-control',
  'placeholder' => 'ToDo内容']) !!}
</div>
<button type="submit" class="btn btn-success pull-right">追加</button>
{!! Form::close() !!}
<!-- 省略 -->
```

編集 file `resources/views/todo/edit.blade.php`

```html
<!-- 省略 -->
{!! Form::open(['route' => ['todo.update', $todo->id], 'method' => 'PUT']) !!}
<div class="form-group">
  {!! Form::input('text', 'title', $todo->title, ['required', 'class' =>
  'form-control']) !!}
</div>
<button type="submit" class="btn btn-success pull-right">更新</button>
{!! Form::close() !!}
<!-- 省略 -->
```

上記のような file になったら問題ありません。

では、記述した内容に関して解説を加えていきます。

まず、最初に基本となる `Form` タグになるものから

```html
{!! Form::open() !!} {!! Form::close() !!}
```

これを書くことによって Form タグを開始し終了を意味してます。
注目は、 `open` 以下です。

- `route` ：これは、次の章で細かく追っておきたい思います。ただしとても重要な箇所となり、Laravel の処理フローに大きく関わってきます。

- `method` ：書く場合と書かない場合があります。書かれている場合に関しては、 `http method` で検索して頂けたらと思います。書いてない場合に関してのみ説明します。端的に `http method の POST` です。基本的に System というものは、数種類の method を使用しますがその多くが `GET` or `POST` です。そして `Form` タグを使用する場合の多くが `POST` です。使用頻度が高い場合は、わざわざ明記するのは手間です。なので明記しなくても使えるようにしてあるのです。
