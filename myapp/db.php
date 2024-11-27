<?php
$host = 'localhost'; // Remplacez 'db' par 'localhost'
$dbname = 'app_db';  // Nom de votre base de données
$username = 'root';  // Utilisateur par défaut de MySQL sur XAMPP
$password = '';      // Mot de passe vide par défaut sur XAMPP

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}
?>
