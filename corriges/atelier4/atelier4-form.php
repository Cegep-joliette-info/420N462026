<?php
try {
    $bd = new PDO('mysql:dbname=test;host=172.17.0.1', 'root', 'root');
} catch (PDOException $e) {
    echo 'Connexion échouée : ' . $e->getMessage();
    exit();
}

// Note, j'ai fait create et edit dans le même fichier, ça fait un peu spaghetti mais ça évite de dupliquer le code
// Avec du recule, j'aurais fait 2 fichiers séparés
// On va voir plus tard comment mieux organiser ça (avec de l'orienté objet)

$error = '';
$name = '';
$category_id = '';
$title = 'Nouveau jeu';
$action = 'Ajouter';

if (isset($_GET['id'])) {
    $title = 'Modifier un jeu';
    $action = 'Modifier';
    $query = $bd->prepare('SELECT * FROM games WHERE id = ?');
    $query->bindParam(1, $_GET['id'], PDO::PARAM_INT);
    $query->execute();
    $game = $query->fetch(PDO::FETCH_ASSOC);
    if ($game) {
        $name = $game['name'];
        $category_id = $game['category_id'];
    } else {
        session_start();
        $_SESSION['error_message'] = 'Le jeu demandé est introuvable.';
        header('Location: atelier4.php');
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ces validations ne sont pas demandés, mais une bonne idée de les faire
    $name = $_POST['name'] ?? '';
    $category_id = $_POST['category_id'] ?? '';
    if (!$name || !$category_id) {
        $error = 'Tous les champs sont requis.';
    } elseif (strlen($name) > 50) {
        $error = 'Le nom du jeu ne peut pas dépasser 50 caractères.';
    } else {
        // Vérifier que la catégorie existe
        $query = $bd->prepare('SELECT COUNT(*) FROM categories WHERE id = ?');
        $query->bindParam(1, $category_id, PDO::PARAM_INT);
        $query->execute();
        if ($query->rowCount() == 0) {
            $error = 'La catégorie sélectionnée est invalide.';
        }
    }
    if (!$error) {
        if (isset($_GET['id'])) {
            $query = $bd->prepare('UPDATE games SET name = ?, category_id = ? WHERE id = ?');
            $query->bindParam(3, $_GET['id'], PDO::PARAM_INT);
        }
        else {
            $query = $bd->prepare('INSERT INTO games (name, category_id) VALUES (?, ?)');
        }
        $query->bindParam(1, $name);
        $query->bindParam(2, $category_id, PDO::PARAM_INT);
        $query->execute();

        session_start();
        if (isset($_GET['id'])) {
            $_SESSION['success_message'] = 'Le jeu a été mis à jour avec succès.';
        } else {
            $_SESSION['success_message'] = 'Le jeu a été ajouté avec succès.';
        }

        header('Location: atelier4.php');
        exit();
    }
}

// Important de mette cette requête après le traitement du POST, sinon on pourrait chercher les catégories inutilement lorsqu'on insert ou modifie
$query = $bd->query('SELECT * FROM categories ORDER BY name ASC');
$categories = $query->fetchAll(PDO::FETCH_ASSOC);
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title><?= $title ?> - Atelier 4</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="min-h-screen bg-slate-50 text-slate-900 dark:bg-slate-900 dark:text-slate-50">
<div class="mx-auto max-w-3xl px-4 py-10">
    <header class="flex items-center justify-between gap-4">
        <h1 class="text-3xl font-semibold tracking-tight"><?= $title ?></h1>
        <a href="atelier4.php"
           class="inline-flex items-center gap-2 rounded-md border border-slate-900 bg-slate-900 px-3 py-2 text-sm font-semibold text-white shadow-sm outline-none ring-offset-2 transition hover:bg-slate-800 focus-visible:ring-2 focus-visible:ring-slate-900 dark:border-slate-50 dark:bg-slate-50 dark:text-slate-900 dark:hover:bg-slate-100 dark:focus-visible:ring-slate-50"
           aria-label="Retour à la liste">
            <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            <span class="hidden sm:inline">Retour</span>
        </a>
    </header>


    <?php if ($error) { ?>
        <div class="rounded-lg border border-red-300 bg-red-50 p-4 mt-8 dark:border-red-700 dark:bg-red-900">
            <div class="flex items-center gap-3">
                <svg aria-hidden="true" class="h-5 w-5 flex-shrink-0 text-red-600 dark:text-red-400" viewBox="0 0 24 24" fill="currentColor">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-2h2v2zm0-4h-2V7h2v6z"/>
                </svg>
                <p class="text-sm font-medium text-red-800 dark:text-red-200"><?= $error ?></p>
            </div>
        </div>
    <?php } ?>

    <main class="mt-8">
        <form method="POST" class="space-y-6 rounded-lg border border-slate-300 bg-white p-6 shadow-sm dark:border-slate-700 dark:bg-slate-800">
            <div>
                <label for="name" class="mb-2 block text-sm font-semibold">
                    Nom du jeu
                </label>
                <input type="text" id="name" name="name" value="<?= $name ?>" required maxlength="50" class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm outline-none ring-offset-2 transition focus-visible:ring-2 focus-visible:ring-slate-900 dark:border-slate-600 dark:bg-slate-900 dark:focus-visible:ring-slate-50" aria-required="true"/>
            </div>

            <div>
                <label for="category_id" class="mb-2 block text-sm font-semibold">
                    Catégorie
                </label>
                <select id="category_id" name="category_id" required class="w-full rounded-md border border-slate-300 bg-white px-3 py-2 text-sm outline-none ring-offset-2 transition focus-visible:ring-2 focus-visible:ring-slate-900 dark:border-slate-600 dark:bg-slate-900 dark:focus-visible:ring-slate-50" aria-required="true">
                    <option value="">Sélectionner une catégorie</option>
                    <?php foreach ($categories as $category) { ?>
                        <option value="<?= $category['id'] ?>" <?= $category_id == $category['id'] ? 'selected' : '' ?>>
                            <?= $category['name'] ?>
                        </option>
                    <?php } ?>
                </select>
            </div>

            <div class="flex justify-end gap-3 pt-4">
                <button class="inline-flex cursor-pointer items-center gap-2 rounded-md border border-emerald-700 bg-emerald-700 px-4 py-2 text-sm font-semibold text-white shadow-sm outline-none ring-offset-2 transition hover:bg-emerald-800 focus-visible:ring-2 focus-visible:ring-emerald-700 dark:border-emerald-300 dark:bg-emerald-300 dark:text-emerald-950 dark:hover:bg-emerald-200 dark:focus-visible:ring-emerald-300">
                    <?= $action ?>
                </button>
            </div>
        </form>
    </main>
</div>
</body>
</html>
