## おまけ

### ページネーションを入れよう

![イメージ](../images/pagination.png)

> TodoController.php

```php
public function index()
{
    $todos = $this->todo->orderby('updated_at', 'desc')->paginate(5);
    return view('todo.index', ['todos' => $todos]);
}
```

> index.blade.php

```html
@endforeach
<!--  以下追加 -->
{{ $todos->links() }}
```

上記に変更し画面で確認していただけるとパージネーションが導入されているとおもいます。
