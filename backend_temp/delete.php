<?php
session_start();
if (($_SESSION['roles']) !== "ROLE_ADMIN") {
    
    header("Location: /../404"); 
    exit(); 
}

require __DIR__ . '/./../pdo.php';
$id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
//$id = htmlspecialchars($_GET['id']);
$stmt = $pdo->prepare("DELETE FROM offre WHERE id = :id");
$stmt->execute([':id' => $id]);

header("Location: http://www.jo2024.ndev2023.fr/backend/Admin.php");
exit();





?>
