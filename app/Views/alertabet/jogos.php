<!DOCTYPE html>
<html lang="pt_BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta Bet!</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 0;
        }

        .container {
            max-width: 1200px;
            margin: 20px auto;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            padding: 20px 0;
            margin: 0;
            background-color: #4CAF50;
            color: #fff;
            border-radius: 8px 8px 0 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #dddddd;
            text-align: center;
            padding: 10px;
        }

        th {
            background-color: #f2f2f2;
        }

        tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        .custom-navbar-nav {
            text-align: center;
        }
    </style>

</head>

<body>
    <?php include(__DIR__ . '/../templates/header.php'); ?>

    <div class="container">
        <hr>
        <h1>Resultado Final</h1>

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
                    <th>Verificar?</th>

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
                        <td><?= $row['flag'] ?></td>

                    </tr>
                <?php endforeach; ?>

            </tbody>
        </table>
        <hr>

    </div>
    <?php include(__DIR__ . '/../templates/footer.php'); ?>

    <script src="/js/jquery-3.3.1.js"></script>
    <script src="/js/bootstrap.min.js"></script>

</body>

</html>