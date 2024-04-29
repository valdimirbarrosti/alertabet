<?php
// Conexão com o banco de dados
try {
    $pdo = new PDO('sqlite:C:/dev/projetos/alertabet/storage/alertabet.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Caminho para o arquivo migrations.sql
    $arquivo_sql = __DIR__ . '/../storage/migrations/migrations.sql';

    // Verifica se o arquivo existe
    if (file_exists($arquivo_sql)) {
        // Lê o conteúdo do arquivo
        $queries = file_get_contents($arquivo_sql);

        // Divide as consultas em um array usando o ponto e vírgula como delimitador
        $lista_queries = explode(';', $queries);

        // Executa cada consulta
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
?>
