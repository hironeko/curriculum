## Vue.jsとは？

近年流行っているJSライブラリです。Reactと双璧と言えます。

## なぜ流行っているのか？

第一に学習コストが低い点です。ただしこれは、あくまでVue.js単体での話です。
これにVuex等が絡んでくると学習コストは比例してあがります。

## 事前順は？

ES6を知っていること事前に学習していること
ただし必須ではないです。

## LaravelとVue

デフォルトでVueのサンプルが用意されています。もちろんpackage.jsonにもすでにライブラリが記述されているので`yarn install` or `npm i`を実行すれば準備が完了します。

## さっそくinstallしbuildしましょう

ターミナル上で以下を実行しましょう。
```shell
yarn install
yarn hot
```

今回なぜhotを実行したかというとこのまま実装をしつつbuildも行うためです。
ターミナル上に`DONE Compiled successfully in 4079ms`のようなものが表示されたらOKです。


## 今回のゴール

ここまでに作成したものをAPIに変更し実行を可能します。

ただし今回は、`Vuex`は使用しません。


## 追加で使用するものをinstallしましょう

```shell
yarn add vue-router vuetify material-design-icons-iconfont --dev
```

cssのフレームワークを今回使用します。またVue側でroutingを実現させるために`vue-router`をinstallします


