<?php

function nomComplet(string $nom, string $prenom): string {
    return $prenom . ' ' . $nom;
    // OU
    // return "$prenom $nom";
}

function estMajeur(int $age): bool {
    return $age >= 18;
}

function plusGrand(int ...$nbs): int {
    if (count($nbs) == 0) {
        // Pourrait aussi lancer une exception
        return 0;
    }
    $max = $nbs[0];
    foreach ($nbs as $nb) {
        $max = $nb > $max ? $nb : $max;
    }
    return $max;
}

echo 'Nom complet<br>';
echo nomComplet('Girard', 'Philippe') . '<br>';
echo nomComplet('Bond', 'James') . '<br>';
echo nomComplet('Gratton', 'Elvis') . '<br>';
echo '<br>';

echo 'Est majeur<br>';
echo estMajeur(2) ? 'Oui<br>' : 'Non<br>';
echo estMajeur(9001) ? 'Oui<br>' : 'Non<br>';
echo estMajeur(17) ? 'Oui<br>' : 'Non<br>';
echo estMajeur(18) ? 'Oui<br>' : 'Non<br>';
echo '<br>';

echo 'Plus grand<br>';
echo plusGrand(2, 3, 4, 5, 6, 7, 8, 9, 10) . '<br>';
echo plusGrand(-2, -3, -4, -5, -6, -7, -8, -9, -10) . '<br>';
echo plusGrand(42, 9001, 7, 13, 0, -42) . '<br>';
echo plusGrand();