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
// Get all tasklists of an user, with all tasks and all user-information
$app->get('/tasklists', function (Request $request, Response $response, $args) {
    $tasklists = R::findAll('tasklist', 'user_id=:user_id', [':user_id'=>$request->getQueryParams()['user_id']]);
    foreach ($tasklists as $tasklist) {
        $tasklist->user;
    }
    $response->getBody()->write(json_encode(R::exportAll($tasklists)));
    return $response;
});
// ########################   Ist das so gewünscht von Perschke, weil sinnlos?? ##################################
// Get one special tasklist of an user, with all tasks and all user-information
$app->get('/tasklist', function (Request $request, Response $response, $args) {
    $listID = $request->getQueryParams()['tid'];
    $tasklist = R::load('tasklist', $listID);
    $tasklist->user;
    $response->getBody()->write(json_encode(R::exportAll($tasklist)));
    return $response;
});

/* POST-Requests */
// Create new empty tasklist
$app->post('/newTasklist', function (Request $request, Response $response, $args) {
    $user_id = $request->getQueryParams()['uid'];
    $newTasklist = R::dispense('tasklist');
    $newTasklist->user_id = $user_id;
    R::store($newTasklist);
    $response->getBody()->write(json_encode($newTasklist));
    return $response;
});
// Create new task
$app->post('/newTask', function (Request $request, Response $response, $args) {
    $taskname = $request->getQueryParams()['name'];
    $description = $request->getQueryParams()['desc'];
    $scope = $request->getQueryParams()['scope'];
    $deadline = $request->getQueryParams()['d'];
    $status = $request->getQueryParams()['stat'];
    $tasklist_id = $request->getQueryParams()['tid'];

    $newTask = R::dispense('tasks');

    $newTask->taskname = $taskname;
    $newTask->description = $description;
    $newTask->scope = $scope;
    $newTask->deadline = $deadline;
    $newTask->status = $status;
    $newTask->tasklist_id = $tasklist_id;

    R::store($newTask);
    $response->getBody()->write(json_encode($newTask));
    return $response;
});
// Change task / tasklist
// Registration --> Create new User
/* DELETE-Requests */
// Delete tasklist

// Login --> compare input with DB-User

$app->run();

?> 
