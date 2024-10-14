<?php
session_start();
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Tableau de Bord Bancaire</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Bienvenue, <?php echo htmlspecialchars($_SESSION['prenom'] . ' ' . $_SESSION['nom']); ?> !</h1>
    </header>
    <main class="container mt-5">
        <h2>Menu</h2>
        <ul class="list-group">
            <li class="list-group-item"><a href="deposer.php">Déposer de l'argent</a></li>
            <li class="list-group-item"><a href="retrait.php">Faire un retrait</a></li>
            <li class="list-group-item"><a href="virement.php">Effectuer un virement</a></li>
            <li class="list-group-item"><a href="solde.php">Consulter le solde</a></li>
            <li class="list-group-item"><a href="ajouter_compte.php">Ajouter un compte</a></li> 
            <li class="list-group-item"><a href="deconnexion.php">Se déconnecter</a></li>
        </ul>
    </main>
</body>
</html>