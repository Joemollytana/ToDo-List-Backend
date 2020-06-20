<?php
/* Creates one user with two ToDo-Lists. Each tasklist contains three tasks. */

require 'rb.php';

echo "Die notwendige Datenbank wird für Sie mit Beispieldaten bestückt.\n";

/* Connection to pre-created database "todolistdb" as root */
R::setup('mysql:host=localhost; dbname=todolistdb', 'root', '');


/* Necessary tables */
$user = R::dispense('user');
$task1 = R::dispense('tasks');
$task2 = R::dispense('tasks');
$task3 = R::dispense('tasks');
$task4 = R::dispense('tasks');
$task5 = R::dispense('tasks');
$task6 = R::dispense('tasks');
$tasklist1 = R::dispense('tasklist');
$tasklist2 = R::dispense('tasklist');


/* Usertable */
$user->username = "test";
$user->password = password_hash("asdf", PASSWORD_DEFAULT);


/* Tasks */
$task1->taskname = "Wash dishes";
$task1->description = "I have to wash my dishes.";
$task1->scope = 4;
$task1->deadline = NULL;
$task1->status = "offen";

$task2->taskname = "Develop Web-App";
$task2->description = "I have to develop a Web-App with PHP.";
$task2->scope = 4;
$task2->deadline = date_create("2020-07-01");
$task2->status = "In Bearbeitung";

$task3->taskname = "Clean my desk";
$task3->description = "I need to clean my desk, before I start to develop the Web-App.";
$task3->scope = 1;
$task3->deadline = date_create("2020-06-17");
$task3->status = "verspätet erledigt";

$task4->taskname = "Lawn mowing";
$task4->description = "My lawn looks like a mess.";
$task4->scope = 2;
$task4->deadline = date_create("2020-06-28");
$task4->status = "abgebrochen";

$task5->taskname = "Study for the exam";
$task5->description = "I have to learn.";
$task5->scope = 5;
$task5->deadline = date_create("2020-06-15");
$task5->status = "erledigt";

$task6->taskname = "tax declaration";
$task6->description = "I need to do my tax declaration till 31.07.";
$task6->scope = 3;
$task6->deadline = date_create("2020-07-31");
$task6->status = "In Bearbeitung";


/* Table to assign User to Tasklist (1:n) */
/* Assign first Tasklist to user */
$tasklist1->user = $user;
$tasklist1->xownTasksList[] = $task1;
$tasklist1->xownTasksList[] = $task2;
$tasklist1->xownTasksList[] = $task3;

/* Assign second Tasklist to user */
$tasklist2->user = $user;
$tasklist2->xownTasksList[] = $task4;
$tasklist2->xownTasksList[] = $task5;
$tasklist2->xownTasksList[] = $task6;


/* Store the tuples in database */
$id = R::store($tasklist1);
$id = R::store($tasklist2);

/* Freeze database */
R::freeze( TRUE );

R::close();

?> 
