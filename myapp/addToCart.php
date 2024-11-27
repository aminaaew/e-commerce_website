<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'guest') {
    header("Location: login.php");
    exit();
}

// Include database connection
require 'db.php';

// Get product ID from POST data
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];
    $user_id = $_SESSION['user_id'];

    // Insert into cart table
    $stmt = $pdo->prepare("INSERT INTO cart (user_id, product_id) VALUES (:user_id, :product_id)");
    $stmt->execute(['user_id' => $user_id, 'product_id' => $product_id]);

    header("Location: products_page.php?msg=Produit ajouté au panier&type=success");
    exit();
} else {
    header("Location: products_page.php?msg=Erreur lors de l'ajout&type=danger");
    exit();
}
?>
