<?php
require_once '../includes/header.php';

$errors = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email    = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (empty($email) || empty($password)) {
        $errors = "Email et mot de passe sont requis.";
    } else {
        $stmt = $pdo->prepare("SELECT id, username, password FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            header("Location: ../index.php");
            exit;
        } else {
            $errors = "Email ou mot de passe incorrect.";
        }
    }
}
?>

<h2 class="mb-4 text-center">Connexion</h2>

<?php if ($errors): ?>
    <div class="alert alert-danger"><?= htmlspecialchars($errors) ?></div>
<?php endif; ?>

<div class="row justify-content-center">
    <div class="col-md-5">
        <form method="POST" class="card p-4 shadow">
            <div class="mb-3">
                <label class="form-label">Email</label>
                <input type="email" name="email" class="form-control" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Mot de passe</label>
                <input type="password" name="password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary w-100">Se connecter</button>
        </form>
        
        <div class="text-center mt-3">
            Pas encore de compte ? <a href="register.php">S'inscrire</a>
        </div>
    </div>
</div>

<?php require_once '../includes/footer.php'; ?>