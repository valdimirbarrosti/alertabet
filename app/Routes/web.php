<?php

use App\Controllers\AlertaBetController;

$this->router->get('/', [AlertaBetController::class, 'showIndex']);
$this->router->get('/jogos', [AlertaBetController::class, 'showGames']);
$this->router->get('/esportes', [AlertaBetController::class, 'getSports']);
$this->router->get('/eventos', [AlertaBetController::class, 'getEvents']);

