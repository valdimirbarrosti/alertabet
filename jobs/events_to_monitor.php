<?php
date_default_timezone_set('America/Sao_Paulo');
require_once(__DIR__ . '/../app/Helpers/functions.php');
require getMainDir() . 'vendor/autoload.php';


use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

$apiUrl = env('API_URL');
$apiKeyAlertaBet = env('API_KEY_ALERTA_BET');
$markets = 'h2h';
$bookmakers = 'betfair_ex_eu';

$arraySport = [
    'soccer_brazil_campeonato',
    'soccer_brazil_serie_b',
    'soccer_spain_la_liga',
    'soccer_italy_serie_a',
    'soccer_portugal_primeira_liga',
    'soccer_epl',
    'soccer_germany_bundesliga',
    'soccer_france_ligue_one',
    'soccer_turkey_super_league',
    'soccer_china_superleague',
    'soccer_netherlands_eredivisie'
];


/*
//1º coletar todos titles  do elementos cujo valor da chave group é soccer:
try {
    $client = new Client();
    $endpoint = $apiUrl . "sports?apiKey=$apiKeyAlertaBet";
    $response = $client->request('GET', $endpoint);
    $jsonResponse = $response->getBody();
    $arraySports = json_decode($jsonResponse, true);
    $arrayCompetitions = [];
    foreach ($arraySports as $sport) {
        if ($sport['group'] == 'Soccer') {
            $arrayCompetitions[] = $sport['key'];
        }
    }
} catch (RequestException $e) {
    $response = $e->getResponse();
    if ($response) {
        $statusCode = $response->getStatusCode();
        $errorBody = $response->getBody()->getContents();
        echo "Erro $statusCode: $errorBody\n" . PHP_EOL ;
    } else {
        // Se não houver resposta, exibe uma mensagem genérica de erro
        echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n". PHP_EOL ;
    }
}
*/


//2º iterar o array de competições e buscar os eventos h2h da betfair de cada competição
try {
    $arrayEventList = [];
    $client = new Client();
    foreach ($arraySport as $sport) {
        echo  $sport .  PHP_EOL;
        echo  $markets .  PHP_EOL;
        $endpoint = $apiUrl . "sports/$sport/odds/?apiKey=$apiKeyAlertaBet&markets=$markets&bookmakers=$bookmakers";
        $response = $client->request('GET', $endpoint);
        $jsonResponse = $response->getBody();
        $eventListH2H = json_decode($jsonResponse, true);
        $count = count($eventListH2H);
        $table = [];
        foreach ($eventListH2H as $index => $event) {
            if ($index >= ($count / 2)) {
                break;
            }
            $flag = false;
            $home_team = $event['home_team'];
            $away_team = $event['away_team'];
            if (!isset($event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'])) {
                continue;
            }
            if (!isset($event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'])) {
                continue;
            }
            if (!isset($event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'])) {
                continue;
            }
            if (!isset($event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'])) {
                continue;
            }
            $team_1_name = $event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'];
            $team_1_price = $event['bookmakers'][0]['markets'][0]['outcomes'][0]['price'];
            //$team_2_name = $event['bookmakers'][0]['markets'][0]['outcomes'][1]['name'];
            $team_2_price = $event['bookmakers'][0]['markets'][0]['outcomes'][1]['price'];
            if ($team_1_name === $home_team) {
                $home_team_price = $team_1_price;
                $away_team_price = $team_2_price;
            } else {
                $home_team_price = $team_2_price;
                $away_team_price = $team_1_price;
            }
            $eventStartTime = date('d/m/Y H:i', strtotime($event['commence_time']));
            if ($home_team_price < $away_team_price) {
                $favouriteTeam = $home_team;
                $favouriteTeamPrice = $home_team_price;
            } else {
                $favouriteTeam = $away_team;
                $favouriteTeamPrice = $away_team_price;
            }
            if($favouriteTeamPrice >= 1.5) {
                continue;
            }

            $timeSartEvent = new DateTime($event['commence_time']);
            $timeSartEvent->modify('-3 hours');
            $timeSartEvent = $timeSartEvent->format('Y-m-d\TH:i:s\Z');

            try {
                echo  $event['id'] .  PHP_EOL;
                $pdo = new PDO('sqlite:C:/dev/projetos/alertabet/storage/alertabet.db');
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $values = array(
                    'id_event' => $event['id'],
                    'competition' => $event['sport_title'],
                    'sport' => $sport,
                    'time_start_event' => $timeSartEvent,
                    'home_team' => $home_team,
                    'home_team_score' => 0,
                    'away_team' => $away_team,
                    'away_team_score' => 0,
                    'favourite_team' => $favouriteTeam,
                    'favourite_team_odd_pre' => $favouriteTeamPrice,
                    'favourite_team_odd_live' => null,
                    'bookmaker' => $bookmakers,
                    'type' => $markets,
                    'tips' => null,
                    'alert' => null,
                    'created_at' => date('d/m/Y H:i', time()),
                    'updated_at' => date('d/m/Y H:i', time()),
                    'deleted_at' => null

                );
                try {
                    $query = "INSERT INTO monitor (id_event, competition, sport, time_start_event, home_team, home_team_score, away_team, away_team_score, favourite_team, favourite_team_odd_pre, favourite_team_odd_live, bookmaker, type, tips, alert, created_at, updated_at, deleted_at) VALUES (:id_event, :competition, :sport, :time_start_event, :home_team, :home_team_score, :away_team, :away_team_score, :favourite_team, :favourite_team_odd_pre, :favourite_team_odd_live, :bookmaker, :type, :tips, :alert, :created_at, :updated_at, :deleted_at)";

                    $stmt = $pdo->prepare($query);
                    foreach ($values as $chave => $valor) {
                        $stmt->bindParam(':' . $chave, $values[$chave]);
                    }
                    $stmt->execute();
                    echo "Inserção realizada com sucesso!" . PHP_EOL;
                } catch (PDOException $e) {
                    echo "Erro ao executar a inserção: " . $e->getMessage() . PHP_EOL;
                }
            } catch (PDOException $e) {
                echo "Erro ao conectar ou executar as operações: " . $e->getMessage() . PHP_EOL;
            }
        }
    }
} catch (RequestException $e) {
    $response = $e->getResponse();
    if ($response) {
        $statusCode = $response->getStatusCode();
        $errorBody = $response->getBody()->getContents();
        echo "Erro $statusCode: $errorBody\n";
    } else {
        echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n" . PHP_EOL;
    }
}


/*
//Inserção dos dados na base de dados
try {
    $pdo = new PDO('sqlite:C:/dev/projetos/alertabet/storage/alertabet.db');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);    

    $query = "INSERT INTO logs (mensagem) VALUES (:mensagem)";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':mensagem', $mensagem);
    $stmt->execute();

    echo "Operações executadas com sucesso!";
} catch (PDOException $e) {
    echo "Erro ao conectar ou executar as operações: " . $e->getMessage();
}

*/
