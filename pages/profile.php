<?php
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT m.*, p.purchased_at 
    FROM purchases p 
    JOIN movies m ON p.movie_id = m.id 
    WHERE p.user_id = ? 
    ORDER BY p.purchased_at DESC
");
$stmt->execute([$user_id]);
$purchased_movies = $stmt->fetchAll();
?>

<h2 class="mb-4">Mon Profil - <?= htmlspecialchars($_SESSION['username']) ?></h2>

<div class="row">
    <div class="col-md-8">
        <h4>Mes films achetés</h4>
        
        <?php if (empty($purchased_movies)): ?>
            <div class="alert alert-info">
                Vous n'avez encore acheté aucun film.
            </div>
        <?php else: ?>
            <div class="list-group">
                <?php foreach ($purchased_movies as $movie): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <strong><?= htmlspecialchars($movie['title']) ?></strong><br>
                                <small class="text-muted">Acheté le <?= date('d/m/Y', strtotime($movie['purchased_at'])) ?></small>
                            </div>
                            <a href="../movie.php?id=<?= $movie['id'] ?>" class="btn btn-sm btn-primary">
                                Voir le film
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Formulaire changement de mot de passe -->
    <div class="col-md-4">
        <h4>Changer mon mot de passe</h4>
        <form method="POST" action="change_password.php" class="card p-3">
            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-warning">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>