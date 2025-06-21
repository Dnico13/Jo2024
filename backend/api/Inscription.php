<?php
require __DIR__ . '/../../pdo.php'; 
header("Content-Type: application/json");


// Récupération des données envoyées en JSON
$data = json_decode(file_get_contents("php://input"), true);




 
if (!$data || empty($data["email2"]) || empty($data["password2"]) || empty($data["nom2"]) || empty($data["prenom2"]) || empty($data["clef1"]) || empty($data["roles"])) {
    echo json_encode(["success" => false, "message" => "Données manquantes"]);
    exit;
}

// Hash du mot de passe pour plus de sécurité
$hashedPassword = password_hash($data["password2"], PASSWORD_BCRYPT);



// Insertion dans la base de données
try {
    $stmt = $pdo->prepare("INSERT INTO user (email, password, nom, prenom, clef1, roles) VALUES (:email, :password, :nom, :prenom, :clef1, :roles)");
    $stmt->execute([
        ":email" => $data["email2"],
        ":password" => $hashedPassword,
        ":nom" => $data["nom2"],
        ":prenom" => $data["prenom2"],
        ":clef1" => $data["clef1"],
        ":roles" => $data["roles"]
    ]);

    echo json_encode(["success" => true, "message" => "Inscription réussie !"]);

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Erreur SQL : " . $e->getMessage()]);
}
?>
