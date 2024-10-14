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
if (!isset($_SESSION['client_id'])) {
    header('Location: connexion.php');
    exit();
}
?>