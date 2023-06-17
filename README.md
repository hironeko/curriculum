## 学習環境構築

- PHP の基礎実装と Laravel の基礎実装
- SQL 基礎

#### Mac

#### M1(or M2) Mac を使用している人

- `docker-compose -f docker-compose.m1.yml up -d`と file を指定して使用するようにしてください

##### PHP

- 本カリキュラムでは、PHP の version は、8.1.\*以上を推奨します。

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

#### Windows

- WSL2 を使用して環境を作成します。下記 URL を参考にまずは WSL2 の install を行なってください

[手順](https://docs.microsoft.com/ja-jp/windows/wsl/install)

- Docker の install を行います。

[ダウンロード](https://docs.docker.com/desktop/windows/install/)

- install 後下記手順を踏んでください

- Docker の設定を開き下記の画像のようになるように操作してください

![settings](./img/windows_docker_settings.png)

- 操作が完了したら WSL のターミナルを開き下記コマンドを実行し、コマンドが使えることを確認してください。

```shell
which docker-compose
```

- DB 環境を作成します

```shell
docker-compose up
```

Mac の手順同様ログが出たら一度止め、Mac の手順と同じようにコマンドを実行しましょう

- Windows の場合、WSL2 には PHP が入っていません。適宜ダウンロードし使えるようにしましょう。

> PHP の version は、必ず 8.1 以上を入れるようにしましょう。また下記コマンドを実行すれば必要なものが大方入り動作させることが可能になると思います

- 下記 8.1 という数字は自身の PC でしようされている PHP の version に合わせてください

```shell
$ sudo apt install -y php8.2 php8.2-zip php8.2-mbstring php8.2-xml php8.2-mysql unzip php8.2-opcache curl php-cli php8.2-curl
```

- composer の install をしましょう
  - 実行する場所は、`cd ~`でホームディレクトリという場所で行いましょう

```shell
$ php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"
$ php -r "if (hash_file('sha384', 'composer-setup.php') === 'e0012edf3e80b6978849f5eff0d4b4e4c79ff1609dd1e613307e16318854d24ae64f26d17af3ef0bf7cfb710ca74755a') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('composer-setup.php'); } echo PHP_EOL;"
$ php composer-setup.php
$ php -r "unlink('composer-setup.php');"
$ composer -v //versionの確認ができれば問題ないです
```
