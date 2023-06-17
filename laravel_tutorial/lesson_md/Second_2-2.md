# Controller の作成

アプリケーションの要となる*Controller*が必要なので作成を行います。以下のコマンドを実行してください。

```shell
php artisan make:controller TodoController --resource
```

作成が完了したら*app/Http/Controllers*内に存在する*TodoController.php*を確認してください。

```php
<?php
declare(strict_types=1); // ここは追記

namespace App\Http\Controllers;

use Illuminate\Http\Request;

/**
* TodoController class
*/
class TodoController extends Controller
{
    /**
    * constructor function
    */
    public function __construct()
    {
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(int $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, int $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(int $id)
    {
        //
    }
}
```

確認が完了したら上記のようになるように手を加えましょう。
色々なメソッドの記載があります。
php では、class に属する関数をメソッドと呼びます。では、プログラミングの世界の慣習として*Hello word*という文言を出力させてみましょう。

```php
<?php
// 省略
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return "Hello world!!";
    }
// 以下省略
```

## 表示させるためにルーティングという機能に手を加えます。

Controller の編集が終わりましたら次にこの Controller を利用するためにルーティング機能に手を加えます。
開く file は、*route*フォルダ以下にある。*web.php*を開きます。中身は、以下のようになってます。

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TodoController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::resource('todo', TodoController::class); // 追記
```

では、作成したものが反映されているか確認するために Laravel のローカルサーバを立ち上げましょう。

```shell
php artisan serve
```

ブラウザに*http://127.0.0.1:8000/todo* と入れてアクセスしてください。
画面に"Hello world!!"と表示されたなら問題ありません。
これで controller と route file の関連性がイメージできたかと思います。
