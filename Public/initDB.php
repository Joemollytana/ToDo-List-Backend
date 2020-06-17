<?php
require 'rb.php';

/* Connection to pre-created database "todolistdb" as root */
R::setup('mysql:host=localhost; dbname=todolistdb', 'root', '');


/* Necessary tables */
$user = R::dispense('user');
$task1 = R::dispense('tasklist');
$task2 = R::dispense('tasklist');
$usertotask = R::dispense('usertotask');


/* Usertable */
$user->username = "test";
$user->password = "asdf";


/* Task */
$task1->taskname = "Wash dishes";
//$task1->description = "I have to wash my dishes.";
//$task1->scope = "large";
$task1->done = false;

$task2->taskname = "Develop Web-App";
//$task2->description = "I have to develop a Web-App with PHP.";
//$task2->scope = "large";
$task2->done = false;


/* Table to assign User to Tasklist (1:n) */
$usertotask->user = $user;
$usertotask->xownTasklistList[] = $task1;
$usertotask->xownTasklistList[] = $task2;


/* Store the tuples in database */
$id = R::store($usertotask);

R::close();
?>
