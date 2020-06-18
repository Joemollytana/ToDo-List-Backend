 <?php

require 'rb.php';

R::setup('mysql:host=localhost; dbname=DATENBANKNAME', 'USER', 'PWD'); //masteruser ?

$a = R::dispense('usertabelle');  //usertabelle wird erstellen
$a->name = "testuser";
$a->password = ""; //sha2 boi?
$a = R::store($a);
R::close();
R::freeze(true);
 ?>
