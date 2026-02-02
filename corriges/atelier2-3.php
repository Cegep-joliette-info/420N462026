<?php
$nb = rand(-10, 10);
$pair = $nb % 2 == 0 ? 'pair' : 'impair';
?><!doctype html>
<html lang="fr-CA">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Atelier 2 - Numéro 3</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-white min-h-screen p-4">
    <?php if ($nb > 0) { ?>
        <p class="text-green-900 bg-green-200 text-3xl font-bold p-4 border-4 border-green-900 rounded-lg">Le nombre <?= $nb ?> est <?= $pair ?>.</p>
    <?php } elseif ($nb < 0) { ?>
        <p class="text-red-900 bg-red-200 text-3xl font-bold p-4 border-4 border-red-900 rounded-lg">Le nombre <?= $nb ?> est invalide.</p>
    <?php } else { ?>
        <p class="text-yellow-900 bg-yellow-200 text-3xl font-bold p-4 border-4 border-yellow-900 rounded-lg">Le nombre <?= $nb ?> est nul.</p>
    <?php } ?>
</body>
</html>
