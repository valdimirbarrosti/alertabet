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
    <?php include(__DIR__ . '/../templates/header.php'); ?>

    <div class="container form-container">
        <hr>
        <h1>Filtros</h1>
        <hr>
        <form action="/jogos" method="get" id=formContainer>
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="apiKey">API Key:</label>
                        <input type="text" class="form-control" id="apiKey" name="apiKey" required value="2e2398a0b79abd02a377d77186283aa0">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="sport">Esporte - Liga:</label>
                        <select class="form-control" id="sport" name="sport" required>
                            <option value="soccer_brazil_campeonato">Futebol - Campeonato Brasileiro Série A</option>
                            <option value="soccer_brazil_serie_b">Futebol - Campeonato Brasileiro Série B</option>
                            <option value="soccer_spain_la_liga">Futebol - Campeonato Espanhol La Liga</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="markets">Mercado:</label>
                        <select class="form-control" id="markets" name="markets" required>
                            <option value="h2h">Resultado Final</option>
                            <option value="totals">Over & Under 2.5 Gols</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="bookmakers">Casa de Aposta</label>
                        <select class="form-control" id="bookmakers" name="bookmakers" required>
                            <option value="betfair_ex_eu">Betfair Exchange</option>
                            <option value="pinnacle">Pinnacle</option>
                            <option value="onexbet">1xBet</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="strategies">Estratégia:</label>
                        <select class="form-control" id="strategies" name="strategies" required>
                            <option value="favorite_turnaround">Virada Do Favorito</option>
                            <option value="more_goals_will_come">Vai Sair Mais Gols</option>
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="search" value="true">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <hr>
    </div>

    <div class="container">
        <hr>
        <h1>Jogos</h1>

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
                        <td <?php if ($row['situation'] === "Iniciado"): ?>class="bold green"<?php elseif ($row['situation'] === "Aguardando Início"): ?>class="bold red"<?php endif; ?>><?= $row['situation'] ?></td>
                        <td><?= $row['flag'] ?></td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        <hr>

    </div>
    <?php include(__DIR__ . '/../templates/footer.php'); ?>

    <script src="/js/vendor/jquery/jquery-3.3.1.js"></script>
    <script src="/js/vendor/boostrap/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>s