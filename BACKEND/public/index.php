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

/* Needed for SLIM 4 --> definition of BasePath, so that router can find the URL */
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

// Show all user
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

// Get all tasklists of an user, with all tasks and all user-information
$app->get('/tasklists', function (Request $request, Response $response, $args) {
    $tasklists = R::findAll('tasklist', 'user_id=:user_id', [':user_id'=>$request->getQueryParams()['user_id']]);
    foreach ($tasklists as $tasklist) {
        $tasklist->user;
    }
    $response->getBody()->write(json_encode(R::exportAll($tasklists)));
    return $response;
});

// Get one special tasklist by id, with all tasks and all user-information
$app->get('/tasklist/{tasklistId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    $tasklist->user;
    $response->getBody()->write(json_encode(R::exportAll($tasklist)));
    return $response;
});



/* POST-Requests */

// Create new empty tasklist
$app->post('/tasklist', function (Request $request, Response $response, $args) {
    $parsedBody = $request->getParsedBody();

    $tasklist = R::dispense('tasklist'); 
    $user = R::load('user', $parsedBody['user_id']);
    $tasklist->user = $user;

    R::store($tasklist);

    $response->getBody()->write(json_encode($tasklist));
    return $response;
});

// Create new task
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

// Create user
$app->post('/user', function (Request $request, Response $response, $args) {
$parsedBody = $request->getParsedBody();

$user = R::dispense('user');
$user->username = $parsedBody['username'];
$user->password = password_hash($parsedBody['password'], PASSWORD_DEFAULT);

R::store($user);

$response->getBody()->write(json_encode($user));
return $response;
});


//login ****************+ nochmal überdenken
$app->post('/login', function (Request $request, Response $response, $args){
    $pasedBody = $request->getParsedBody();


});



/* DELETE-Requests */

// Delete tasklist with chosen id (ACHTUNG, nicht berechtigte user können das auch!)
$app->delete('/tasklist/deleteList/{tasklistId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    R::trash($tasklist);
    $response->getBody()->write('Löschvorgang erfolgreich');
    return $response;
});

// delete task in a tasklist
$app->delete('/tasklist/deleteTask/{tasklistId}/{taskId}', function (Request $request, Response $response, $args) {
    $tasklist = R::load('tasklist', $args['tasklistId']);
    $task = $tasklist->xownTasksList[$args['taskId']];
    if ($task->status == "erledigt") {
        $response->getBody()->write("task ist erledigt und kann nicht gelöscht werden");
    } else {
        unset($tasklist->xownTasksList[$args['taskId']]);
        $response->getBody()->write(json_encode($tasklist));
        R::store( $tasklist );
    }
    return $response;
});

// Delete a user by his ID and all of his tasklists (and tasks)
$app->delete('/user/{user_id}', function (Request $request, Response $response, $args) {
    $user = R::load('user', $args['user_id']);
    $tasklist = R::find('tasklist', 'user_id = ? ', [$args['user_id']]);
    R::trash($user);
    R::trashAll($tasklist);
    $response->getBody()->write("done");
    return $response;
});



/* PUT-Requests */

// Change an existing tasklist
$app->put('/tasklist/{tlid}', function (Request $request, Response $response, $args) {
    $parsedBody = json_decode((string)$request->getBody(), true);
    $tasklist = R::load('tasklist', $args['tlid']);

    //$tasklist->user_id = $tasklist->user_id;
    $tasklist->user->name = $tasklist->user->name;
    $tasklist->user->password = $tasklist->user->password;

    //$tasklist->xownTasksList = [];
    $i = 1;
    foreach( $parsedBody['ownTasks'] as $z) {
        $task = R::load('tasks', $i);
        if ($task->status == 'erledigt' || $task->status == 'verspätet erledigt') {
            $task->taskname = $task->taskname;
            $task->description = $task->description;
            $task->scope = $task->scope;
            $task->deadline = $task->deadline;
            $task->status = $task->status;
            $task->tasklist_id = $task->tasklist_id;
            $tasklist->ownTasksList[] = $task;
        }
        else {
            $task->id = $z['id'];
            $task->taskname = $z['taskname'];
            $task->description = $z['description'];
            $task->scope = $z['scope'];
            $task->deadline = $z['deadline'];
            $task->status = $z['status'];
            $task->tasklist_id = $task->tasklist_id;
            $tasklist->ownTasksList[] = $task; 
        }
        $i += 1;
    }
    R::store($tasklist);
    $response->getBody()->write(json_encode($tasklist));
    return $response;
});
// Update an existing Useraccount 
$app->put('/user/{uid}', function (Request $request, Response $response, $args) {
    $parsedBody = json_decode((string)$request->getBody(), true);
    $user = R::load('user', $args['uid']);
    $user->username = $user->username;
    $user->password = password_hash($parsedBody['password'], PASSWORD_DEFAULT);
    R::store($user);
    $response->getBody()->write(json_encode($user));
    return $response;
});

// bearbeiten 'if' testen
// create user testen

$app->run();

?> 
