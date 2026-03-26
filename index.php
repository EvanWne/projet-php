<?php
require_once 'includes/header.php';

$stmt = $pdo->query("SELECT m.*, d.name as director_name, c.name as category_name 
                     FROM movies m 
                     JOIN directors d ON m.director_id = d.id 
                     JOIN categories c ON m.category_id = c.id 
                     ORDER BY m.created_at DESC LIMIT 12");

$movies = $stmt->fetchAll();
?>

<div class="row">
    <div class="col-12">
        <h1 class="mb-4 text-center">Bienvenue sur MovieStore</h1>
        <p class="lead text-center mb-5">Découvrez et achetez vos films préférés en ligne</p>
    </div>
</div>

<!-- Section Films en vedette -->
<h2 class="mb-4">Films récemment ajoutés</h2>

<div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
    <?php foreach ($movies as $movie): ?>
        <div class="col">
            <div class="card h-100 shadow-sm">
                <img src="<?= htmlspecialchars($movie['image']) ?>" 
                     class="card-img-top" 
                     alt="<?= htmlspecialchars($movie['title']) ?>"
                     style="height: 300px; object-fit: cover;">
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title"><?= htmlspecialchars($movie['title']) ?></h5>
                    <p class="card-text text-muted">
                        <?= htmlspecialchars($movie['director_name']) ?>
                    </p>
                    <p class="card-text">
                        <strong><?= number_format($movie['price'], 2) ?> €</strong>
                    </p>
                    
                    <div class="mt-auto">
                        <a href="movie.php?id=<?= $movie['id'] ?>" 
                           class="btn btn-primary btn-sm w-100 mb-2">
                            Voir les détails
                        </a>
                        <a href="add_to_cart.php?id=<?= $movie['id'] ?>" 
                           class="btn btn-success btn-sm w-100">
                            <i class="fas fa-cart-plus"></i> Ajouter au panier
                        </a>
                    </div>
                </div>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<?php if (empty($movies)): ?>
    <div class="alert alert-warning text-center">
        Aucun film disponible pour le moment.
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>