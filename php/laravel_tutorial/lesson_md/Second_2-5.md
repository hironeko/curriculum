# Controller を仕上げていく

- View に関しては、概ね完了してます。それに対応した `Controller` の記述を書いていきます。
- 現状として実装で触ったのは、`index` メソッドのみかと思います。なので `create` 、 `edit` 、 `destroy` に付随するメソッドを含め記載を行い、DB への値の保存などの操作を行えるようにします。

まず最初に `DB` への操作が行えるように `Model` の file を作成します

```shell
php artisan make:model Todo
```

> また migration の作成と同時に Model の作成も同時に行うコマンドも存在しています。

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
    protected $fillable = ['title', 'content']; // 追記

    /**
     * @var array
     */
    protected $dates = ['created_at', 'updated_at']; // 追記
}
```

この作成した file を `Controller` 側で使用できるようにします。この `Model` を継承した file というのが `DB` への操作を可能にする file となります。

ここで記述した`fillable` というのは、いわゆるホワイトリスト呼ばれたりするものになります。

> 今回は、`fillable`というプロパティを使っていますがこれは、DB に対しての保存可能なカラムのホワイトリストというものになります。ホワイトリストとブラックリストが存在し、現場によっては、ブラックリストの`guarded` というのを使用している場合もあるので併せて覚えておきましょう。またこれらは、共存することができないので必ずどちらか一方を指定する様にします

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
    private Todo $todo;

    /**
    * constructor function
    * @param Todo $todo
    */
    public function __construct(Todo $todo)
    {
        $this->todo = $todo;
    }
    // ここまで追記

// 以下省略
```

これだけで `model` の使用が可能になります。順を追って説明していきましょう。

- `use App\Todo;` ： 最初のうちは、`require()` メソッドに近いイメージをしていただけたらいいと思います。この記述をすることによって `app/Todo.php` を使用することができます。 `use` は、日本語で使うという意味、それ以下に書かれているのは、file までのパスが書いてあると思ってください。

  - 正確には `namespace`です。この namespace に従って認識する様になってます。

- `private Todo $todo;` ：private は、日本語的にクローズドなイメージを抱くかと思います。この場合の用途は、 `Class` 内でしか使用しないプロパティ言い換えれば `このClass` 以外からのアクセスを避けたいプロパティの定義の際に使用します。
  - 他にも、`public` `protected` と種類がありますので併せて覚えておきましょう。
  - またこのようなクラスにて使用するプロパティをメンバー変数と呼んだりします。
  - タイプヒンティングとして private のあとに`Todo`と書いてます。これは、このプロパティに入る型の指定をおこなっています。今回ですと Todo という Object が入ることを意味しており、それ以外は、プロパティに代入できない様になっています。こうすることで予期せぬ代入を防ぐことができます。

### Laravel の魅力の一つ Dependency Injection について

- `__construct` ：このメソッドなのですが `マジックメソッド` と呼ばれたりします。基本的な用途としてこの `Class` が使用される際 = `Classのインスタンス化` が行われた際に設定しておきたい値などを設定するメソッドとして使われます。これを初期化とか初期値設定などと呼んだりします。

  - 引数の箇所で `use` をした `app/Todo` を `Todo` という形で使用してます。実態は、`Todo Class` のインスタンス化された object です。なのでそれを引数として受け取りかつプロパティに代入します。
  - メソッドの中で `$this->todo` という風に書いたものに引数で渡ってきたものを代入してます。これは、`private Todo $todo;` へアクセスし代入を行なっていることになります。 `$this->todo` の `$this` が自身(Class)自体をさしているのでその中に存在する `$todo` を意味しています。なぜ `->` という書き方をしたのかというとオブジェクトに対して操作をする際は、必ずこの書き方を行う必要があります。以後覚えておきましょう。

- ここまでの説明が Construct Injection です。よく`依存性の注入` と言われますが、`オブジェクトの挿入` です。なんなら依存してません。あくまで使用する外部のオブジェクトを挿入しているに過ぎません。

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
        return view('todo.index', ['todos' => $todos]);  // 編集
    }
// 以下省略
```

model は、DB への操作を行うものと言いました。ここで `$this->todo->all()` と書かれておりそれは、 `$todos` へ代入されています。ここでわかるのは、代入された変数は、複数形になっているので 1 個以上の値が入ってきているのだということがわかります。
実際に何をしているか？答えは、簡単で `$this->todo->all()` とすることで DataBase の todos table から全件取得してます。つまり `select * from todos where todos.deleted_at is null;` という SQL 文が発行されます。

どの FW でも大抵は、DB 操作を楽するためのツールをあらかじめ導入しておりそれを用いて簡潔にかつプログラムで DB への操作を実現してます。このようなものを `ORM` と言います。
DB からの返却データは、Object としてデータが返却されます。この返却された Object を View に渡し取得した値を表示したりします。そのためには、取得したデータを View に渡さなければなりません。そのための記述として `return view('todo.index', ['todos' => $todos]);` という書き方をしています。第二引数の配列に view 側に渡したい変数を記述してあげます。そうすることによって view 側で変数を使用することが可能となります。また`key`になる側が view 側で使用する際の変数名になります。

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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);

        $this->todo->fill($input)->save();

        return redirect()->route('todo.index');
    }
// 以下省略
```

見たことないものばかりかと思います。

- `Request $request` ：file の上部に記載ある `ues Illuminate\Http\Request;` の `Request` クラスを意味しています。これを使うことで何が実現できているかというと`Form` タグで送信した `POST` 情報を取得することを実現してます。

  > GET の場合でも使用します。

- `$request->validate([])`
  - Validation をおこなっています。[Validation＆ルールの参考](https://readouble.com/laravel/8.x/ja/validation.html)
  - 第一引数にルールを記述することによって、そのルールに従った Validation チェックが行われます。その結果を配列として返却しています。

* `$this->todo->fill($input)->save();` ：fill を使用することによって`Model`で記述した`fillable`ホワイトリストのプロパティに対してまとめて値の設定を行うことができます。 値を設定された object に対して`save()` メソッドを使いで DB へデータの保存を行います。

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
        $todo = $this->todo->findOrFail($id);  // 追記
        return view('todo.edit', ['todo' => $todo]));  // 追記
    }
// 以下省略
```

今回は、あまり説明するような箇所はないのですが 2 箇所だけ説明をいたします。

- `edit(int $id)` ：これは、`URL` のパラメータの取得のための記述になります。`php artisan route:list` で `route` の一覧を確認してみてください。そうすると `todo/{todo}/edit` となっているはずです。この `{todo}` の箇所がパラメータ扱いになります。view 側で引数で渡すことによって画面遷移用の URL が作成できるようになっています。この Controller の記述が終わったら再度 View の仕上げを行います。その際に再度説明を交えます。

- `$this->todo->findOrFail($id);` ：パラメータで渡ってきた値を元に DB へ問い合わせを行なっています。これにより指定のデータのみを取得することが可能になります。また`find($id)`でも ID 指定での取得は可能ですが今回なぜこのメソッドを使用したかというと`find`は、該当するデータが取得できなかったら`null`を返却するのに対して`findOrFail`は、Exception を throw してくれます。なぜ Exception が throw されるといいのかというとデータが存在していない場合適切なエラーを返す必要があるためです。なら`find`でも null の場合 Exception を throw すればいいじゃないかと思われるかもしれませんが Laravel があらかじめ用意してくれているものを利用した方が効率的であり、無駄な記述をする必要性がないためです。

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
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string', 'max:255']
        ]);
        $this->todo->findOrFail($id)->fill($validated)->save();
        return redirect()->to('todo.index');
    }
// 以下省略
```

内容としては、`edit` メソッドの箇所と `store` メソッドの箇所を組み合わせたものになります。
処理としては、`findOrFail` で検索し、`fill` で設定の設定を行い、保存という流れです。

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
        $this->todo->findOrFail($id)->delete();
        return redirect()->to('todo.index');
    }
```

今回の内容もメソッド名が変わっただけで基本的な流れは同じです。
処理として、`findOrFail` で検索し、`delete` で削除という流れになります。

---

これで`Controller` の実装は、完了となります。
あとは最後に`view` の仕上げをしてこのカリキュラムは、完了となります。
