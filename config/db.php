<?php
require_once 'includes/header.php';

echo "<h3>Debug bydirector.php</h3>";

if (!isset($_GET['director_id']) || !is_numeric($_GET['director_id'])) {
    die("<div class='alert alert-danger'>ID du réalisateur manquant ou invalide.</div>");
}

$director_id = (int)$_GET['director_id'];

echo "Director ID reçu : " . $director_id . "<br>";

$stmt = $pdo->prepare("SELECT name FROM directors WHERE id = ?");
$stmt->execute([$director_id]);
$director = $stmt->fetch();

if (!$director) {
    die("<div class='alert alert-danger'>Réalisateur ID " . $director_id . " non trouvé.</div>");
}

echo "Réalisateur trouvé : " . htmlspecialchars($director['name']) . "<br>";

$stmt = $pdo->prepare("
    SELECT m.*, c.name as category_name 
    FROM movies m 
    JOIN categories c ON m.category_id = c.id 
    WHERE m.director_id = ? 
    ORDER BY m.title
");
$stmt->execute([$director_id]);
$movies = $stmt->fetchAll();

echo "Nombre de films trouvés : " . count($movies) . "<br><hr>";

?>

<h2 class="mb-4">Films de <?= htmlspecialchars($director['name']) ?></h2>

<?php if (empty($movies)): ?>
    <div class="alert alert-info">Aucun film trouvé pour ce réalisateur.</div>
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
                        <p class="card-text text-muted"><?= htmlspecialchars($movie['category_name']) ?></p>
                        <p class="card-text"><strong><?= number_format($movie['price'], 2) ?> €</strong></p>
                        
                        <div class="mt-auto">
                            <a href="movie.php?id=<?= $movie['id'] ?>" class="btn btn-primary btn-sm w-100 mb-2">Voir détails</a>
                            <a href="add_to_cart.php?id=<?= $movie['id'] ?>" class="btn btn-success btn-sm w-100">Ajouter au panier</a>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>

<p class="mt-4">
    <a href="index.php" class="btn btn-secondary">← Retour à l'accueil</a>
</p>

<?php require_once 'includes/footer.php'; ?>