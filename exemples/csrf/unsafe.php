<?php
session_start();
if (isset($_POST['btn'])) {
    $_SESSION['csrf_count'] = ($_SESSION['csrf_count'] ?? 0) + 1;
}
?>
<?php require '../config/header.php' ?>
<h1>CSRF</h1>
<h2>Exemple non-sécuritaire</h2>
<p>La page a été soumise <?= $_SESSION['csrf_count'] ?? 0 ?></p>
<form method="post">
    <button name="btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded">Ajouter</button>
</form>
<div class="mt-3">
    <a href="hack.php" class="text-blue-600 hover:underline">Accéder à la page du pirate</a>
</div>
<?php require '../config/footer.php' ?>