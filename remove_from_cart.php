<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: pages/login.php");
    exit;
}

if (!isset($_GET['cart_id']) || !is_numeric($_GET['cart_id'])) {
    header("Location: cart.php");
    exit;
}

$stmt = $pdo->prepare("DELETE FROM cart WHERE id = ? AND user_id = ?");
$stmt->execute([ (int)$_GET['cart_id'], $_SESSION['user_id'] ]);

$_SESSION['success'] = "Film retiré du panier.";
header("Location: cart.php");
exit;
?>