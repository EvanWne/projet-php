<?php
require_once '../includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $current_password = $_POST['current_password'] ?? '';
    $new_password = $_POST['new_password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($current_password) || empty($new_password) || empty($confirm_password)) {
        $error = "Tous les champs sont obligatoires.";
    } elseif ($new_password !== $confirm_password) {
        $error = "Les nouveaux mots de passe ne correspondent pas.";
    } elseif (strlen($new_password) < 6) {
        $error = "Le nouveau mot de passe doit contenir au moins 6 caractères.";
    } else {
        $stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch();

        if ($user && password_verify($current_password, $user['password'])) {
            $hashed = password_hash($new_password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
            $stmt->execute([$hashed, $_SESSION['user_id']]);

            $message = "Mot de passe changé avec succès !";
        } else {
            $error = "Mot de passe actuel incorrect.";
        }
    }
}
?>

<h2 class="mb-4">Changer mon mot de passe</h2>

<?php if ($message): ?>
    <div class="alert alert-success"><?= $message ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-danger"><?= $error ?></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Mot de passe actuel</label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Nouveau mot de passe</label>
                <input type="password" name="new_password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Confirmer le nouveau mot de passe</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-warning w-100">Changer le mot de passe</button>
        </form>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>