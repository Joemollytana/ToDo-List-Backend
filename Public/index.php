<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;



require __DIR__ . '/../vendor/autoload.php';
require 'rb.php';

/* Connect to DB */
R::setup('mysql:host=localhost; dbname=todolistdb', 'root', '');

/* Creates Slim-Application */
$app = AppFactory::create();

/*
Needed for SLIM 4 --> definition of BasePath, so that router can find the URL
*/
$app->setBasePath((function () {
    $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    $uri = (string) parse_url('http://a' . $_SERVER['REQUEST_URI'] ?? '', PHP_URL_PATH);
    if (stripos($uri, $_SERVER['SCRIPT_NAME']) === 0) {
        return $_SERVER['SCRIPT_NAME'];
    }
    if ($scriptDir !== '/' && stripos($uri, $scriptDir) === 0) {
        return $scriptDir;
    }
    return '';
})());



/* 
Routing 
*/
/* GET-Requests */
// Welcome Screen
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to our To-Do-List");
    return $response;
});
// Get all tasklists of an user
$app->get('/tasklists', function (Request $request, Response $response, $args) {
    $tasklists = R::findAll('tasklist', 'user_id=:user_id', [':user_id'=>$request->getQueryParams()['user_id']]);
    $response->getBody()->write(json_encode(R::exportAll($tasklists)));
    return $response;
});
// Create new empty tasklist
$app->post('/newTasklist', function (Request $request, Response $response, $args) {
    $user_id = $request->getQueryParams()['user_id'];
    $newTasklist = R::dispense('tasklist');
    $newTasklist->user_id = $user_id;
    R::store($newTasklist);
    $response->getBody()->write(json_encode($newTasklist));
    return $response;
});
// Create new task
// Change task
// Delete tasklist
// Create new User
// Login --> compare input with DB-User

$app->run();

?> 
