<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Include database connection
    require 'db.php';

    try {
        // Query to fetch the user record by email
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            // Verify the password
            if (password_verify($password, $user['password'])) {
                // Store user info in session
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['role'] = $user['role'];

                // Redirect based on role
                if ($user['role'] === 'admin') {
                    header("Location: index.php"); // Admin Dashboard
                } else {
                    header("Location: products_page.php"); // Guest Product Page
                }
                exit();
            } else {
                $error = "Erreur : Mot de passe incorrect.";
            }
        } else {
            $error = "Erreur : Utilisateur non trouvé.";
        }
    } catch (PDOException $e) {
        $error = "Erreur de connexion à la base de données.";
    }
}
?>

<!-- Login Form -->
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
</head>
<body>
    <div class="container pt-4">
        <h1>Connexion</h1>
        <form method="POST" action="login.php">
            <div class="mb-3">
                <label for="email" class="form-label">Email :</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Mot de passe :</label>
                <input type="password" name="password" id="password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-primary">Se connecter</button>
        </form>

        <?php if (isset($error)): ?>
            <div class="alert alert-danger mt-3">
                <?= htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
