<?php

try {
    $dbPath = 'sqlite:'.dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'alertabet.db';
    $pdo = new PDO($dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $arquivo_sql = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'migrations' . DIRECTORY_SEPARATOR . 'migrations.sql';
    if (file_exists($arquivo_sql)) {
        $queries = file_get_contents($arquivo_sql);
        $lista_queries = explode(';', $queries);
        foreach ($lista_queries as $query) {
            if (!empty(trim($query))) {
                $pdo->exec($query);
            }
        }
        echo "Migrações executadas com sucesso!";
    } else {
        echo "O arquivo migrations.sql não foi encontrado.";
    }
} catch (PDOException $e) {
    echo "Erro ao conectar ou executar as migrações: " . $e->getMessage();
}
