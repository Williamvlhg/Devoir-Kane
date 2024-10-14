<?php

include 'db.php';



if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $clientId = $_SESSION['client_id'];
    $typeDeCompte = $_POST['typeDeCompte'];

    if (!empty($typeDeCompte)) {
        $stmt = $pdo->prepare("SELECT * FROM comptebancaire WHERE clientId = :clientId AND typeDeCompte = :typeDeCompte");
        $stmt->execute(['clientId' => $clientId, 'typeDeCompte' => $typeDeCompte]);
        $compte = $stmt->fetch();

        if ($compte) {
            echo "Le solde de votre compte " . htmlspecialchars($typeDeCompte) . " est : " . number_format($compte['solde'], 2, ',', ' ') . " €.";
        } else {
            echo "Aucun compte trouvé pour le type sélectionné.";
        }
    } else {
        echo "Veuillez sélectionner un type de compte.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" crossorigin="anonymous">
    <title>Consulter le solde</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Consulter le solde</h1>
    </header>
    <main class="container mt-5">
        <form action="solde.php" method="POST">
            <div class="form-group">
                <label for="typeDeCompte">Sélectionnez le type de compte</label>
                <select id="typeDeCompte" name="typeDeCompte" class="form-control" required>
                    <option value="">--Choisissez un type de compte--</option>
                    <option value="courant">Courant</option>
                    <option value="epargne">Épargne</option>
                    <option value="entreprise">Entreprise</option>
                </select>
            </div>
            <input type="submit" class="btn btn-info mt-3" value="Consulter le solde">
        </form>
    </main>
</body>
</html>