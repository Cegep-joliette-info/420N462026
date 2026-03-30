<?php
$HOST     = 'host.docker.internal';
$PORT     = 3306;
$DBNAME   = 'security';
$USER     = 'root';
$PASSWORD = 'root';

$db = new PDO("mysql:host=$HOST;port=$PORT;dbname=$DBNAME", $USER, $PASSWORD);