<?php
require_once 'includes/header.php';

$category_name = isset($_GET['cat']) ? trim($_GET['cat']) : '';

if (!in_array($category_name, ['Action', 'Drama'])) {
    echo "<div class='alert alert-danger'>Catégorie invalide.</div>";
    require_once 'includes/footer.php';
    exit;
}

$stmt = $pdo->prepare("
    SELECT m.*, d.name as director_name 
    FROM movies m 
    JOIN directors d ON m.director_id = d.id 
    WHERE m.category_id = (SELECT id FROM categories WHERE name = ?)
    ORDER BY m.title ASC
");
$stmt->execute([$category_name]);
$movies = $stmt->fetchAll();
?>

<h2 class="mb-4">Catégorie : <?= htmlspecialchars($category_name) ?></h2>

<?php if (empty($movies)): ?>
    <div class="alert alert-info">
        Aucun film disponible dans cette catégorie pour le moment.
    </div>
<?php else: ?>
    <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
        <?php foreach ($movies as $movie): ?>
            <div class="col">
                <div class="card h-100 shadow-sm">
                    <img src="<?= htmlspecialchars($movie['image']) ?>" 
                         class="card-img-top" 
                         alt="<?= htmlspecialchars($movie['title']) ?>"
                         style="height: 280px; object-fit: cover;">
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                        <p class="card-text text-muted"><?= htmlspecialchars($movie['director_name']) ?></p>
                        <p class="card-text"><strong><?= number_format($movie['price'], 2) ?> €</strong></p>
                        
                        <div class="mt-auto">
                            <a href="movie.php?id=<?= $movie['id'] ?>" 
                               class="btn btn-primary btn-sm w-100 mb-2">Voir détails</a>
                            <a href="add_to_cart.php?id=<?= $movie['id'] ?>" 
                               class="btn btn-success btn-sm w-100">Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<div class="mt-4">
    <a href="index.php" class="btn btn-secondary">← Retour à l'accueil</a>
</div>

<?php require_once 'includes/footer.php'; ?>