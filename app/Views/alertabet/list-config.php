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

    <div class="container form-container">
        <hr>
        <h1>Configure a lista</h1>
        <hr>
        <form action="/jogos" method="get" id=formContainer>
            <div class="row">
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="apiKey">API Key:</label>
                        <input type="text" class="form-control" id="apiKey" name="apiKey" required value="">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="sport">Esporte:</label>
                        <select class="form-control" id="sport" name="sport" required>
                            <option value="soccer">Futebol</option>
                            <option value="basketball">Basquete</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="markets">Mercado:</label>
                        <select class="form-control" id="markets" name="markets" required>
                            <option value="h2h">Resultado Final</option>
                            <option value="totals">Over/Under</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="form-group">
                        <label for="bookmakers">Casa de Aposta</label>
                        <select class="form-control" id="bookmakers" name="bookmakers" required>
                            <option value="betfair_ex_eu">Betfair Exchange</option>
                            <option value="pinnacle">Pinnacle</option>
                        </select>
                    </div>
                </div>
            </div>
            <input type="hidden" name="search" value="true">
            <button type="submit" class="btn btn-primary">Enviar</button>
        </form>
        <hr>
    </div>

    <script src="/js/vendor/jquery/jquery-3.3.1.js"></script>
    <script src="/js/vendor/boostrap/bootstrap.min.js"></script>
    <script src="/js/script.js"></script>
</body>

</html>s