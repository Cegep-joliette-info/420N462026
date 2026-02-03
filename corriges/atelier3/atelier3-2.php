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

$pgcd = '';
$ppcm = '';
$nb1 = $_POST['nombre1'] ?? '';
$nb2 = $_POST['nombre2'] ?? '';
if (isset($_POST['nombre1']) && isset($_POST['nombre2'])) {
    $nb1 = $nb1 > 0 ? intval($nb1) : 1;
    $nb2 = $nb2 > 0 ? intval($nb2) : 1;
    $pgcd = pgcd($nb1, $nb2);
    $ppcm = ppcm($nb1, $nb2);
}
?>
<!--
Prompt du HTML:
Crée une page HTML qui utilise tailwind, la page doit supporter le dark theme, être responsive et être accessible selon le WCAG niveau 2. Le titre de la page est "Atelier 3 - no2". La page contient un formulaire en post et contient les champs suivants:
Nombre 1, Nombre 2, PPCM (lecture seule), PGCD (lecture seule)
Et un bouton bleu Calculer. La page ne doit pas contenir de CSS ni JavaScript.
-->
<!DOCTYPE html>
<html lang="fr-CA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atelier 3 - no2</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-white transition-colors duration-300">
<div class="min-h-screen p-4 sm:p-8">
    <div class="max-w-md mx-auto">
        <h1 class="text-3xl sm:text-4xl font-bold mb-8 text-center">Atelier 3 - no2</h1>
        <form method="POST" class="space-y-6 border border-gray-300 dark:border-gray-600 rounded-lg p-6 bg-gray-50 dark:bg-gray-800">
            <div class="space-y-2">
                <label for="nombre1" class="block text-sm font-semibold">
                    Nombre 1
                </label>
                <input type="number" value="<?= $nb1 ?>" id="nombre1" name="nombre1" required aria-required="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <div class="space-y-2">
                <label for="nombre2" class="block text-sm font-semibold">
                    Nombre 2
                </label>
                <input type="number" value="<?= $nb2 ?>" id="nombre2" name="nombre2" required aria-required="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>

            <?php if ($ppcm !== '' && $pgcd !== '') { ?>
                <div class="space-y-2">
                    <label for="ppcm" class="block text-sm font-semibold">
                        PPCM
                    </label>
                    <input type="text" value="<?= $ppcm ?>" id="ppcm" name="ppcm" readonly aria-readonly="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-900 cursor-not-allowed">
                </div>

                <div class="space-y-2">
                    <label for="pgcd" class="block text-sm font-semibold">
                        PGCD
                    </label>
                    <input type="text" value="<?= $pgcd ?>" id="pgcd" name="pgcd" readonly aria-readonly="true" class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-100 dark:bg-gray-900 cursor-not-allowed">
                </div>
            <?php } ?>

            <button type="submit" class="w-full px-6 py-2 bg-blue-500 hover:bg-blue-600 active:bg-blue-700 text-white font-semibold rounded-lg transition-colors duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-gray-900">
                Calculer
            </button>
        </form>
    </div>
</div>
</body>
</html>

