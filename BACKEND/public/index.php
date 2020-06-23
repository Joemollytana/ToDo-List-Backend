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
Enable lazy CORS --> Allows the (external) Angular-Server to make a request to the Apache-Server (BE)
*/
$app->add(function ($request, $handler) {
    $response = $handler->handle($request);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});



/* 
Routing 
*/
/* GET-Requests */
// Welcome Screen
$app->get('/', function (Request $request, Response $response, $args) {
    $response->getBody()->write("Welcome to our To-Do-List");
    return $response;
});

// ******** die beiden geben ein gleiches ergebniss aus, sollte aber nicht so sein boi oder? *******
$app->get('/lists_user', function (Request $request, Response $response, $args) {
    $lists = R::findAll('user');
    $response->getBody()->write(json_encode($lists));
    return $response;
});
/* $app->get('/lists_list', function (Request $request, Response $response, $args) {
    $lists = R::findAll('tasklist');
    $response->getBody()->write(json_encode($lists)));
    return $response;
});
 */

/* GET-Requests */
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
// Get one special tasklist by id, with all tasks and all user-information
$app->get('/tasklist/{tasklistId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    $tasklist->user;
    $response->getBody()->write(json_encode(R::exportAll($tasklist)));
    return $response;
});


/* POST-Requests */
// Create new empty tasklist
/*$app->post('/newTasklist', function (Request $request, Response $response, $args) {
    $user_id = $request->getQueryParams()['uid'];
    $newTasklist = R::dispense('tasklist');
    $newTasklist->user_id = $user_id;
    R::store($newTasklist);
    $response->getBody()->write(json_encode($newTasklist));
    return $response;
});
*/
$app->post('/tasklist', function (Request $request, Response $response, $args) {
    $parsedBody = $request->getParsedBody();

    $tasklist = R::dispense('tasklist');
    //$tasklist->user_id = $parsedBody['user_id'];      // ### Alternative --> funktioniert genauso 
    $user = R::load('user', $parsedBody['user_id']);
    $tasklist->user = $user;

    R::store($tasklist);

    $response->getBody()->write(json_encode($tasklist));
    return $response;
});
// Create new task
/* $app->post('/newTask', function (Request $request, Response $response, $args) {
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
*/
$app->post('/task', function (Request $request, Response $response, $args) {
    $parsedBody = $request->getParsedBody();

    $task = R::dispense('tasks');
    $task->taskname = $parsedBody['taskname'];
    $task->description = $parsedBody['description'];
    $task->scope = $parsedBody['scope'];
    $task->deadline = $parsedBody['deadline'];
    $task->status = $parsedBody['status'];
    $task->tasklist_id = $parsedBody['tasklist_id'];

    R::store($task);

    $response->getBody()->write(json_encode($task));
    return $response;
});

/* DELETE-Requests */
// Delete tasklist with chosen id (ACHTUNG, nicht berechtigte user können das auch!)
$app->delete('/tasklist/deleteList/{tasklistId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    R::trash($tasklist);
    $response->getBody()->write('Löschvorgang erfolgreich');
    return $response;
});
//delete task in a tasklist
$app->delete('/tasklist/deleteTask/{tasklistId}/{taskId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    $task = $tasklist->xownTaskList[$args['taskId']];
    /* if $task->status == beendet */
    unset($tasklist->xownTasksList[$args['taskId']]);
    $response->getBody()->write(json_encode($tasklist));
    R::store( $tasklist );
    return $response;
});
$app->delete('/user/{user_id}', function (Request $request, Response $response, $args) {
    $user = R::load('user', $args['user_id']);
    R::trash($user);
    $response->getBody()->write("Löschvorgang erfolgreich");
    return $response;
});

// Change a task
$app->put('/task', function (Request $request, Response $response, $args) {
    $parsedBody = json_decode((string)$request->getBody(), true);

    $task = R::load('tasks', $parsedBody['id']);

    $task->taskname = $parsedBody['taskname'];
    $task->description = $parsedBody['description'];
    $task->scope = $parsedBody['scope'];
    $task->deadline = $parsedBody['deadline'];
    $task->status = $parsedBody['status'];
    $task->tasklist_id = $parsedBody['tasklist_id'];

    R::store($task);

    $response->getBody()->write(json_encode($task));
     return $response;
    });

/* DELETE-Requests */
// bearbeiten 'if'
// delete, create, change user
// Registration --> Create new User
// Delete tasklist
// Delete User


// Login --> compare input with DB-User

$app->run();

?> 