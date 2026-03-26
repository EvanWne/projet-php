<?php
require_once 'config/db.php';

echo "✅ Connexion à la base de données réussie !<br>";
echo "Base utilisée : " . $dbname . "<br>";

$stmt = $pdo->query("SELECT COUNT(*) as total FROM movies");
$row = $stmt->fetch();
echo "Nombre de films dans la table : " . $row['total'];
?>