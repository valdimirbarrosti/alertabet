<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta Bet!</title>
    <link rel="stylesheet" href="/css/vendor/bootstrap/bootstrap.min.css">
    <link rel="stylesheet" href="/css/style.css">
</head>

<body>
<?php include(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php'); ?>
    <div class="container">
        <hr>
        <h1>Monitoramento dos Jogos</h1>
        <div class="table-container">
            <table>
                <tr>
                    <th>Competição</th>
                    <th>Data\Hora do Evento</th>
                    <th>Casa</th>
                    <th>Gols</th>
                    <th>Fora</th>
                    <th>Gols</th>
                    <th>Favorito</th>
                    <th>Odd Favorito - PRÉ</th>
                    <th>Odd Favorito - LIVE</th>
                    <th>Casa de Apostas</th>
                    <th>Tipo</th>
                    <th>Dicas</th>
                    <th>Alerta</th>
                    <th>Situação</th>
                </tr>
                <?php foreach ($table as $row) : ?>
                    <tr>
                        <td><?= $row['competition'] ?></td>
                        <td><?= $row['time_start_event'] ?></td>
                        <td><?= $row['home_team'] ?></td>
                        <td><?= $row['home_team_score'] ?></td>
                        <td><?= $row['away_team'] ?></td>
                        <td><?= $row['away_team_score'] ?></td>
                        <td><?= $row['favourite_team'] ?></td>
                        <td><?= $row['favourite_team_odd_pre'] ?></td>
                        <td><?= $row['favourite_team_odd_live'] ?></td>
                        <td><?= $row['bookmaker'] ?></td>
                        <td><?= $row['type'] ?></td>
                        <td><?= $row['tips'] ?></td>
                        <td><?= $row['alert'] ?></td>
                        <td <?php if ($row['situation'] == 'Em andamento'): ?>class="bold green"<?php elseif ($row['situation'] == 'Não Iniciado'): ?>class="bold red"<?php endif; ?>><?= $row['situation'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <hr>
    </div>
    <?php include(dirname(__DIR__) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'footer.php'); ?>
    <script src="/js/vendor/jquery/jquery-3.3.1.js"></script>
    <script src="/js/vendor/boostrap/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>s