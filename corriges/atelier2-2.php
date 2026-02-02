<?php

function pgcd(int $nb1, int $nb2): int {
    // Pas optimisé, c'est la solution "brute-force"
    $diviseur = $nb1 < $nb2 ? $nb1 : $nb2;
    while ($diviseur > 1 && ($nb1 % $diviseur != 0 || $nb2 % $diviseur != 0)) {
        $diviseur--;
    }
    return $diviseur;
}

function ppcm(int $nb1, int $nb2): int {
    // Pas optimisé, c'est la solution "brute-force"
    $multiple1 = 1;
    $multiple2 = 1;

    while ($nb1 * $multiple1 != $nb2 * $multiple2) {
        if ($nb1 * $multiple1 < $nb2 * $multiple2) {
            $multiple1++;
        } else {
            $multiple2++;
        }
    }

    return $nb1 * $multiple1;
}

$nb1 = rand(1, 100);
$nb2 = rand(1, 100);
$pgcd = pgcd($nb1, $nb2);
$ppcm = ppcm($nb1, $nb2);
// Gardez les echo les plus simples possible
echo "PGCD de $nb1 et $nb2 est $pgcd";
echo "<br>";
echo "PPCM de $nb1 et $nb2 est $ppcm";
