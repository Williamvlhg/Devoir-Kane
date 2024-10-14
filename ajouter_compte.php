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
    <title>Création de compte</title>
    <script>
        function validerFormulaire() {
            const numeroCompte = document.getElementById('numeroCompte').value;
            const solde = document.getElementById('solde').value;
            const typeDeCompte = document.getElementById('typeDeCompte').value;

            if (numeroCompte.length < 5 || numeroCompte.length > 15) {
                alert('Le numéro de compte doit contenir entre 5 et 15 caractères.');
                return false;
            }

            if (solde < 10 || solde > 2000) {
                alert('Le solde doit être compris entre 10 et 2000.');
                return false;
            }

            const typesValides = ['courant', 'epargne', 'entreprise'];
            if (!typesValides.includes(typeDeCompte.toLowerCase())) {
                alert('Le type de compte doit être "courant", "épargne", ou "entreprise".');
                return false;
            }



            return true;
        }
    </script>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Créer un compte</h1>
    </header>
    <main class="container mt-5">
        <form action="ajouter_compte.php" method="POST" onsubmit="return validerFormulaire()">
            <div class="form-group">
                <label for="numeroCompte">Numéro de compte</label>
                <input type="text" class="form-control" id="numeroCompte" name="numeroCompte" placeholder="Numéro de compte" required>
            </div>
            <div class="form-group">
                <label for="solde">Solde initial</label>
                <input type="number" class="form-control" id="solde" name="solde" placeholder="Solde initial" min="10" max="2000" required>
            </div>
            <div class="form-group">
                <label for="typeDeCompte">Type de compte</label>
                <select class="form-control" id="typeDeCompte" name="typeDeCompte" required>
                    <option value="">--Choisissez un type de compte--</option>
                    <option value="courant">Courant</option>
                    <option value="epargne">Épargne</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary mt-3">Créer compte</button>
        </form>
    </main>
</body>
</html>
