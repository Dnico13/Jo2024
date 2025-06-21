<?php

require __DIR__ . '/../../pdo.php'; 



header("Content-Type: application/json");
header("Access-Control-Allow-Origin: *");


try {
    // Préparer la requête sécurisée
    $stmt = $pdo->query("SELECT id, nom, description1, description2, description3, prix FROM offre");
    $offres = $stmt->fetchAll(PDO::FETCH_ASSOC);
    



    // Vérifier si des offres existent
    if ($offres) {
        echo json_encode($offres);
    } else {
        echo json_encode(["error" => "Aucune offre disponible"]);
    }

} catch (PDOException $e) {
    error_log("Erreur SQL : " . $e->getMessage());
    echo json_encode(["error" => "Erreur serveur"]);
}
?>
