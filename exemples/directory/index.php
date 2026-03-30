<?php
$files = glob(__DIR__ . '/../config/files/*');
if (isset($_GET['file'])) {
    header('Content-Type: text/plain');
    flush();
    readfile(__DIR__ . '/../config/files/' . $_GET['file']);
    die();
}
?>
<?php require '../config/header.php' ?>
<h1>Traversée de dossiers</h1>
<p class="bg-blue-100 border border-blue-400 text-blue-800 px-4 py-3 rounded mb-4">Fichiers téléchargeables</p>
<ul>
    <?php foreach ($files as $file) { ?>
        <li><a href="?file=<?= basename($file) ?>"><?= basename($file) ?></a></li>
    <?php } ?>
</ul>
<?php require '../config/footer.php' ?>