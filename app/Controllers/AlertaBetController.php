<?php

namespace App\Controllers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class AlertaBetController
{
    private $apiUrl;
    private $apiKeyAlertaBet;
    private $dbPath;


    public function __construct()
    {
        $this->apiUrl = env('API_URL');
        $this->apiKeyAlertaBet = env('API_KEY_ALERTA_BET');
        $this->dbPath = 'sqlite:' . dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'alertabet.db';
    }

    public function getApiUrl()
    {
        return $this->apiUrl;
    }

    public function getApiKeyAlertaBet()
    {
        return $this->apiKeyAlertaBet;
    }

    public function getDbPath()
    {
        return $this->dbPath;
    }

    public function showIndex()
    {
        require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'alertabet' . DIRECTORY_SEPARATOR . 'index.php';
    }

    public function showGames()
    {
        $search = $_GET['search'] ?? false;

        if ($search == true) {
            $apiKey = $_GET['apiKey'] ?? '';
            $markets = $_GET['markets'] ?? '';
            $bookmakers = $_GET['bookmakers'] ?? '';
            $this->getGames($apiKey, $markets, $bookmakers);
        } else {
            require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR .  'alertabet' . DIRECTORY_SEPARATOR . 'games.php';
        }
    }

    public function showMonitor()
    {
        $pdo = new \PDO($this->getDbPath());
        $monitorModel = new \App\Models\MonitorModel($pdo);
        $monitorRegistries = $monitorModel->getLiveEvents();
        $table = [];
        foreach ($monitorRegistries as $registry) {
            $updatedEvent = $this->getEventById($registry['id_event'], $registry['sport']);
            $updatedOdd = $this->getOddsByEventId($registry['id_event'], $registry['sport'], $registry['bookmaker'], $registry['type']);
            $tips = 0;
            $alert = 0;
            $situation = 'Em andamento';
            foreach ($updatedEvent[0]['scores']  as $score) {
                if ($score['name'] == $updatedEvent[0]['home_team']) {
                    $homeTeamScore = $score['score'];
                } else {
                    $awayTeamScore = $score['score'];
                }
            };

            foreach ($updatedOdd[0]['bookmakers'][0]['markets'][0]['outcomes'] as $team) {
                if ($team['name'] == $registry['favourite_team']) {
                    $favouriteTeamOddLive = $team['price'];
                }
            };

            $table[] = [
                'competition' => $registry['competition'],
                'time_start_event' => $registry['time_start_event'],
                'home_team' => $registry['home_team'],
                'home_team_score' => $homeTeamScore,
                'away_team' => $registry['away_team'],
                'away_team_score' => $awayTeamScore,
                'favourite_team' => $registry['favourite_team'],
                'favourite_team_odd_pre' => $registry['favourite_team_odd_pre'],
                'favourite_team_odd_live' => $favouriteTeamOddLive,
                'bookmaker' => $registry['bookmaker'],
                //'type' => $registry['type'],
                'tips' => $tips,
                'alert' => $alert,
                'situation' => $situation,
            ];
        }

        require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'alertabet' . DIRECTORY_SEPARATOR . 'monitor.php';
    }

    public function getGames($apiKey, $markets, $bookmakers)
    {
        if (empty($apiKey) ||  empty($markets) ||  empty($bookmakers)) {
            throw new \InvalidArgumentException('Todos os parâmetros obrigatórios devem ser fornecidos.');
        }

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

        $table = [];
        foreach ($arraySport as $sport) {
            $endpoint = $this->apiUrl . "sports/$sport/odds/?apiKey=$apiKey&markets=$markets&bookmakers=$bookmakers";
            try {
                $client = new Client();
                $response = $client->request('GET', $endpoint);
                $jsonResponse = $response->getBody();
                $eventList = json_decode($jsonResponse, true);
                $count = count($eventList);
                $countaux = 0;
                foreach ($eventList as $index => $event) {
                    if ($index >= ($count / 2)) {
                        break;
                    }
                    if ($markets == 'h2h') {
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
                        if ($home_team_price < $away_team_price) {
                            $favouriteTeam = $home_team;
                            $favouriteTeamPrice = $home_team_price;
                        } else {
                            $favouriteTeam = $away_team;
                            $favouriteTeamPrice = $away_team_price;
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
                            'type' => 'h2h',
                            'last_update' => $lastUpdate,
                            'situation' => $situation,
                            'flag' => $flag ? "Sim" : "Não"
                        ];
                    }

                    if ($markets == 'totals') {
                        $flag = false;
                        $home_team = $event['home_team'];
                        $away_team = $event['away_team'];
                        $over = $event['bookmakers'][0]['markets'][0]['outcomes'][0]['name'];
                        $overPrice = $event['bookmakers'][0]['markets'][0]['outcomes'][0]['price'];
                        $under = $event['bookmakers'][0]['markets'][0]['outcomes'][1]['name'];
                        $underPrice = $event['bookmakers'][0]['markets'][0]['outcomes'][1]['price'];

                        if ($overPrice < $underPrice) {
                            $favouriteTotals = $over;
                            $favouriteTotalsPrice = $overPrice;
                        } else {
                            $favouriteTotals = $under;
                            $favouriteTotalsPrice = $underPrice;
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
                            'away_team' => $away_team,
                            'over_price' => $overPrice,
                            'under_price' => $underPrice,
                            'favourite_totals' => $favouriteTotals,
                            'favourite_totals_price' => $favouriteTotalsPrice,
                            'type' => 'totals',
                            'last_update' => $lastUpdate,
                            'situation' => $situation,
                            'flag' => $flag ? "Sim" : "Não"
                        ];
                    }

                    $table[] = $row;
                }
            } catch (RequestException $e) {
                $response = $e->getResponse();
                if ($response) {
                    $statusCode = $response->getStatusCode();
                    $errorBody = $response->getBody()->getContents();
                    echo "Erro $statusCode: $errorBody\n";
                } else {
                    echo "Erro ao fazer a requisição: " . $e->getMessage() . "\n";
                }
            }
        }
        usort($table, function ($a, $b) {
            $timeA = \DateTime::createFromFormat('d/m/Y H:i', $a['event_start_time']);
            $timeB = \DateTime::createFromFormat('d/m/Y H:i', $b['event_start_time']);

            return $timeA <=> $timeB;
        });

        require_once dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Views' . DIRECTORY_SEPARATOR . 'alertabet' . DIRECTORY_SEPARATOR . 'games.php';
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
            $eventId = '59389731a98fcc9d8b8db8e3aee2a8d6';
            $endpoint = $this->apiUrl . "sports/$sport/scores/?apiKey=" . $this->getApiKeyAlertaBet() . "&eventIds=$eventId";
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
    public function getOddsByEventId($eventId, $sport, $bookmakers, $markets)
    {
        try {
            $client = new Client();
            $endpoint = $this->apiUrl . "sports/$sport/odds/?apiKey=" . $this->getApiKeyAlertaBet() . "&eventIds=$eventId&bookmakers=$bookmakers&markets=$markets";
            $response = $client->request('GET', $endpoint);
            $jsonResponse = $response->getBody();
            return json_decode($jsonResponse, true);
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

    public function getEventById($eventId, $sport)
    {
        try {
            $client = new Client();
            $endpoint = $this->apiUrl . "sports/$sport/scores/?apiKey=" . $this->getApiKeyAlertaBet() . "&eventIds=$eventId";
            $response = $client->request('GET', $endpoint);
            $jsonResponse = $response->getBody();
            return json_decode($jsonResponse, true);
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
