# 初級


## SELECTを使用してのデータ取得

まずは、もっとも初歩的な`SQL文` を実行しましょう。
```sql
SELECT * FROM payment;
```

ずらーっと表示できたでしょうか？ `16049 row ....` と最後に書かれていると思います。

この`SQL` の意味は、`paymentというtableから全カラムを対象にデータを取得` という意味になります。
なので全カラムのデータが表示されたと思います。

全カラムを対象にデータ取得することは、ないわけではないですが稀です。基本的には、欲しいデータのカラムを指定し取得します。
では、実際に行いましょう。

```sql
SELECT payment_id FROM payment;
```

結果はどうでしょう？`payment_id` カラムのデータのみ取得できましたでしょうか？
もちろんカラムは、以下のように複数指定が可能です。

```sql
SELECT payment_id, amount FROM payment;
```

次は、`payment_id`、 `amount` 両カラムが取得できたのではないでしょうか？
このように取得したいカラムを指定することはよくありますので覚えておきましょう。


## WHERE句を使用してデータ取得

`WHERE句` を使用する用途は、特定の条件にマッチするデータを取得したい場合などで使います。非常に多くの場面で使用されるものになりますのでしっかりと理解をしていきましょう。

まずは、簡単なものから学習していきたいと思います。

```sql
SELECT payment_id, amount FROM payment WHERE payment_id = 1;
```

このsqlを実行した結果1行のみ取得できたと思います。
これをわかりやすく日本語で表すならば`payment tableから選択したpayment_idとamountのデータを取得するにあたってpayment_idが1 のもの(場所)のみ取得` と言えるでしょう。
`WHERE` は、日本語で*どこ*という意味になります。なのでデータの取得する場所を指定していることになります。


## WHERE句の基礎的な使い方

上記のようなWHERE句の使用は一般的ではありますがもう少し条件を継ぎ足したい時などがあるかと思います。
例えばWHERE以下に指定の値の除外記述や複数の値を含む指定、どちらかなしどちらか片方を含むなど多様な使い方が想定されます。
では最初にどちらも含むという条件を指定する方法を見ます。




### A AND B、A OR B

```sql
SELECT last_name FROM customer WHERE store_id = 2 AND first_name = "BARBARA";
```
データが取得できましたでしょうか？

これは、WHERE句以下で`AND` を使うことによって`A かつ B` という条件になってます。なのでこの場合、`WHERE store_id = 2 AND first_name = "BARBARA"` といsql文は、`store_idの値が2 かつ first_nameの値がBARBARA` であるデータという意味になります。よって特定のレコードの`last_name` が取得できました。

では`AND` を`OR` に変更しましょう。
すると先ほどと異なり多くのデータが取得できたのではないでしょうか？


> `AND` 、`OR` に関して、この二つに関しては、`&&` 、`||` に変更が可能です。ただしこれは、それぞれ記号のが文字よりも優先度は、高いことは注意してください。

ここまでは、どちらかを含むもしくは、どちらとも含むというWHERE句の作成を行いました。
次に学ぶのは、特定の値を含むレコードを除くという処理になります。




### `NOT()`

`WHERE NOT()` とすることで否定の条件となります。

```sql
SELECT last_name FROM customer WHERE NOT(store_id = 2 AND first_name = "BARBARA");
```

どのような結果になったでしょうか？
特定の条件を除くというWHERE句は、頻度高く使用することがあるのでしっかりと理解を深めましょう。




### column IN (カンマ区切り)

`column IN()` ()以下のカンマ区切りの値を含むレコードの取得になります。

```sql
SELECT last_name FROM customer WHERE first_name IN("BARBARA", "WILLARD", "PERRY");
```

first_nameというcolumnの値が`()` 内の指定の物のみ取得ができたと思います。
このような方法もよく使用される物になりますので理解しておきましょう。




### 比較演算子

`>=` `!=` etc..... など多くの比較演算子が存在します。これを使用し例文(一部)を記載します。これらもよく使用するものになります。

`>=`
```sql
SELECT amount FROM payment WHERE amount >= 10.99;
```
これは、比較の演算子で`amount` columnの値が`10.99` を含め以上の値を取得するものになります。なので`10.99` も含むデータが取得できたかと思います。


`!=`
```sql
SELECT amount FROM payment WHERE amount != 6.99;
```
これは、否定の演算子で`amount` columnの値が`6.99` を除く値の取得するものになります。のので`6.99` を含むデータ以外が取得できたかと思います。

他にも多くの演算子が存在するのでsql文を変えて実行し確認をしてみましょう。




### `IS NULL` `IS NOT NULL`

`NULL 演算子` と呼ばれるものになります。
対象のフィールドの値が`NULL` なのかそうでないのかの判定を行うためのものになります。前提として対象のcolumnが`NULL` の設定がされている(許容されていること)が前提になっているため必ず値が入るようなcolumnに関しては、使用を行うことはありません。

最初に`NULL` が存在しているデータのみ取得したいと思います。
```sql
SELECT customer_id FROM rental WHERE return_date IS NULL;
```
これにより返却を行なっていない利用者を絞り込んでの取得が可能になりました。例えばこれは、未返却の利用者一覧なので使用できるsql文になると想像ができます。※実際は、もっと複雑なsql文になります。

では、逆に返却済みの利用者のデータを取得する場合です。これは、`NULL` ではない `=` 返却日があるということそれは`NUll` ではないということになります。
```sql
SELECT customer_id FROM rental WHERE return_date IS NOT NULL;
```

これにより、指定columnに値があるものを取得することが可能です。




### BETWEEN A AND B

Aを含め ~ Bを含む データの取得となります。例えば、`11 AND 20` とすると`11を含めた 11,12,13,14,15,16,17,18,19 20を含めた間に該当するレコード` の取得となります。

```sql
SELECT customer_id, last_name FROM customer WHERE customer_id BETWEEN 1 AND 5;
```
このような指定をすることによって範囲条件での取得が可能になります。注意が必要なのが必ず`A AND B` の`Aの値とBの値` を含む範囲条件になる点です。




### column LIKE による曖昧検索

`LIKE` による検索は、少々難易度が上がるかもしれません。というのも前方一致、後方一致、指定値を含む(部分一致)なども可能です。これに`NOT` を加えることによって否定の条件も可能となります。

例えば簡単な曖昧検索は、以下のように書くことが可能です。
```sql
select description from film where description like "%Boy%";
```
これは、`Boy` を`description` 内のどこかしら(部分一致)に含むレコードの取得になります。これにより条件にマッチするレコードのみ取得ができたと思います。

同じ文言を使用し、前方一致と後方一致の取得を行います。

```sql
select description from film where description like "%Boy";
select description from film where description like "Boy%";
```

それぞれ取得結果が異なる結果になったと思います。
検索機能を作成する際によく使用するものになります。



### COUNT()

レコードの数を取得します。

```sql
SELECT COUNT(*) FROM payment;
```
レコード総数が取得できたかと思います。

### DISTINCT

重複を取り除く方法です。

```sql
SELECT DISTINCT customer_id FROM payment;
```
指定のcolumnの重複分をのぞいてレコードを取得します。


- おまけ

`COUNT()` と`DISTINCT` を組み合わせて重複した物を除いたレコードの総数を取得します。

```sql
SELECT COUNT(DISTINCT customer_id) FROM payment;
```

そうしますと`DISTINCT` の箇所で出たrowの数が以下のような形で表示されるかとおもいます。

```shell
+-----------------------------+
| COUNT(DISTINCT customer_id) |
+-----------------------------+
|                         599 |
+-----------------------------+
```

### ORDER BY

- 昇順
指定したcolumnかつ昇順でレコードを取得したい場合に使用します。
例えば、名前の順番に取得したい場合などに使用できます。

```sql
SELECT customer_id, last_name FROM customer ORDER BY last_name;
```

これにより、`last_name` を基準に昇順に取得が可能です。
注意が必要なのは、あくまでこれは、`昇順` であるということです。
`ORDER BY column` でsqlを作成すると昇順ですが明示的に昇順で行うことを書く場合は、`ORDER BY column ASC` となります。
反対に降順で取得したい場合に関しては、次で説明します。


- 降順
先ほどは、昇順に関してのsql文に関して説明を行いました。次は、降順ですが先ほど説明させていただいたsqlに対して追加で`DESC` と追加します。
実際のsqlは以下になります。
```sql
SELECT customer_id, lasr_name FROM customer ORDER BY last_name DESC;
```

これで降順になったのですがわかりづらかったら`last_name` を`customer_id` へと変更してみましょう。

それぞれが`customer_id` 順に降順になったかと思います。

### GROUP BY column レコードをまとめる、AS ~ ~として

使う場面として利用者が何回利用したのか？などと知る際など使用すると思います。

```sql
SELECT customer_id, COUNT(*) AS payment_count FROM payment GROUP BY customer_id ORDER BY payment_count;
```

今までのsql文と異なり少々難易度が上がっているかもしれませんがここまでの学習の応用に近いです。

一個ずつ分解していけば理解は、深まると思いますので一個ずつ確認していきましょう。

`SELECT` のあとは、columnの指定をしています。が`COUNT(*) AS payment_count` と書かれてます。初めて見ますがさほど難しくはありません。まず最初に`COUNT(*)` とすることでレコード単位に数を数えてます。その後ろにある`AS payment_count` これがポイントになります。`AS` この文字を見たらまず`として` という意味で考えましょう。プログラミングを学ぶにあたり多くの場面で使われる文字になります。なので`AS payment_count` というのは、`COUNT(*)` した結果を`payment_count` として使うという意味になります。
`GROUP BY` 次のポイントです。`GROUP BY column` と書き`column によってグルーピング` と考えましょう。なのでここだと`customer_id` によってGROUPとしてまとめるとなります。
`ORDER BY` これは、先ほど学んだことです。`customer_id` でGROUPとしてまとめカウントした昇順となります。

sql文の実行結果は、`payment_count` の数の昇順(カウント数が若い順)となっているはずです。

```
+-------------+---------------+
| customer_id | payment_count |
+-------------+---------------+
|         318 |            12 |
|          61 |            14 |
|         110 |            14 |
|         281 |            14 |
|         136 |            15 |
|         248 |            15 |
|         492 |            16 |
|         398 |            16 |
|         464 |            16 |
|         164 |            16 |
|         223 |            17 |
|          48 |            17 |
|         465 |            17 |
|         315 |            17 |
|         555 |            17 |
|         124 |            18 |
|         140 |            18 |
|         205 |            18 |
|         542 |            18 |
|          95 |            18 |
|         255 |            18 |
|         271 |            18 |
|          97 |            18 |
|         353 |            18 |
|         194 |            18 |
|         483 |            18 |
|          70 |            18 |
|         344 |            18 |
|         218 |            18 |
.....
```


### LIMIT レコード件数の限定

例えば取得件数のトータルが1000件など多くなる場合に直近の10件のみ取得したいなどといった場合に使用します。
他にも最初の10件のみ取得したいなどといった状況があったりします。その際に使用することがあります。

```sql
SELECT * FROM payment LIMIT 10;
```

最初に10件が取得できたでしょうか？

では先ほどと違うもので実行し確認をして見ましょう。
```sql
SELECT customer_id, COUNT(*) AS payment_count FROM payment GROUP BY customer_id ORDER BY payment_count LIMIT 3;
```

実行結果は、利用頻度が少ない順に3件取得できたと思います。


#### 初級編の振り返り

以上で初級編は終了となります。
ここまでは、基礎のきになりますので本来データを取得し分析したりする際は、これらを使いさらに複雑なsql文の作成になります。

ただここをしっかりと抑えただけでもだいぶsql文が読めるようになると思います。
簡単な操作なら問題なくできるようになっているでしょう。

ここまでの学習を元に練習問題を行い座学ではなく実際に自身の頭で考えsql文を作成しましょう。

## [練習問題](First_exercises.md)
