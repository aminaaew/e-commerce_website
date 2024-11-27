<?php
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Secure password
    $role = $_POST['role'];

    $sql = "INSERT INTO users (email, password, role) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$email, $password, $role]);

    echo "User added successfully.";
}
?>
<form method="post">
    <input type="email" name="email" placeholder="Email" required>
    <input type="password" name="password" placeholder="Password" required>
    <select name="role">
        <option value="guest">Guest</option>
        <option value="admin">Admin</option>
    </select>
    <button type="submit">Add User</button>
</form>
