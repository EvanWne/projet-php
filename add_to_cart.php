<?php
require_once 'includes/header.php';

echo "<h3>Test Add to Cart</h3>";

if (!isset($_SESSION['user_id'])) {
    echo "<div class='alert alert-danger'>Vous devez être connecté pour ajouter des films au panier.</div>";
    echo "<p><a href='pages/login.php'>Aller à la page de connexion</a></p>";
    require_once 'includes/footer.php';
    exit;
}

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de film invalide.</div>";
    require_once 'includes/footer.php';
    exit;
}

$movie_id = (int)$_GET['id'];
$user_id = $_SESSION['user_id'];

echo "Utilisateur ID : " . $user_id . "<br>";
echo "Film ID : " . $movie_id . "<br>";

$stmt = $pdo->prepare("SELECT id, title FROM movies WHERE id = ?");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    echo "<div class='alert alert-danger'>Ce film n'existe pas.</div>";
    require_once 'includes/footer.php';
    exit;
}

echo "Film trouvé : " . htmlspecialchars($movie['title']) . "<br>";

try {
    $stmt = $pdo->prepare("
        INSERT INTO cart (user_id, movie_id) 
        VALUES (?, ?) 
        ON DUPLICATE KEY UPDATE added_at = CURRENT_TIMESTAMP
    ");
    $stmt->execute([$user_id, $movie_id]);

    echo "<div class='alert alert-success'>Film ajouté au panier avec succès !</div>";
    echo "<p><a href='cart.php' class='btn btn-primary'>Voir mon panier</a></p>";

} catch (PDOException $e) {
    echo "<div class='alert alert-danger'>Erreur SQL : " . $e->getMessage() . "</div>";
}

require_once 'includes/footer.php';
?>