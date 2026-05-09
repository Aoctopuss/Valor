<?php


session_start();

$user_id = $_SESSION['user_id'] ?? null;

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}
