<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $compteID = $_SESSION['client_id'];
    $montant = (float)$_POST['montant'];
    $typeDeCompte = $_POST['typeDeCompte'];

    if (!empty($compteID) && $montant > 0) {
        $stmt = $pdo->prepare("SELECT * FROM comptebancaire WHERE clientId = :clientId"); 
        $stmt->execute(['clientId' => $compteID]);  
        $compte = $stmt->fetch(); 

        if ($compte) {
            if ($compte['solde'] >= $montant) {
                $nouveau_solde = $compte['solde'] - $montant;
                $update = $pdo->prepare("UPDATE comptebancaire SET solde = :solde WHERE numeroCompte = :numeroCompte AND typeDeCompte = :typeDeCompte");
                $update->execute(['solde' => $nouveau_solde, 'numeroCompte' => $compte['numeroCompte'], 'typeDeCompte' => $typeDeCompte]);

                echo "Retrait de $montant € effectué. Nouveau solde : $nouveau_solde €.";
            } else {
                echo "Solde insuffisant.";
            }
        } else {
            echo "Compte non trouvé.";
        }
    } else {
        echo "Montant invalide.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Retirer de l'argent</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Retirer de l'argent</h1>
    </header>
    <main class="container mt-5">
        <form action="retrait.php" method="POST">
            <div class="form-group">
            <label for="typeDeCompte">Sélectionnez le type de compte</label>
                <select id="typeDeCompte" name="typeDeCompte" class="form-control" required>
                    <option value="">--Choisissez un type de compte--</option>
                    <option value="courant">Courant</option>
                    <option value="epargne">Épargne</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>
            <div class="form-group">
                <label for="montant">Montant à retirer</label>
                <input type="number" id="montant" name="montant" class="form-control" required min="1">
            </div>
            <input type="submit" class="btn btn-danger mt-3" value="Retirer">
        </form>
    </main>
</body>
</html>