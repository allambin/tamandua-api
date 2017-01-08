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

$mw = function ($request, $response, $next) {
    try {
        Inextends\Tamandua\APIController::isTokenValid($request, $response);
    } catch (Exception $e) {
        return \Inextends\Tamandua\ResponseHelper::sendJsonErrorResponse($response, $e);
    }
    
    $response = $next($request, $response);
    return $response;
};

// Routing
$app->get('/', function(Request $request, Response $response) {
    $response->getBody()->write("This is the index.");
    return $response;
});
$app->get('/hello', function(Request $request, Response $response) {
    $response->getBody()->write("Hello world!");
    return $response;
});
$app->get('/restricted', function(Request $request, Response $response) {
    $response->getBody()->write("Restricted area");
    return $response;
})->add($mw);

$app->post('/users', 'Inextends\Tamandua\APIController:register');
$app->post('/users/login', 'Inextends\Tamandua\APIController:login');
$app->post('/projects', 'Inextends\Tamandua\APIController:createProject')->add($mw);
$app->put('/projects/{id:[0-9]+}', 'Inextends\Tamandua\APIController:updateProject')->add($mw);

$app->run();