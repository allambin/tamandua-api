<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once dirname(__FILE__) . '/vendor/autoload.php';

$app = new Slim\App;

$app->get('/', function(Request $request, Response $response) {
    $response->getBody()->write("This is the index.");
    return $response;
});
$app->get('/hello', function(Request $request, Response $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->run();