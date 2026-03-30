<?php
require_once 'includes/header.php';

$search = isset($_GET['q']) ? trim($_GET['q']) : '';
$results = [];

if (!empty($search)) {
    $stmt = $pdo->prepare("
        SELECT m.*, d.name as director_name, c.name as category_name 
        FROM movies m 
        JOIN directors d ON m.director_id = d.id 
        JOIN categories c ON m.category_id = c.id 
        WHERE m.title LIKE ? 
           OR d.name LIKE ? 
        ORDER BY m.title ASC
    ");
    $like = "%$search%";
    $stmt->execute([$like, $like]);
    $results = $stmt->fetchAll();
}
?>

<h2 class="mb-4">Recherche de films</h2>

<div class="row justify-content-center mb-5">
    <div class="col-md-8">
        <form method="GET" class="input-group">
            <input type="text" 
                   name="q" 
                   class="form-control form-control-lg" 
                   placeholder="Titre du film ou nom du réalisateur..." 
                   value="<?= htmlspecialchars($search) ?>" 
                   required>
            <button type="submit" class="btn btn-primary btn-lg">
                <i class="fas fa-search"></i> Rechercher
            </button>
        </form>
    </div>
</div>

<?php if (!empty($search)): ?>
    <?php if (empty($results)): ?>
        <div class="alert alert-info text-center">
            Aucun résultat trouvé pour "<strong><?= htmlspecialchars($search) ?></strong>".
        </div>
    <?php else: ?>
        <p class="mb-4"><strong><?= count($results) ?> résultat(s) trouvé(s)</strong></p>
        
        <div class="row row-cols-1 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($results as $movie): ?>
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
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>