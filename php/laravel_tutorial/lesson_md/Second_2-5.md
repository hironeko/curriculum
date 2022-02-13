# Controller を仕上げていく

- View に関しては、概ね完了してます。それに対応した `Controller` の記述を書いていきます。
- 現状として書かれているのは、`index` メソッドのみかと思います。なので `create` 、 `edit` 、 `destroy` に付随するメソッドを含め記載を行い、DB への値の保存などの操作を行えるようにします。

まず最初に `DB` への操作が行えるように `Model` の file を作成します

```shell
php artisan make:model Todo
```

`app/` 以下に file が作成されたと思うので編集を行います。

```php
<?php
declare(strict_types=1);

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
* Todo class
*/
class Todo extends Model
{
    /**
    * @var array
    */
    protected $fillable = ['title']; // 追記
}
```

この作成した file を `Controller` 側で使用できるようにします。この `Model` を継承した file というのが `DB` への操作を可能にする file となります。

ここで記述した`fillable` というのは、いわゆるホワイトリスト呼ばれたりするものになります。

編集 file `app/Http/Controllers/TodoController.php` を編集します。

```php
<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Todo;  // 追記

/**
* TodoController class
*/
class TodoController extends Controller
{
    // ここから追記
    /**
    * @var Todo
    */
    private $todo;

    /**
    * contructor function
    * @param Todo $instanceClass
    */
    public function __construct(Todo $instanceClass)
    {
        $this->todo = $instanceClass;
    }
    // ここまで追記

// 以下省略
```

これだけで `model` の使用が可能になります。順を追って説明していきましょう。

- `use App\Todo;` ： 最初のうちは、`require()` メソッドに近いイメージをしていただけたらいいと思います。この記述をすることによって `app/Todo.php` を使用することができます。 `use` は、日本語で使うという意味、それ以下に書かれているのは、file までのパスが書いてあると思ってください。

- `private $todo;` ：private は、日本語的にクローズドなイメージを抱くかと思います。この場合の用途としてもまさにそのままでこれは、 `Class` 内でしか使用しない変数言い換えれば `このClass` 以外からのアクセスを避けたい変数の定義のさいに使用されます。
  - 他にも、`public` `protected` と種類がありますので比べてみてみるのもいいかと思います。
  - またこのようなクラスにて使用する変数をメンバー変数と呼んだりします。

### Laravel の魅力の一つ Dependency Injection について

- `__construct` ：このメソッドなのですが `マジックメソッド` と呼ばれたりします。基本的な用途としてこの `Class` が使用される際 = `Classのインスタンス化` が行われた際に設定しておきたい値などを設定するメソッドとして使われます。これを初期化とか初期値設定などと呼んだりします。

  - 引数の箇所で `use` をした `app/Todo` を `Todo` という形で使用してます。実態は、`Todo Class` のインスタンス化です。なのでそれを引数として受け取りかつ変数に代入します。その際注意が必要なのが`private $todo;`とは、別物だということです。
  - メソッドの中で `$this->todo` という風に書いたものに引数で渡ってきたものを代入してます。これは、`private $todo;` へアクセスし代入を行なっていることになります。 `$this->todo` の `$this` が自身(Class)自体をさしているのでその中に存在する `$todo` を意味しています。なぜ `->` という書き方をしたのかというとオブジェクトに対して操作をする際は、必ずこの書き方を行う必要があります。以後覚えておきましょう。

- ここまでの説明が一番 Laravel でしようされている Construct Injection です。よく`依存性の注入` と言われますが、`オブジェクトの挿入` です。なんなら依存してません。あくまで使用する外部のオブジェクトを挿入しているに過ぎません。

※またここで書いた`$instanceClass` は、イメージしやすいように書いていますが、本来は、`$todo`と書かれます。

作成した View を使用するための記述を順次書いていきます。ここまでは、あくまでの下準備に過ぎないので各メソッドに対して追記を行なっていきます。

## `index` メソッドを編集

```php
// 上記省略
    /**
    * index function
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $todos = $this->todo->all();  // 追記
        return view('todo.index', compact('todos'));  // 編集
    }
// 以下省略
```

model は、DB への操作を行うものと言いました。ここで `$this->todo->all()` と書かれておりそれは、 `$todos` へ代入されています。ここでわかるのは、代入された変数は、複数形になっているので 1 個以上の値が入ってきているのだということがわかります。
実際に何をしているか？答えは、簡単で `$this->todo->all()` とすることで DataBase の todos table から全件取得してます。つまり `SELECT * FORM Todos;` という SQL 文が発行されてます。

どの FW でも大抵は、DB 操作を楽するためのツールをあらかじめ導入しておりそれを用いて簡潔にかつプログラムで DB への操作を実現してます。このようなものを `ORM` と言います。
DB からの返却データは、Object としてデータが返却されます。この返却された Object を View に渡し取得した値を表示したりします。そのためには、取得したデータを View に渡さなければなりません。そのための記述として `return view('todo.index', compact('todos'));` という書き方をしています。この `compact` というのは、日本語でぎっしり詰めてという意味になるので `compact()` に view 側に渡したい変数を記述してあげます。そうすることによって view 側で変数を使用することが可能となります。

後ほど View の方を再度修正したいと思います。

※基本的に Controller は、view メソッドに画面で使用する物を引数として渡し返却してるに過ぎません。なので間違えがないようにしてもらいたいのは、Controller は、画面を描画しているわけではないと言うことを注意しましょう。

## `Create` メソッドを編集

処理という処理はないですが View の表示が行えるように編集を行います。

```php
// 省略
    /**
    * create function
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return view('todo.create');  // 追記
    }
// 以下省略
```

View file の指定を行います。Create メソッドに関しては、以上となります。

## `Store` メソッドを編集

このメソッドがどんな役割をするメソッドかどうかからの説明が必要になると思います。まず最初に `Store` というのは、英語では多義語となってます。今回の使い道としては、格納というイメージでの使われ方をしていると思います。

格納という意味を知って即座に DB を連想した方は、素晴らしいです。DB にデータを格納するための処理を行うメソッドになっています。

```php
// 省略
    /**
    * store function
    * @param \Illuminate\Http\Request $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        // 以下 returnまで追記
        $input = $request->all();
        $this->todo->fill($input)->save();
        return redirect()->to('todo');
    }
// 以下省略
```

見たことないものばかりかと思います。

- `Request $request` ：file の上部に記載ある `ues Illuminate\Http\Request;` の `Request` クラスを使用してます。これを使うことで何が実現できているかというと`Form` タグで送信した `POST` 情報を取得することを実現してます。

- `$this->todo->fill($input)->save();` ：fill は、渡された引数をオブジェクトに設定できるかどうかを確認してくれます。 `save()` でデータの保存を行います。

※fillable に書かれていないものは、無視します。

最後に保存完了後は、一覧画面に遷移させる記述を行なっています。

## `Edit` メソッドを編集

- このメソッドを通して Todo の更新を行います。

```php
// 省略
    /**
    * edit function
    * @param int $id
    * @return Response
    */
    public function edit(int $id)
    {
        $todo = $this->todo->find($id);  // 追記
        return view('todo.edit', compact('todo'));  // 追記
    }
// 以下省略
```

今回は、あまり説明するような箇所はないのですが 2 箇所だけ説明をいたします。

- `edit(int $id)` ：これは、`URL` のパラメータの取得のための記述になります。`php artisan route:list` で `route` の一覧を確認してみてください。そうすると `todo/{todo}/edit` となっているはずです。この `{todo}` の箇所がパラメータ扱いになります。view 側で引数で渡すことによって画面遷移用の URL が作成できるようになっています。この Controller の記述が終わったら再度 View の仕上げを行います。その際に再度説明を交えます。

- `$this->todo->find($id);` ：パラメータで渡ってきた値を元に DB へ問い合わせを行なっています。これにより指定のデータのみを取得することが可能になり、編集画面に一覧で選択した Title のものを表示し更新を可能にします。

## `Update` メソッドを編集

- このメソッドが更新のメイン処理になります。

```php
// 省略
    /**
    * update function
    * @param \Illuminate\Http\Request $request
    * @param int $id
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, int $id)
    {
        $input = $request->all();
        $this->todo->find($id)->fill($input)->save();
        return redirect()->to('todo');
    }
// 以下省略
```

内容としては、`edit` メソッドの箇所と `store` メソッドの箇所を組み合わせたものになります。
処理としては、`find` で検索し、`fill` で設定の確認(検証)し、保存という流れです。

## `Destroy` メソッドを編集

- 今回は、物理削除にしてます。なのでこのメソッドの処理が行われる際は、DB から完全に削除されます。

```php
    /**
    * destroy function
    * @param int $id
    * @return Response
    */
    public function destroy(int $id)
    {
        $this->todo->find($id)->delete();
        return redirect()->to('todo');
    }
```

今回の内容もメソッド名が変わっただけで基本的な流れは同じです。
処理として、`find` で検索し、`delete` で削除という流れになります。

---

これで`Controller` の実装は、完了となります。
あとは最後に`view` の仕上げをしてこのカリキュラムは、完了となります。
