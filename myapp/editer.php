<?php
$id = $_GET['idd'];
$pdo = new PDO("mysql:host=db;dbname=db", 'db', 'db');
$sql = "SELECT * FROM users WHERE id=?";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(1, $id, PDO::PARAM_INT);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if(!$row) header("location:/");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title >Edit</title>
    <link rel="stylesheet" href="https://bootswatch.com/5/darkly/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="container pt-3">
        <h1>Edit user</h1>
        <form action="save.php" method="post">
            <input type="hidden" name="idd" value="<?= $id ?>">
            <input type="text" class="form-control"
            value="<?= $row['email'] ?>"
            placeholder="Email" name="email">
            <input type="text" class="form-control" 
            value="<?= $row['password'] ?>"
            placeholder="Password" name="pass">
            <select name="role" id="" class="form-select">
                <option value="">Choisir un role</option>
                <option value="guest" <?= ($row['role'] == 'guest') ? 'selected' : '' ?> >Guest</option>
                <option value="admin" <?= ($row['role'] == 'admin') ? 'selected' : '' ?> >Admin</option>
                <option value="author" <?= ($row['role'] == 'author') ? 'selected' : '' ?> >Author</option>
                <option value="editor" <?= ($row['role'] == 'gueditorest') ? 'selected' : '' ?> >Editor</option>
            </select>
            <button class="btn btn-success w-100 my-2">Enregistrer</button>
        </form>
    </div>
</body>
</html>