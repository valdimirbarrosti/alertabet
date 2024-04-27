<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AlertaBetController
{
    private $apiUrl;
    private $apiKeyAlertaBet;


    public function __construct()
    {
        $this->apiUrl = env('API_URL');
        $this->apiKeyAlertaBet = env('API_KEY_ALERTA_BET');
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getApiKeyAlertaBet()
    {
        return $this->apiKeyAlertaBet;
    }

    public function showIndex()
    {
        require_once getMainDir()
            . 'App'
            . DIRECTORY_SEPARATOR . 'Views'
            . DIRECTORY_SEPARATOR . 'alertabet'
            . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function showGames()
    {
        $search = $_GET['search'] ?? false;

        if ($search == true) {
            $sport = $_GET['sport'] ?? '';
            $apiKey = $_GET['apiKey'] ?? '';
            $markets = $_GET['markets'] ?? '';
            $bookmakers = $_GET['bookmakers'] ?? '';
            $this->getGames($apiKey, $sport, $markets, $bookmakers);
        } else {
            require_once dirname(__DIR__)
                . DIRECTORY_SEPARATOR . 'Views'
                . DIRECTORY_SEPARATOR . 'alertabet'
                . DIRECTORY_SEPARATOR . 'games.php';
        }
    }

    public function getGames($apiKey, $sport, $markets, $bookmakers)
    {
        if (empty($apiKey) || empty($sport) || empty($markets) || empty($bookmakers)) {
            throw new \InvalidArgumentException('Todos os parâmetros obrigatórios devem ser fornecidos.');
        }
        $endpoint = $this->apiUrl . "sports/$sport/odds/?apiKey=$apiKey&markets=$markets&bookmakers=$bookmakers";
        try {
            $client = new Client();
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
                $currentDateTime = time();
                if (strtotime($event['commence_time']) < $currentDateTime) {
                    $situation = "Iniciado";
                } else {
                    $situation = "Aguardando Início";
                }

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
                    'situation' => $situation,
                    'flag' => $flag ? "Sim" : "Não"
                ];
                $table[] = $row;
            }
            require_once dirname(__DIR__)
                . DIRECTORY_SEPARATOR . 'Views'
                . DIRECTORY_SEPARATOR . 'alertabet'
                . DIRECTORY_SEPARATOR . 'games.php';

        } catch(RequestException $e) {
            $response = $e->getResponse();       
            if ($response) {
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                echo "Erro $statusCode: $errorBody\n";
            } else {
                // Se não houver resposta, exibe uma mensagem genérica de erro
                echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n";
            }
        }
    }

    public function getSports()
    {
        try {
            $client = new Client();
            $endpoint = $this->getApiUrl() . 'sports?apiKey=' . $this->getApiKeyAlertaBet();
            $response = $client->request('GET', $endpoint);
            echo jsonFormatter($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                echo "Erro $statusCode: $errorBody\n";
            } else {
                // Se não houver resposta, exibe uma mensagem genérica de erro
                echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n";
            }
        }
    }

    public function getEvents()
    {
        try {
            $client = new Client();
            $sport = 'soccer_brazil_campeonato';
            $eventId = 'fbc7c329f7514a204f1e869d7f39c9ce';
            $endpoint = $this->apiUrl . "sports/$sport/scores/?apiKey=". $this->getApiKeyAlertaBet() . "&eventIds=$eventId";     
            $response = $client->request('GET', $endpoint);
            echo jsonFormatter($response);
        } catch (RequestException $e) {
            $response = $e->getResponse();
            if ($response) {
                $statusCode = $response->getStatusCode();
                $errorBody = $response->getBody()->getContents();
                echo "Erro $statusCode: $errorBody\n";
            } else {
                // Se não houver resposta, exibe uma mensagem genérica de erro
                echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n";
            }
        }
    }
}
