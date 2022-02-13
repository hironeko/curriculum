# Viewを作って行く

## 前章で必要となるものは、用意しました
- 次にブラウザで表示するために使用するhtml fileの作成を行います。
- resources/views/todo/index.blade.phpを作成します。
- ただしその前にLaravelにおけるViewについて知る必要があります。


## Bladeというもの
- 種類：テンプレートエンジンと呼ばれるもの
- 他のテンプレートエンジンと異なる点としてPHPを直接記述することを許容している
- 利点：テンプレートの継承とセクション

ざっくりあげるとこのような特徴を持っています。

では、実際にどのようにしようするのか？

Webページは、多くの共通する記述が存在します。
例えば、`<head>`タグ内の記述、`<header>`タグなどを利用したhederと呼ばれる箇所、`<footer>`タグを利用したfooterと呼ばれる箇所など多くの共通する記述が存在します。
それらを新たにページを作成するたびに記述しているのは、エンジニアとして怠惰です。
共通する処理、記述などは、基本的に一箇所にまとめるべきです。
なので今回作成するLaravelでも同様に共通するであろう箇所は、全て共通かつ大元のテンプレートファイルとして独立させ、また各ページのviewのファイルを読み込むように設定してあげます。

## fileを置くディレクトリ作成

```shell
mkdir resources/views/layouts resources/views/todo
```
同時に2個のディレクトリを作成しました。
1個は、テンプレートになるfileが格納されるディレクトリ
1個は、各ページのfileが格納されるディレクトリとなってます。

早速雛形となるfileの作成を行います。

Macの方は、コマンドでfileを作成しましょう。
```shell
touch resources/views/layouts/app.blade.php
```

エディタを開き早速中身を書いていきます。

```html
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laravel</title>

    <!-- Fonts -->
    <link href='//fonts.googleapis.com/css?family=Roboto:400,300' rel='stylesheet' type='text/css'>
    <style>
        .todo-table td {
            vertical-align: middle !important;
        }
    </style>
</head>
<body>

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
    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>

</body>
</html>
```

上記内容をそのままfileに書きましょう。

この状態でブラウザではどのように見えているのかを確認したいと思います。
なのでLaravelのローカルサーバを立ち上げましょう。

```shell
php artisan serve
```

では、ブラウザで*127.0.0.1:8000/todo*と入力し確認をしましょう。
