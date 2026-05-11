<?php 


$user = '/;
$pass = '/';


$dsn = 'mysql:host=localhost;dbname=valor;charset=utf8mb4';


try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO:: ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    die("connection failed" . $e->getMessage());
}