<?php
session_start();
if (($_SESSION['roles']) !== "ROLE_ADMIN") {
    
    header("Location: /../404"); 
    exit(); 
}
require __DIR__ . '/./../pdo.php';
require_once  'partials/head.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("INSERT INTO offre (nom, description1, description2, description3, prix, ventes) VALUES (:nom, :description1, :description2, :description3, :prix, :ventes)");
    
    $stmt->execute([
        ':nom' => htmlspecialchars($_POST['nom']),
        ':description1' => htmlspecialchars($_POST['description1']),
        ':description2' => htmlspecialchars($_POST['description2']),
        ':description3' => htmlspecialchars($_POST['description3']),
        ':prix' => filter_var($_POST['prix'], FILTER_VALIDATE_FLOAT),
        ':ventes' => filter_var($_POST['ventes'], FILTER_VALIDATE_INT),
    ]);

   header("Location: http://www.jo2024.ndev2023.fr/backend/Admin.php");
    exit();

}
?>
<form method="post" class="table table-striped table-hover">
    <input type="text" name="nom" placeholder="Nom" required>
    <input type="text" name="description1" placeholder="Description 1">
    <input type="text" name="description2" placeholder="Description 2">
    <input type="text" name="description3" placeholder="Description 3">
    <input type="number" name="prix" placeholder="Prix" required>
    <input type="number" name="ventes" placeholder="Ventes">
    <button type="submit" class="btn btn-secondary">Ajouter</button>
</form>

<?php
require_once  'partials/footer.php';
?>