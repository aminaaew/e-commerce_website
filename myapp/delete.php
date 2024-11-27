<?php

$id = $_GET['idd'];

$pdo = new PDO('mysql:host=db;dbname=db', 'db', 'db');

$sql = "DELETE FROM users WHERE id=$id";

$ret = $pdo->exec($sql);

header("location:/index.php");