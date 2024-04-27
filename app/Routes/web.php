<?php

use App\Controllers\AlertaBetController;

$this->router->get('/', [AlertaBetController::class, 'index']);
$this->router->get('/jogos', [AlertaBetController::class, 'games']);
$this->router->get('/esportes', [AlertaBetController::class, 'getSports']);
