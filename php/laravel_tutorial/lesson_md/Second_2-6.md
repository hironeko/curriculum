# View を仕上げていく

`Controller` の記述が完了し View に渡すための処理やデータの保存などの処理は、実装し終えました。
次に最後の段階として View で`Controller` から渡された値の出力を行うための処理などを実装していきたいと思います。

最初に編集するのは、 `resouces/views/todo/index.blade.php` となります。

```html
<!-- 省略 -->
<tbody>
  @foreach($todos as $todo)
  <tr>
    <td class="border px-4 py-2">{{ $todo->title }}</td>
    <td class="border px-4 py-2">{{ $todo->content }}</td>
    <td class="border px-4 py-2">{{ $todo->created_at }}</td>
    <td class="border px-4 py-2">{{ $todo->updated_at }}</td>
    <td class="border px-4 py-2">
      <a
        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
        href="{{ route('todo.edit', $todo->id) }}"
      >
        編集
      </a>
    </td>
    {!! Form::open(['method' => 'delete', 'route' => ['todo.destroy',
    $todo->id]]) !!}
    <td class="border px-4 py-2">
      <button
        class="bg-white hover:bg-gray-100 text-gray-800 font-semibold py-2 px-4 border border-gray-400 rounded shadow"
      >
        削除
      </button>
    </td>
    {!! Form::close() !!}
  </tr>
  @endforeach
</tbody>
<!-- 省略-->
```

該当 file を上記のように編集を終えたら完了です。

- `blade` ：`blade` テンプレートに `php` のロジックを埋め込む方法として今回の`foreach` を例に解説します。

```php
@foreach($array as $variable)
    // 処理
@endforeach
```

この書き方は、通常の`foreach` と同じ様に使用が可能です。
ただし、View に渡ってきた値というのは、`Object` で渡ってきます。
`$todo->カラム` という書き方で値を取得できます。

- 編集ボタンの箇所の`href`に書かれている

```php
{{ route('todo.edit', $todo->id) }}
```

次にこの書き方ですがブラウザで`developer tools` を使用し実際に表示されているソースをみた方が早いと思います。
実際に表示されているソースは、以下になっているはずです。

`http://127.0.0.1:8000/todo/1/edit` と`href=""` の中身が`URL` になっていると思います。

上記のような`blade` の記法と`Laravel` のメソッドを使用すると`URL` が生成された`a` タグが完成します。
この`route`メソッドの書き方は、多くの場面で使用される書き方なのでしっかり覚えましょう。
