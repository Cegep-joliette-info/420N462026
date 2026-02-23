<?php
include 'produits.php';
/** @var $produits Produit[] */

// Je réindexe le tableau pour utiliser l'ID comme clé, vraiment pas optimal, mais simplifie le code
$produits = array_column($produits, null, 'id');

session_start();
if (count($_SESSION) == 0) {
    $_SESSION['produits'] = [];
    foreach ($produits as $produit) {
        $_SESSION['produits'][$produit->id] = 0;
    }
}

header('Content-Type: application/json');

if (!isset($_GET['refresh'])) {
    $id = intval($_POST['id'] ?? -1);
    $qte = intval($_POST['quantity'] ?? 0);

    if (!isset($_SESSION['produits'][$id])) {
        http_response_code(400);
        echo json_encode("Le produit n'existe pas");
        die();
    }
    if ($_SESSION['produits'][$id] == 0 && $qte < 0) {
        http_response_code(400);
        echo json_encode("Quantité invalide");
        die();
    }

    $_SESSION['produits'][$id] += $qte;
}

// À éviter, trop complexe pour 1 ligne de code
$sousTotal = array_reduce($produits, fn(float $sousTotal, Produit $produit): float => $sousTotal + $_SESSION['produits'][$produit->id] * $produit->prix, 0);
$tps = round($sousTotal * 0.05, 2);
$tvq = round($sousTotal * 0.09975, 2);
$resultat = [
    'produits' => $_SESSION['produits'],
    'nb' => array_sum($_SESSION['produits']),
    'sousTotal' => $sousTotal,
    'tps' => $tps,
    'tvq' => $tvq,
    'total' => $sousTotal + $tps + $tvq
];
echo json_encode($resultat);