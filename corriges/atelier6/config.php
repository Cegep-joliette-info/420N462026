<?php

// L'autoload doit être avant le sessionstart pour que les classes soient disponibles dans les sessions
spl_autoload_register(function($className) {
    $className = str_replace("\\", DIRECTORY_SEPARATOR, strtolower($className));
    include_once __DIR__ . DIRECTORY_SEPARATOR . $className . '.php';
});

session_start();

try {
    $bd = new PDO('mysql:dbname=atelier6;host=172.17.0.1', 'root', 'root');
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit();
}