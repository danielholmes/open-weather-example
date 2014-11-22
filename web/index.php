<?php

require __DIR__ . '/../vendor/autoload.php';

$controller = new \OpenWeatherExample\Web\FrontController();
$controller->run($_GET);