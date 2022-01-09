## 学習環境構築

- PHP の基礎実装と Laravel の基礎実装
- SQL 基礎

#### Mac

##### PHP

- 本カリキュラムでは、PHP の version は、7.4.\*以上を推奨します。

##### DB

- Docker の install を行います

[ダウンロード](https://matsuand.github.io/docs.docker.jp.onthefly/desktop/mac/install/)

上記ダウンロードリンクから Docker のダウンロードを行い、ページに記載ある手順で install を行なってください

- DB の利用

本カリキュラムでは、DB はすべて Docker を使用します。当ディレクトリ(カレントディレクトリといいます)で下記コマンドを実行してください

```shell
docker-compose up
```

![Logs](./img/mysql_logs.png)

上記コマンド実行後にログが出力されるようになりましたら一度 Docker を停止します。

CTL + C で止めることができます。

今後は下記コマンドでバックグランド実行します。

```shell
docker-compose up -d
```
