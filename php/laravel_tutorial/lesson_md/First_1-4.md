## Laravel の構成

- Laravel の構成は、あくまで使用者に委ねる前提で構成されています。

  - なので以下に書いてある MVC がすべてではないということを覚えておきましょう。

- MVC という言葉を聞いたことはありますか？

  - デザインパターンではありません。
  - オブジェクト指向で成り立っています。
  - ケースバイケースでその形を変えるので`MVC`が絶対ということはありません。
  - プロジェクトにより採用すべきアーキテクチャやデザインパターンは異なります。なので幅広く知ることで多くのアプリケーション作成に対応が可能になります。

- 基本的なディレクトリ構成

```
app/
├── Console
│   └── Kernel.php
├── Exceptions
│   └── Handler.php
├── Http
│   ├── Controllers
│   │   └── Controller.php
│   ├── Kernel.php
│   └── Middleware
│       ├── Authenticate.php
│       ├── EncryptCookies.php
│       ├── PreventRequestsDuringMaintenance.php
│       ├── RedirectIfAuthenticated.php
│       ├── TrimStrings.php
│       ├── TrustHosts.php
│       ├── TrustProxies.php
│       └── VerifyCsrfToken.php
├── Models
│   └── User.php
└── Providers
    ├── AppServiceProvider.php
    ├── AuthServiceProvider.php
    ├── BroadcastServiceProvider.php
    ├── EventServiceProvider.php
    └── RouteServiceProvider.php
```
