<?php
session_start();
$error = false;
if (isset($_POST['btn'])) {
    if (isset($_POST['csrf']) && $_POST['csrf'] == $_SESSION['csrf']) {
        $_SESSION['csrf_count'] = ($_SESSION['csrf_count'] ?? 0) + 1;
    }
    else {
        $error = 'Erreur lors de l\'envoie, veuillez réessayer';
    }
}
$_SESSION['csrf'] = bin2hex(random_bytes(16));
?>
<?php require '../config/header.php' ?>
<h1>CSRF</h1>
<h2>Exemple sécuritaire</h2>
<?php if ($error) { ?>
    <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-4"><?= $error ?></div>
<?php } ?>
<p>La page a été soumise <?= $_SESSION['csrf_count'] ?? 0 ?></p>
<form method="post">
    <input type="hidden" name="csrf" value="<?= $_SESSION['csrf'] ?>">
    <button name="btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded">Ajouter</button>
</form>
<div class="mt-3">
    <a href="hack.php" class="text-blue-600 hover:underline">Accéder à la page du pirate</a>
</div>
<?php require '../config/footer.php' ?>