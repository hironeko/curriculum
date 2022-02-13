### 頻出コマンド一覧

- ここで紹介するコマンドとは？
  - Laravelの開発には、欠かせない**artisan**コマンドを紹介していきます
  - 以下で紹介するコマンドを使用してLaravelのファイルの作成やサーバーの立ち上げを行ったりします
  - artisanコマンドは、とても重要なのでわからなくなったらこの章に戻って確認しましょう


- 実際にどんなものがあるか確認をしてみます

```shell
php artisan list
```
  - 上記コマンドを実行するとartisan コマンドの一覧が表示されます
  - たくさん表示されてこれ全部覚えるの？って思うかもしれませんが実際によく使われるのは、そこまで多くないので安心してください


```shell
php artisan make:controller Controller名   # 新しくController Classの作成を行います
php artisan make:model Model名             # 新しくModel Classの作成を行います
php artisan make:seeder Table名TableSedder # 新しくSeeder Classの作成を行います

php artisan make:migration create_table名_table # 新しくMigration fileを作成します
php artisan migrate                             # DBに対してtable情報を反映させる
php artisan migrate:refresh                     # DBに対してmigrateしたのを全部rollbackし再度migrateを行う
php artisan migrate:reset                       # DBに対してmigrateしたものをrollbackする 
php aritsan migrate:rollback                    # 一回ずつrollbackする
php artisan db:seed                             # DBに対してあらかじめ入れたいデータがある場合に実行する
php artisan migrate --seed                      # DBに対してtable情報を反映させつつあらかじめDBにいれたいデータを入れたい場合に使用する
php artisan make:test                           # PHPUnitのテストを作成する時に使用するまたオプションで --unitとすればUnit以下に作成される

# URIなどの一覧を表示してくれる
php artisan route:list

# git cloneなどを行ってprojectを行う場合に必要となる
php artisan key:generate

# Laravel の 認証機能のひな型を作成してくれる
php artisan make:auth

```
