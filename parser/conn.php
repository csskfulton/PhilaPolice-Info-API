<?php
$MySQLServer = '127.0.0.1:3306';
$MySQLServerUsername = 'gerry';
$MySQLPassword = 'Keithistheking';
$MySQLDatabase = 'PhillyPolice';

$NO_DATABASE = json_encode(array('Error'=>'Database Does Not Exist'));
$NO_CONNECTION = json_encode(array('Error'=>'Could Not Connect To Database'));

mysql_connect($MySQLServer, $MySQLServerUsername,$MySQLPassword)
or 
die($NO_CONNECTION. header('HTTP/1.1 403 Forbidden'));

mysql_select_db($MySQLDatabase)
or 
die($NO_DATABASE. header('HTTP/1.1 403 Forbidden'));

?>
