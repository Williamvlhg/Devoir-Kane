<?php

include 'db.php';

if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $numeroCompte = htmlspecialchars($_POST['numeroCompte']);
    $solde = floatval($_POST['solde']);
    $typeDeCompte = htmlspecialchars($_POST['typeDeCompte']);
    $clientId = $_SESSION['client_id']; 

    if (!empty($numeroCompte) && $solde >= 10 && in_array($typeDeCompte, ['courant', 'epargne', 'entreprise'])) {
        $stmt = $pdo->prepare("SELECT * FROM comptebancaire WHERE numeroCompte = :numeroCompte");
        $stmt->execute(['numeroCompte' => $numeroCompte]);
        $existant = $stmt->fetch();

        if (!$existant) {
            $stmt = $pdo->prepare("INSERT INTO comptebancaire (numeroCompte, solde, typeDeCompte, clientId) VALUES (:numeroCompte, :solde, :typeDeCompte, :clientId)");
            $stmt->execute([
                'numeroCompte' => $numeroCompte,
                'solde' => $solde,
                'typeDeCompte' => $typeDeCompte,
                'clientId' => $clientId
            ]);

            echo "Compte créé avec succès.";
        } else {
            echo "Un compte avec ce numéro existe déjà.";
        }
    } else {
        echo "Veuillez remplir tous les champs correctement.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Créer un compte</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Créer un compte</h1>
    </header>
    <main class="container mt-5">
        <form method="POST" action="ajouter_compte.php">
            <div class="form-group">
                <label for="numeroCompte">Numéro de compte</label>
                <input type="text" class="form-control" id="numeroCompte" name="numeroCompte" placeholder="Numéro de compte" required>
            </div>
            <div class="form-group">
                <label for="solde">Solde</label>
                <input type="number" class="form-control" id="solde" name="solde" placeholder="Solde initial" min="10" max="2000" required>
            </div>
            <div class="form-group">
                <label for="typeDeCompte">Type de compte</label>
                <select class="form-control" id="typeDeCompte" name="typeDeCompte" required>
                    <option value="courant">Courant</option>
                    <option value="epargne">Épargne</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Créer compte</button>
        </form>
    </main>
</body>
</html>