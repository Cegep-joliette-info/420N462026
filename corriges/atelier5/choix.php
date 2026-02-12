<?php
/** @var $bd PDO */
require_once('config.php');

if (!class_exists($_POST['classe'] ?? '')) {
    $_SESSION['error'] = 'Classe invalide';

    header('location: index.php');
    die();
}
$classe = new $_POST['classe']();
$_SESSION['joueur'] = $classe;

$monstre = $bd->query('SELECT * FROM `monstre` ORDER BY rand() LIMIT 1', PDO::FETCH_CLASS, 'Monstre')->fetch();
$_SESSION['monstre'] = $monstre;

header('location: combat.php');
// Pas besoin de die/exit puisque c'est la fin du script

// Ne pas oublier de ne pas fermer le script PHP