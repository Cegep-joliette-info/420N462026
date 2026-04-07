<?php
$search = $_GET['s'] ?? null;
?>
<?php require '../config/header.php' ?>
<p><a href="index.php">Retour</a></p>
<h1>XSS temporaire</h1>
<h2>Recherche</h2>
<?php if ($search) { ?>
    <p>Votre recherche: <?= $search ?></p>
<?php } ?>
<form method="get">
    <div class="mb-2">
        <input class="border border-gray-300 rounded px-3 py-2 w-full focus:outline-none focus:ring-2 focus:ring-blue-500" name="s"/>
    </div>
    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">Rechercher</button>
    <p class="text-gray-500 text-sm">Rechercher: &lt;script&gt;alert('toto');&lt;/script&gt;</p>
</form>
<?php require '../config/footer.php' ?>