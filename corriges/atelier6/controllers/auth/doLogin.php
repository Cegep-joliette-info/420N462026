<?php
/** @var $bd PDO */
require_once('../../config.php');

// C'est un peu affreux de mettre des header exit autant que ça, est-ce vraiment pire que faire des gros if-else?

if (!isset($_POST['username'])) {
    header('Location: ../../index.php');
    exit();
}

if (!$_POST['username']) {
    $_SESSION['error'] = 'Veuillez saisir un nom d\'utilisateur';
    header('Location: ../../index.php');
    exit();
}
if (!$_POST['password']) {
    $_SESSION['error'] = 'Veuillez saisir un mot de passe';
    header('Location: ../../index.php');
    exit();
}

$utilisateur = Models\Utilisateurs::obtenirParNomUtilisateur($bd, $_POST['username']);

if ($utilisateur == null) {
    $_SESSION['error'] = 'Aucun utilisateur avec ce nom à été trouvé';
    header('Location: ../../index.php');
    exit();
}

if ($utilisateur->motDePasse != $_POST['password']) {
    $_SESSION['error'] = 'Le mot de passe n\'est pas valide';
    header('Location: ../../index.php');
    exit();
}

$_SESSION['username'] = $utilisateur->nomUtilisateur;
$_SESSION['success'] = 'Connection réussis!';
header('Location: ../home/dashboard.php');