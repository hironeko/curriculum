# Viewを仕上げていく

`Controller` の記述が完了しViewに渡すための処理やデータの保存などの処理は、実装し終えました。
次に最後の段階としてViewで`Controller` から渡された値の出力を行うための処理などを実装していきたいと思います。

最初に編集するのは、 `resouces/views/todo/index.blade.php` となります。
```html
<!-- 省略 -->
        <tbody>
            @foreach($todos as $todo)
                <tr>
                    <td>{{{ $todo->title }}}</td>
                    <td>{{{ $todo->created_at }}}</td>
                    <td>{{{ $todo->updated_at }}}</td>
                    <td><a class="btn btn-info" href="{{{ route('todo.edit', $todo->id) }}}">編集</a></td>
                    {!! Form::open(['route'=>['todo.destroy',$todo->id],'method'=>'DELETE']) !!}
                        <td>
                            <button class="btn btn-danger" type="submit">削除</button>
                        </td>
                    {!! Form::close() !!}
                </tr>
            @endforeach
        </tbody>
<!-- 省略-->
```

該当fileを上記のように編集を終えたら完了です。

- `blade` ：`blade` テンプレートに `php` のロジックを埋め込む方法として今回の`foreach` を例に解説します。

```php
@foreach($array as $variable)
    // 処理
@endforeach
```

この書き方は、通常の`foreach` と何ら変わる挙動はせず普段使うように使用が可能です。
ただし、Viewに渡ってきた値というのは、`Object` で渡ってきます。
厳密にいうと`Collection` という今回DBへの操作を簡単にしている`Eloquent` という`ORM` の`Object` になってます。
`$variable->カラム` という書き方で値を取得できます。

```php
{{{ route('todo.edit', $todo->id) }}}
```

次にこの書き方ですがブラウザで`developer tools` を使用し実際に表示されているソースをみた方が早いと思います。
実際に表示されているソースは、以下になっているはずです。

`http://127.0.0.1:8000/todo/1/edit` と`href=""` の中身が`URL` になっていると思います。

 上記のような`blade` の記法と`Laravel` のメソッドを使用すると`URL` が生成された`a` タグが完成します。
この書き方は、多くの場面で使用される書き方なのでしっかり覚えましょう。


