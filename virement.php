<?php

include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $prenom_source = $_POST['prenom_source'];
    $prenom_dest = $_POST['prenom_dest'];
    $montant = (float)$_POST['montant'];

    if (!empty($prenom_source) && !empty($prenom_dest) && $montant > 0) {
        $stmt_source = $pdo->prepare("SELECT * FROM comptebancaire INNER JOIN client ON comptebancaire.clientId = client.clientId WHERE client.prenom = :prenom");
        $stmt_dest = $pdo->prepare("SELECT * FROM comptebancaire INNER JOIN client ON comptebancaire.clientId = client.clientId WHERE client.prenom = :prenom");

        $stmt_source->execute(['prenom' => $prenom_source]);
        $stmt_dest->execute(['prenom' => $prenom_dest]);

        $compte_source = $stmt_source->fetch();
        $compte_dest = $stmt_dest->fetch();

        if ($compte_source && $compte_dest && $compte_source['solde'] >= $montant) {

            $nouveau_solde_source = $compte_source['solde'] - $montant;
            $nouveau_solde_dest = $compte_dest['solde'] + $montant;

            $update_source = $pdo->prepare("UPDATE comptebancaire SET solde = :solde WHERE numeroCompte = :numeroCompte");
            $update_dest = $pdo->prepare("UPDATE comptebancaire SET solde = :solde WHERE numeroCompte = :numeroCompte");

            $update_source->execute(['solde' => $nouveau_solde_source, 'numeroCompte' => $compte_source['numeroCompte']]);
            $update_dest->execute(['solde' => $nouveau_solde_dest, 'numeroCompte' => $compte_dest['numeroCompte']]);

            echo "Virement de $montant € effectué de $prenom_source vers $prenom_dest.";
        } else {
            echo "Solde insuffisant ou compte(s) non trouvé(s).";
        }
    } else {
        echo "Montant ou informations invalides.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Effectuer un virement</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Effectuer un virement</h1>
    </header>
    <main class="container mt-5">
        <form action="virement.php" method="POST">
            <div class="form-group">
                <label for="prenom_source">Prénom du compte source</label>
                <input type="text" id="prenom_source" name="prenom_source" class="form-control" required minlength="2">
            </div>
            <div class="form-group">
                <label for="prenom_dest">Prénom du compte destinataire</label>
                <input type="text" id="prenom_dest" name="prenom_dest" class="form-control" required minlength="2">
            </div>
            <div class="form-group">
                <label for="montant">Montant à transférer</label>
                <input type="number" id="montant" name="montant" class="form-control" required min="1">
            </div>
            <input type="submit" class="btn btn-primary mt-3" value="Effectuer le virement">
        </form>
    </main>
</body>
</html>