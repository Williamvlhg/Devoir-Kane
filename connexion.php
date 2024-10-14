<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['mdp'];

    if (!empty($email) && !empty($mdp)) {
        
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $client = $stmt->fetch();

        //Normalement ici c'est un password verify mais comme j'ai retiré la fonction de hash on ne l'utilisera pas
        if ($client && $mdp == $client['mdp']) {
            
            $_SESSION['client_id'] = $client['clientId'];
            $_SESSION['prenom'] = $client['prenom'];
            $_SESSION['nom'] = $client['nom'];

            
            header('Location: index.php');
            exit();
        } else {
            echo "Identifiants incorrects.";
        }
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Connexion</title>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Connexion</h1>
    </header>
    <main class="container mt-5">
        <form method="POST" action="connexion.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>
        <p class="mt-3">Pas encore de compte? <a href="inscription.php">Inscrivez-vous ici</a></p>
    </main>
</body>
</html>