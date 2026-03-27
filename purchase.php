<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) 
    {
        $_SESSION['error'] = "Vous devez être connecté pour effectuer un achat.";
        header("Location: pages/login.php");
        exit;
    }

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare(
    "SELECT c.id as cart_id, m.id as movie_id, m.title, m.price 
    FROM cart c 
    JOIN movies m ON c.movie_id = m.id 
    WHERE c.user_id = ?");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

if (empty($cart_items)) 
    {
        $_SESSION['error'] = "Votre panier est vide.";
        header("Location: cart.php");
        exit;
    }

$total = 0;
foreach ($cart_items as $item) 
    {
        $total += $item['price'];
    }

try {
    $pdo->beginTransaction();

    foreach ($cart_items as $item) 
        {
            $stmt = $pdo->prepare("INSERT INTO purchases (user_id, movie_id) VALUES (?, ?)");
            $stmt->execute([$user_id, $item['movie_id']]);
        }

    $stmt = $pdo->prepare("DELETE FROM cart WHERE user_id = ?");
    $stmt->execute([$user_id]);

    $pdo->commit();

    $_SESSION['success'] = "Achat effectué avec succès ! Total : " . number_format($total, 2) . " €";
    header("Location: pages/profile.php");
    exit;

} catch (Exception $e) {
    $pdo->rollBack();
    $_SESSION['error'] = "Une erreur est survenue lors de l'achat.";
    header("Location: cart.php");
    exit;
}
?>