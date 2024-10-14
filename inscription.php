<?php
$host = 'localhost';
$dbname = 'Banque';
$username = 'root';  
$password = '';      

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

session_start();

if (isset($_SESSION['client_id'])) {
    header('Location: index.php');
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nom = htmlspecialchars($_POST['nom']);
    $prenom = htmlspecialchars($_POST['prenom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);
    $mdp = $_POST['mdp']; // Mot de passe non-haché pour l'instant (devra être haché lors de l'inscription)


    if (!empty($nom) && !empty($prenom) && !empty($email) && !empty($mdp)) {
        $stmt = $pdo->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $existant = $stmt->fetch();

        if (!$existant) {
            $stmt = $pdo->prepare("INSERT INTO client (nom, prenom, telephone, email, mdp) VALUES (:nom, :prenom, :telephone, :email, :mdp)");
            $stmt->execute([
                'nom' => $nom,
                'prenom' => $prenom,
                'telephone' => $telephone,
                'email' => $email,
                'mdp' => $mdp
            ]);

            echo "Inscription réussie. Vous pouvez maintenant vous connecter.";
        } else {
            echo "Un compte avec cet email existe déjà.";
        }
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <title>Inscription</title>
    <script>
        function validerFormulaire() {
            const mdp = document.getElementById('mdp').value;

            // Vérifier si le mot de passe contient des espaces
            if (/\s/.test(mdp)) {
                alert("Le mot de passe ne doit pas contenir d'espaces.");
                return false;
            }

            return true; // Le formulaire peut être soumis
        }
    </script>
</head>
<body>
    <header class="bg-secondary p-4 text-center">
        <h1>Inscription</h1>
    </header>
    <main class="container mt-5">
        <!-- On ajoute onsubmit="return validerFormulaire()" -->
        <form method="POST" action="inscription.php" onsubmit="return validerFormulaire()">
            <div class="form-group">
                <label for="nom">Nom</label>
                <input type="text" class="form-control" id="nom" name="nom" placeholder="Nom" required>
            </div>
            <div class="form-group">
                <label for="prenom">Prénom</label>
                <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Prénom" required>
            </div>
            <div class="form-group">
                <label for="telephone">Téléphone</label>
                <input type="tel" class="form-control" id="telephone" name="telephone" placeholder="Téléphone">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" class="form-control" id="mdp" name="mdp" placeholder="Mot de passe" required>
            </div>
            <button type="submit" class="btn btn-primary">S’inscrire</button>
        </form>
        <p class="mt-3">Vous avez déjà un compte? <a href="connexion.php">Connectez-vous</a></p>
    </main>
</body>
</html>
