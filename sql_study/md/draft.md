## 下書き用

### B treeについて
[クックパッドの記事](https://techlife.cookpad.com/entry/2017/04/18/092524)

### 前提
- mysql / mariadb

### shell

```shell
mysqldump -uusernam -pyourpassword db_name -d > dump
```
> 構造のみ抜き出す

### 特定のtableのみ抽出
- 特定のprefixがついたtableに対して行う場合

下記コマンドでtable名のみ取得する
```shell
mysql -uusername -ppassword db_name -N -e "show tables like 'prefix%'" > nnnnnn.txt
```
次にtable名のみが記述されているテキストを元にdump fileを作成する
```shell
mysqldump -uusername -ppassword db_name `cat nnnnnn.txt`
```

容量が大きい場合は、gzipで圧縮する
```shell
mysqldump -uusernam -ppassword db_name `cat nnnnnn.txt` | gzip > nnnnn.dump.gz
```


### sqlコマンド

```sql
use databas_name;
show create table table_name\G
show variable;
show variable likes 'etc%';
show tables like 'prefix%';
show master logs;
show index from table_name;
```

```sql
exlpain select * from table_name;
alter table table_name add index index_name(column, column);
alter table table_name drop index index_name;
```

join
```sql
select * from table_first as tf join table_second as ts on tf.filed_id = ts.filed_id where column = nnn; 
```

### パーティショニング
- 任意のものを指定(ユニーク)、もしくは、primary keyで格納するレコードを指定したもので分割格納する
- チューニング方法として有効
  - indexと組み合わせたらさらによき


### index
- 本でいう索引
  - 作成しすぎるのもよくない
  - 必要なものだけを用意すべし
  - 必ずexplainで結果を確認すべし

- 実行計画の結果に関しては、都度確認すべし
  - だめなワードはしっかりと頭に入れとくべき
 
 
### variableなど
- dbの設定状態が確認できる
- 一時的に変更も可能
- クエリキャッシュがされているか、有効かなど確認できる

```sql
show variables like '%query_cache%';
set global name=value;
show processlist;
```
> name > 設定項目, value > 設定値
---
> 実行中ないしsleep状態などのプロセスを一覧で確認可能

### status
- キャッシュクエリの確認
```sql
show status like 'Qcache%';
```

### tableのカラム、型などを確認する

```sql
desc tables_name;
show create table table_name\G
```

### replication user 作成
master
```sql
grant replication slave on *.* to 'repl'@'10.100.10.100/255.255.255.0' indentified by 'password';
show master status;
```

slave
```sql
change master to MASTER_HOST='10.100.10.10', MASTER_USER='repl', MASTER_PASSWORD='password', MASTER_LOG_FILE='show master statusで確認した物', MASTER_LOG_POS=11111;
start slave;
show slave status;
```
- MASTER_LOG_POSのvalue： MASTER_LOG_FILEと同様にmasterで確認したもの
- `show slave status`の結果以下の箇所に注目
```
Slave_IO_Running: Yes
Slave_SQL_Running: Yes
```
- かつerrorが出てないことを確認する


### やはりmysqlのことなら彼に聞け！

[レプリケーション遅延の傾向と対策](http://nippondanji.blogspot.com/2011/12/mysql.html)



