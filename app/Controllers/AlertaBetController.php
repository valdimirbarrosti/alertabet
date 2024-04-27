<?php

namespace App\Controllers;

require_once(__DIR__ . '/../Helpers/functions.php');

use GuzzleHttp\Client;


class AlertaBetController
{
    const API_KEY = '2e2398a0b79abd02a377d77186283aa0';
    const API_URL = 'https://api.the-odds-api.com/';

    public function index()
    {
        require_once dirname(__DIR__)
            . DIRECTORY_SEPARATOR . 'Views'
            . DIRECTORY_SEPARATOR . 'alertabet'
            . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function games()
    {
        $search = isset($_GET['search']) && $_GET['search'] === 'true';

        if ($search) {
            $sport = $_GET['sport'] ?? '';
            $apiKey = $_GET['apiKey'] ?? '';
            $regions = $_GET['regions'] ?? '';
            $markets = $_GET['markets'] ?? '';
            $bookmakers = $_GET['bookmakers'] ?? '';
            debug('tem search');
        }
        debug('nao tem search');

        $client = new Client();
        $endpoint = "/v4/sports/$sport/odds/?apiKey=$apiKey&regions=$regions&markets=$markets&bookmakers=$bookmakers";
        $endpoint = self::API_URL . 'v4/sports/soccer_brazil_campeonato/odds/?apiKey=' . self::API_KEY . '&regions=eu&markets=h2h&bookmakers=pinnacle';
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
            $lastUpdate = date('d/m/Y H:i', strtotime($event['bookmakers'][0]['markets'][0]['last_update']));


            $row = [
                'competition' => $event['sport_title'],
                'event_start_time' => $eventStartTime,
                'home_team' => $home_team,
                'home_team_price' => $home_team_price,
                'away_team' => $away_team,
                'away_team_price' => $away_team_price,
                'draw_name' => $event['bookmakers'][0]['markets'][0]['outcomes'][2]['name'],
                'draw_price' => $event['bookmakers'][0]['markets'][0]['outcomes'][2]['price'],
                'type' => $event['bookmakers'][0]['markets'][0]['key'],
                'last_update' => $lastUpdate,
                'flag' => $flag ? "Sim" : "Não"
            ];

            $table[] = $row;
        }

        require_once dirname(__DIR__)
            . DIRECTORY_SEPARATOR . 'Views'
            . DIRECTORY_SEPARATOR . 'alertabet'
            . DIRECTORY_SEPARATOR . 'games.php';
    }

    public function getSports($apiKey)
    {
        $client = new Client();
        $endpoint = self::API_URL . 'sports?apiKey=' . $apiKey;
        $response = $client->request('GET', $endpoint);
        echo jsonFormatter($response);
    }


    public function getGames($sport, $apiKey, $markets, $regions, $bookmakers)
    {


        if (empty($sport) || empty($apiKey) || empty($markets) || empty($regions) || empty($bookmakers)) {
            throw new InvalidArgumentException('Todos os parâmetros obrigatórios devem ser fornecidos.');
        }
    }
}
