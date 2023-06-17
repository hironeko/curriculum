# 練習問題解答

方針
指定の値ないしレコードの取得を行う
使用するデータが同じのためそれは可能。

1. 
`SELECT COUNT(*) FROM actor;`

2. 
`SELECT last_name FROM actor LIMIT 5;`

3. 
`SELECT DISTINCT first_name FROM actor LIMIT 3;`

4. 
``


5. 
```sql
SELECT
  customer_id, COUNT(customer_id) AS customer_count
FROM
  rental
WHERE
  return_date IS NOT NULL
GROUP BY
  customer_id
ORDER BY
  customer_count
DESC
LIMIT
  5
;
```


