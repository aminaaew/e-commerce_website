<?php
try {
$id = $_POST['idd'];
$email = $_POST['email'];
$pass = $_POST['pass'];
$role = $_POST['role'];

$pdo = new PDO('mysql:host=db;dbname=db', 'db', 'db');

$sql = "UPDATE  users SET email=:email, password=:pass, role=:role WHERE id=:id";

$stmt = $pdo->prepare($sql);

$stmt->bindValue(':email', $email, PDO::PARAM_STR);
$stmt->bindValue(":pass", $pass, PDO::PARAM_STR);
$stmt->bindValue(':role', $role, PDO::PARAM_STR);
$stmt->bindValue(':id', $id, PDO::PARAM_INT);

$stmt->execute();
    $header = "location:/index.php";
} catch(PDOException $e) {
    //$header = "location:/index.php?msg=une+erreur";
    print_r($e);
}

header($header);