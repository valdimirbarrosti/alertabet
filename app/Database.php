<?php


namespace App;

class Database
{
    private $pdo;

    public function __construct()
    {
        $dbPath = 'sqlite:' . dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'alertabet.db';
        try {
            $pdo = new \PDO($dbPath);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            echo "Conexão com o banco de dados SQLite estabelecida com sucesso!";
        } catch (\PDOException $e) {
            echo "Erro ao conectar ao banco de dados: " . $e->getMessage();
        }
    }

    public function getPdo()
    {
        return $this->pdo;
    }
}
