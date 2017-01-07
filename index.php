<?php
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

require_once dirname(__FILE__) . '/config.php';
require_once dirname(__FILE__) . '/vendor/autoload.php';

$app = new Slim\App($config);

// Eloquent connection
$container = $app->getContainer();
$capsule = new Illuminate\Database\Capsule\Manager;
$capsule->addConnection($container->get('settings')['db']);
$capsule->bootEloquent();

// Routing
$app->get('/', function(Request $request, Response $response) {
    $response->getBody()->write("This is the index.");
    return $response;
});
$app->get('/hello', function(Request $request, Response $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});

$app->post('/users', 'Inextends\Tamandua\APIController:register');
$app->post('/users/login', 'Inextends\Tamandua\APIController:login');

$app->run();