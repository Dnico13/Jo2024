<?php
session_start();
if (($_SESSION['roles']) !== "ROLE_ADMIN") {
    
    header("Location: /../404"); 
    exit(); 
}




require __DIR__ . '/./../pdo.php';
require_once  'partials/head.php';

$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
$stmt = $pdo->prepare("SELECT * FROM offre WHERE id = :id");
$stmt->execute([':id' => $id]);
$offre = $stmt->fetch();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $stmt = $pdo->prepare("UPDATE offre SET nom=:nom, description1=:description1, description2=:description2, description3=:description3, prix=:prix, ventes=:ventes WHERE id=:id");
    
    $stmt->execute([
        ':nom' => htmlspecialchars($_POST['nom']),
        ':description1' => htmlspecialchars($_POST['description1']),
        ':description2' => htmlspecialchars($_POST['description2']),
        ':description3' => htmlspecialchars($_POST['description3']),
        ':prix' => filter_var($_POST['prix'], FILTER_VALIDATE_FLOAT),
        ':ventes' => filter_var($_POST['ventes'], FILTER_VALIDATE_INT),
        ':id' => $id,
    ]);

    header("Location: http://www.jo2024.ndev2023.fr/backend/Admin.php");
    exit();


}
?>
<form method="post"  class="table table-striped table-hover">
    <input  type="text" name="nom" value="<?= htmlspecialchars($offre['nom']) ?>" required>
    <input  type="text" name="description1" value="<?= htmlspecialchars($offre['description1']) ?>">
    <input  type="text" name="description2" value="<?= htmlspecialchars($offre['description2']) ?>">
    <input  type="text" name="description3" value="<?= htmlspecialchars($offre['description3']) ?>">
    <input  type="number" name="prix" value="<?= htmlspecialchars($offre['prix']) ?>" required>
    <input  type="number" name="ventes" value="<?= htmlspecialchars($offre['ventes']) ?>">
    <button type="submit">Modifier</button>
</form>

<?php
require_once  'partials/footer.php';    
?>
