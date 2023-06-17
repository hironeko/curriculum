# 中級

テーブルの結合がメインになります。
JOINというものがメインで登場しかなりイメージがしづらいゾーンになってきます。
前提としてメインテーブルと結合テーブルがあり
テーブルをJOIN(結合)する時、どちらのテーブルがメインとするかがポイントです。

## 等価結合

異なるテーブルから条件に一致するレコードを結合して取得する際に使用します。

```sql
SELECT
  payment.payment_id,
  customer.first_name,
  customer.last_name
FROM
  payment, customer
WHERE
  payment.customer_id = customer.customer_id
; 
```

件数は、多くなりますがこれで`customer_id` が同一のものを結合して取得できました。
複数のテーブルを結合させるという点では、これでも問題なく動作します。 


## INNER JOIN ... ON  INNERは省略できます。

異なるテーブルで同じデータをもつレコード同士結合して取得する際に使います。
これは、内部結合と呼ばれたりします。とても等価結合と似てますが厳密には異なることは、覚えていおてください。
条件に一致しないレコードは取得しません。必ず両方のテーブルに存在するレコードだけ取得します。
これは、メインとなるtableと結合tableを入れ替えても結果は、同じになります。
例えば、伝票table、商品tableがあった場合伝票tableには、商品tableのidのみが入っており商品名称や定価の情報がなかった場合に二つのtableを結合し一度に伝表番号、商品名、定価を取得する際に使用します。

```sql
SELECT 
  * 
FROM
  メインテーブル
INNER JOIN
  結合テーブル
ON
  メインテーブル.キー = 結合テーブル.キー
;
```
メインテーブル、結合テーブルと書いてますがINNER JOINに関しては、同義なのでどちらかを優先にしてとかはないです。

では、実際にサンプルのデータを使用し、payment tableとcustomer tableを結合し支払いを行なった利用者の人物名を取得したいと思います。
初級と異なり若干長くなるので改行を加えます。
```sql
SELECT 
  payment.payment_id,
  customer.first_name,
  customer.last_name
FROM
  customer
INNER JOIN
  payment
ON
  customer.customer_id = payment.customer_id
LIMIT 10
;
```

実際に取得ができたと思います。
これは以下と同義で同じ結果が取得できます。

```sql
SELECT 
  payment.payment_id,
  customer.first_name,
  customer.last_name
FROM
  payment
INNER JOIN
  customer
ON
  customer.customer_id = payment.customer_id
;
```

軸にする主テーブルと副テーブルが入れ替わっても同じ結果が取得することが可能です。
それは、どちらにも値が入っている場合のみレコードが取得するということが起因となっています。

Aのテーブルには、値があってBのテーブルには値がない場合というのは、Aのテーブルを主テーブルにしても副テーブルにしても事実として変わらないのでAとBどちらを主テーブルにしても取得できるレコードには、変化がないということです。


## LEFT OUTER JOIN OUTERは省略できます。

外部結合と呼ばれるものになります。
基本的にテーブルの結合を使用する際は、`LEFT OUTER JOIN` が主として使われます。


```sql
SELECT
  *
FROM
  主テーブル
LEFT OUTER JOIN 副テーブル
  ON 
    主テーブル.カラム名A = 副テーブル.カラム名A
;
```

これが基本的な形になります。では、実際に取得してみましょう。
取得する条件内容は、以下とします。
1. rentalテーブルを主テーブル
2. customerテーブルを副テーブル
3. customerテーブルのlast_nameとfirst_name、rentalテーブルのreturn_dateを取得

```sql
SELECT
  rental.return_date,
  customer.first_name,
  customer.last_name
FROM
  rental
LEFT OUTER JOIN
  customer
  ON
    rental.customer_id = customer.customer_id
;
```

こちらも件数は、すごく多くなりますが`NULL` が存在しているレコードも取得してるのが確認できますでしょうか？


### USING() を使用してまとめる

結合に使うカラム名が同一の場合、`USING()` を使用しまとめることが可能です。

`USING( customer_id )` とすることが可能です。

実際に上記の2種類の`WHERE句` 、`ON以下` のカラム名の箇所を`USING` を使用してまとめてみましょう。

```sql
SELECT 
  payment.payment_id,
  customer.first_name,
  customer.last_name
FROM
  payment
INNER JOIN
  customer
  USING(customer_id)
;

SELECT
  rental.return_date,
  customer.first_name,
  customer.last_name
FROM
  rental
LEFT OUTER JOIN
  customer
  USING(customer_id)
; 
```

### 結合に`WHERE句` を入れよう

例えば、取得したデータが膨大のため一人のcustomerのみに絞って取得したいという場面は少なからず想定されます。
そのような状況を想定した場合対象のcustomerのみ取得できるsqlは必須と言わざる負えません。

```sql
 SELECT
  rental.return_date,
  customer.first_name,
  customer.last_name
FROM
  rental
LEFT OUTER JOIN
  customer
  USING(customer_id)
WHERE
  customer.first_name = "MARY"
  AND
  customer.last_name = "SMITH"
;
```

WHERE句の使い方は至って通常です。今までの学習で習ったことの応用ですね。初級と異なるのは、`テーブル名.カラム名` になったことでしょうか？それ以外に異なる書き方はありません。

このsqlを実行することにより一人のcustomerのレコードのみを取得できました。

ここまでの学習でテーブルの結合について学んできました。
本来ですと`RIGTH OUTER JOIN ON` など学ぶべきと言われそうですがこれは、`LEFT OUTER JOIN ON` も主テーブルと副テーブルを入れ替えるのみで同じ結果を得ることが可能です。
なのでテーブルの結合を使うという際は、`LEFT OUTER JOIN ON` を使用すると考えれば大方問題ないかと思います。

では、この後の学習は、より絞り込んだレコード取得＋複数のテーブルの結合を行うということを念頭に進めたい思います。

## GROUP BYとHAVINGを使用しての絞りこみ

この学習では、より可読性をあげるために`AS` をうまく使用してのsqlの作成を行いたいと思います。

いままので学習と異なりだいぶ複雑なsqlになると思います。

```sql
SELECT
  category.name AS name,
  count(category.name) AS film_cnt
FROM
  film
INNER JOIN film_category
  USING(film_id)
INNER JOIN category
  USING(category_id)
GROUP BY
  category.name
HAVING
  count(category.name) >= 60
ORDER BY
  film_cnt DESC
;
```

`HAVING` これがどうも`WHERE` に似ててしゃーないと思います。
これから学習するにあたりどうしてもついて回るのが`sqlの評価順番` という点です。今回の`HAVING` と`WHERE` についてもこれです。
sqlが実行される際それぞれ評価の順番が決まっているという点です。

sqlが評価する順番です。
1. FROM
2. ON
3. JOIN
4. WHERE
5. GROUP BY
6. HAVING
7. SELECT
8. DISTINCT
9. ORDER BY
10. TOP（LIMIT）

この順番を念頭に入れこの後も取り組んでいくことによってどうしてのその順番のsqlの書き方なのか？という疑問を持ちながら学習ができるかと思います。
上記の順番に当てはめて考えると`WHERE` > `GROUP BY` > `HAVING` という順番に評価がされます。
なので上に書いたsqlの順番を変えるとエラーが発生し実行ができません。


