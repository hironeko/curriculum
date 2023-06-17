<?php


class Base
{
    const DNS = 'mysql:dbname=test;host=127.0.0.1;port=3006;charset=utf8mb4';
    const USER = 'root';
    const PASSWORD = 'root';
    protected PDO $db;

    public function connection()
    {
        try {
            $this->db  = new PDO(
                self::DNS,
                self::USER,
                self::PASSWORD
            );
        } catch (PDOException $e) {
            echo "接続に失敗しました：" . $e->getMessage() . "\n";
            exit();
        }
    }

    public function disconnection()
    {
        $this->db = null;
    }
}