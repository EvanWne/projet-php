<?php
require_once '../includes/header.php';

$errors = [];
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['confirm_password'] ?? '';

    if (empty($username)) $errors[] = "Le nom d'utilisateur est requis.";
    if (empty($email)) $errors[] = "L'email est requis.";
    if (empty($password)) $errors[] = "Le mot de passe est requis.";
    if ($password !== $confirm) $errors[] = "Les mots de passe ne correspondent pas.";
    if (strlen($password) < 6) $errors[] = "Le mot de passe doit faire au moins 6 caractères.";

    if (empty($errors)) {
        try {
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $errors[] = "Ce nom d'utilisateur ou cet email existe déjà.";
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
                $stmt->execute([$username, $email, $hashed_password]);

                $success = "Inscription réussie ! Vous pouvez maintenant vous connecter.";
            }
        } catch (PDOException $e) {
            $errors[] = "Erreur lors de l'inscription.";
        }
    }
}
?>

<h2 class="mb-4">Créer un compte</h2>

<?php if (!empty($errors)): ?>
    <div class="alert alert-danger">
        <ul class="mb-0">
            <?php foreach ($errors as $error): ?>
                <li><?= htmlspecialchars($error) ?></li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<?php if ($success): ?>
    <div class="alert alert-success"><?= $success ?></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Nom d'utilisateur</label>
                <input type="text" name="username" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Confirmer le mot de passe</label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
        </form>
        
        <div class="text-center mt-3">
            Déjà un compte ? <a href="login.php">Se connecter</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>