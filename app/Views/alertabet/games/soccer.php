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
    <?php include(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'templates' . DIRECTORY_SEPARATOR . 'header.php'); ?>

    <div class="container">
        <hr>
        <h1>Jogos em andamento</h1>
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
                        <td <?php if ($row['situation'] == 'Em andamento') : ?>class="bold green" <?php elseif ($row['situation'] == 'Não Iniciado') : ?>class="bold red" <?php endif; ?>><?= $row['situation'] ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
        <hr>
    </div>


    <div class="container">
        <hr>
        <h1>Jogos Não Iniciados</h1>
        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>Competição</th>
                        <th>Hora do Evento</th>
                        <th>Casa</th>
                        <th>Odd</th>
                        <th>Empate</th>
                        <th>Odd</th>
                        <th>Fora</th>
                        <th>Odd</th>
                        <th>Última Atualização</th>
                        <th>Tipo</th>
                        <th>Over Odd</th>
                        <th>Under Odd</th>
                        <th>Favourite Totals</th>
                        <th>Favourite Odd</th>
                        <th>Situação</th>
                        <th>Alerta?</th>

                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($table as $row) : ?>
                        <tr>
                            <td><?= $row['competition'] ?></td>
                            <td><?= $row['event_start_time'] ?></td>
                            <td><?= $row['home_team'] ?></td>
                            <td><?= $row['home_team_price'] ?></td>
                            <td><?= $row['draw_name'] ?></td>
                            <td><?= $row['draw_price'] ?></td>
                            <td><?= $row['away_team'] ?></td>
                            <td><?= $row['away_team_price'] ?></td>
                            <td><?= $row['last_update'] ?></td>
                            <td><?= $row['type'] ?></td>
                            <td><?= $row['over_price'] ?></td>
                            <td><?= $row['under_price'] ?></td>
                            <td <?php if ($row['favourite_totals'] === "Over") : ?>class="bold green" <?php elseif ($row['favourite_totals'] === "Under") : ?>class="bold red" <?php endif; ?>><?= $row['favourite_totals'] ?></td>
                            <td <?php if ($row['favourite_totals_price'] === "Over") : ?>class="bold green" <?php elseif ($row['favourite_totals_price'] === "Under") : ?>class="bold red" <?php endif; ?>><?= $row['favourite_totals_price'] ?></td>
                            <td <?php if ($row['situation'] === "Iniciado") : ?>class="bold green" <?php elseif ($row['situation'] === "Aguardando Início") : ?>class="bold red" <?php endif; ?>><?= $row['situation'] ?></td>
                            <td><?= $row['flag'] ?></td>

                        </tr>
                    <?php endforeach; ?>

                </tbody>
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