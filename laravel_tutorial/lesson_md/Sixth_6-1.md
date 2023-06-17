## 下書き

- 短い PHP の実装経験の上で必要だと感じた最低限のコーディングルール
- 厳密な型指定
- PHPDocs の記載
- DI とは？
- レイヤードアーキテクチャ
- 1file での責務の明確化
- 1 メソッドの記述量を少なくし可読性をあげる

```php
<?php
declare(strict_types=1);

namespace App\Services;

use App\Models\User;

/**
* UserSettingService class
*/
class UserSettingService
{
    /**
    * constructor
    * @param User $user
    */
    public function __constructor(
        private User $user
    ) {}
}
```

- if 文

```php
$item = null;
if (is_null($item)) {
    return ['message' => 'model not found'];
}
```

## sql

頻出コマンド

```
show databases;
show tables;
show tables like '%nanika%';
show index from table_name;
explain select * from hoge where id = n and created_at = 'yyyy-mm-dd H:i:s';
desc table_name;
```

頻出用語

```
実行計画
index（索引）
```
