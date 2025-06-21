<?php

require __DIR__ . '/../../pdo.php'; 
header("Content-Type: application/json"); 


/*try {
    

    // Vérifier si des données sont envoyées
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"] ?? "";
        $password = $_POST["password"] ?? "";

        // Vérification dans la base de données
        $stmt = $pdo->prepare("SELECT id, password FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user["password"])) {
            echo json_encode(["success" => true, "message" => "Connexion réussie"]);
        } else {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
        }
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Erreur serveur"]);
}
?>
*/
try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = trim($_POST["email"] ?? "");
        $password = trim($_POST["password"] ?? "");

        if (empty($email) || empty($password)) {
            echo json_encode(["success" => false, "message" => "Email et mot de passe requis"]);
            exit;
        }

        // Vérification dans la base de données
        $stmt = $pdo->prepare("SELECT id, password, roles FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
            exit;
        }

        if (password_verify($password, $user["password"])) {
            // **Génération du code de vérification**
            $verificationCode = random_int(100000, 999999);
            $expiryTime = time() + 300; 

            // **Stockage du code en base**
            $stmt = $pdo->prepare("UPDATE user SET verif_code = ?, expir_code = ? WHERE email = ?");
            if (!$stmt->execute([$verificationCode, $expiryTime, $email])) {
                echo json_encode(["success" => false, "message" => "Erreur de mise à jour"]);
                exit;
            }

            // **Envoi du code par email**
            $to = $email;
            $subject = "Votre code de vérification pour le site www.jo2024.ndev2023.fr";
            $message = "Votre code est : " . $verificationCode;
            $headers = "From: no-reply@JO2024.com";

            if (mail($to, $subject, $message, $headers)) {
                error_log("✅ Email envoyé à " . $email);
                echo json_encode(["success" => true, "message" => "Code envoyé", "id"=> $user["id"],"roles"=> $user["roles"]]);
            } else {
                error_log("❌ Échec d'envoi d'email à " . $email);
                echo json_encode(["success" => false, "message" => "Problème d'envoi d'email"]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect"]);
        }
    }
} catch (Exception $e) {
    error_log("❌ Erreur serveur : " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "Erreur serveur"]);
}
?>


