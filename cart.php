<?php
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    $_SESSION['error'] = "Vous devez être connecté pour accéder au panier.";
    header("Location: pages/login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$stmt = $pdo->prepare("
    SELECT m.*, c.id as cart_id 
    FROM cart c 
    JOIN movies m ON c.movie_id = m.id 
    WHERE c.user_id = ? 
    ORDER BY c.added_at DESC
");
$stmt->execute([$user_id]);
$cart_items = $stmt->fetchAll();

$total = 0;
foreach ($cart_items as $item) {
    $total += $item['price'];
}
?>

<h1 class="mb-4">Votre Panier</h1>

<?php if (empty($cart_items)): ?>
    <div class="alert alert-info">
        Votre panier est vide. 
        <a href="index.php" class="alert-link">Découvrir des films</a>
    </div>
<?php else: ?>
    <div class="row">
        <div class="col-md-8">
            <table class="table table-bordered">
                <thead class="table-dark">
                    <tr>
                        <th>Film</th>
                        <th>Prix</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cart_items as $item): ?>
                        <tr>
                            <td>
                                <strong><?= htmlspecialchars($item['title']) ?></strong><br>
                                <small class="text-muted"><?= htmlspecialchars($item['director_name'] ?? '') ?></small>
                            </td>
                            <td><?= number_format($item['price'], 2) ?> €</td>
                            <td>
                                <a href="remove_from_cart.php?cart_id=<?= $item['cart_id'] ?>" 
                                   class="btn btn-danger btn-sm"
                                   onclick="return confirm('Supprimer ce film du panier ?')">
                                    Supprimer
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h5>Total : <strong><?= number_format($total, 2) ?> €</strong></h5>
                    <a href="purchase.php" class="btn btn-success btn-lg w-100 mt-3">
                        Procéder à l'achat
                    </a>
                    <a href="index.php" class="btn btn-secondary w-100 mt-2">
                        Continuer mes achats
                    </a>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php require_once 'includes/footer.php'; ?>    