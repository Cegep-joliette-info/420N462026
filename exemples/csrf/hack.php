<?php require '../config/header.php' ?>
<h1>CSRF</h1>
<h2>Exemple d'exploitation d'un formulaire non-sécuritaire</h2>
<p>Cliquez sur le bouton pour gagner 42$ !</p>
<form method="post" action="unsafe.php">
    <button name="btn" class="bg-blue-600 hover:bg-blue-700 text-white font-medium px-4 py-2 rounded">Ajouter à la version vulnérable</button>
</form>
<form method="post" action="safe.php">
    <button name="btn" class="bg-red-600 hover:bg-red-700 text-white font-medium px-4 py-2 rounded">Ajouter à la version protégé</button>
</form>
<?php require '../config/footer.php' ?>