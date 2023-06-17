<?php

$age = 20;

//  年齢が二十歳の場合出力させる
// if文
// 20歳なら「お酒が飲めます」と出力させてください
if ($age === 20) {
    echo 'お酒が飲めます';
}

// 　if文と同じ様に三項演算子を使用して出力してください

// 3で割り切れる場合fizz, 5で割り切れる場合buzz, どちらでも割り切れる場合fizz buzzと表示させる
// fizz buzz
function fizzBuzz() {
    for ($i = 1; $i <= 100; $i++) {
        if ($i % 15 === 0) {
            echo 'fizz buzz';   
        }elseif ($i % 5 === 0) {
            echo 'fizz';
        }elseif ($i % 3 === 0) {
            echo 'buzz';
        }else {
            echo $i;
        }
    }

}

fizzBuzz();


// 配列の初期化を行なってください
// 配列の要素を5個用意していください
// 3個目の要素を出力してください
// 二次元配列を作成してください
// 二次元配列に対して連装配列の要素数を5以上作成してください(多次元配列)
// →　一次元を5個、さらにそれらに付随する要素を5個作成してください(5*5)
// 繰り返し処理を使用して二次元配列の連想配列のvalueのみ抜き出してください
// サンプル↓
[
    'hoge' => [
        'key' => 'value'
    ]
];

// hoge@example.com
// 上記の文字列の@以降のドメインだけ出力してください

// class
// 自己紹介を行うclassを作成してください
// 要件
// 何かしら出力するメソッドを2個class内に実装してください
// classのインスタンス時に引数を受け取れる様に実装してください
// classのプロパティを1個以上作成してください(引数に応じて)
// 例えば
// 私の年齢は〇〇ですと出力される様なメソッドです。

// 例
class User
{
    private string $name;
    private int $age;
    private string $hobby;

    public function __construct(string $name, int $age)
    {
        $this->name = $name;
        $this->age = $age;   
    }

    public function basicInfo()
    {
        return "私は、$this->name です。年齢は、$this->age です";
    }

    public function setHobby(string $hobby)
    {
        $this->hobby = $hobby; 
    }

    public function getHobby()
    {
        return "私の趣味は、$this->hobby です";
    }
}

