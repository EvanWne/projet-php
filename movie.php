<?php
require_once 'includes/header.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo "<div class='alert alert-danger'>ID de film invalide.</div>";
    require_once 'includes/footer.php';
    exit;
}

$movie_id = (int)$_GET['id'];

$stmt = $pdo->prepare("
    SELECT m.*, d.name as director_name, c.name as category_name 
    FROM movies m 
    JOIN directors d ON m.director_id = d.id 
    JOIN categories c ON m.category_id = c.id 
    WHERE m.id = ?
");
$stmt->execute([$movie_id]);
$movie = $stmt->fetch();

if (!$movie) {
    echo "<div class='alert alert-danger'>Film non trouvé.</div>";
    require_once 'includes/footer.php';
    exit;
}
?>

<div class="row">
    <div class="col-md-4">
        <img src="<?= htmlspecialchars($movie['image']) ?>" 
             class="img-fluid rounded shadow" 
             alt="<?= htmlspecialchars($movie['title']) ?>"
             style="width: 100%; height: auto;">
    </div>
    
    <div class="col-md-8">
        <h1><?= htmlspecialchars($movie['title']) ?></h1>
        <p class="text-muted fs-5">
            Réalisé par : 
            <a href="bydirector.php?director_id=<?= $movie['director_id'] ?>">
                <?= htmlspecialchars($movie['director_name']) ?>
            </a>
        </p>
        
        <p><strong>Catégorie :</strong> <?= htmlspecialchars($movie['category_name']) ?></p>
        <p><strong>Prix :</strong> <span class="fs-4 text-success"><?= number_format($movie['price'], 2) ?> €</span></p>
        
        <h5 class="mt-4">Acteurs :</h5>
        <p><?= htmlspecialchars($movie['actors']) ?></p>
        
        <h5 class="mt-4">Description :</h5>
        <p><?= nl2br(htmlspecialchars($movie['description'])) ?></p>
        
        <div class="mt-4">
            <a href="add_to_cart.php?id=<?= $movie['id'] ?>" 
               class="btn btn-success btn-lg me-3">
                <i class="fas fa-cart-plus"></i> Ajouter au panier
            </a>
            
            <a href="index.php" class="btn btn-secondary btn-lg">
                Retour à l'accueil
            </a>
        </div>
    </div>
</div>

<?php require_once 'includes/footer.php'; ?>