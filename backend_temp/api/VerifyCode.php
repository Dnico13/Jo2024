<?php
session_start(); // Démarre la session PHP

require __DIR__ . '/../../pdo.php';

// Toujours définir le Content-Type à application/json car nous renvoyons désormais du JSON en permanence.
// C'est important pour que le frontend sache comment interpréter la réponse.
header("Content-Type: application/json");

try {
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        $email = $_POST["email"] ?? "";
        $verificationCode = $_POST["verif_code"] ?? "";

        // Récupération du code, de son expiration, des rôles ET DE L'ID de l'utilisateur
        $stmt = $pdo->prepare("SELECT id, verif_code, expir_code, roles FROM user WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "Aucun utilisateur trouvé."]);
            exit;
        }

        // Vérification du code et de son expiration
        if ($verificationCode == $user["verif_code"] && time() < $user["expir_code"]) {
            // Code valide et non expiré
            // Suppression du code après validation pour qu'il ne puisse plus être réutilisé
            $stmt = $pdo->prepare("UPDATE user SET verif_code = NULL, expir_code = NULL WHERE email = ?");
            $stmt->execute([$email]);

            // **MODIFICATION MAJEURE ICI : GESTION DE LA SESSION PHP ET PRÉPARATION DE LA RÉPONSE JSON**
            // Authentification réussie : Stocke les informations de session PHP sur le serveur.
            // Ces variables seront disponibles sur les pages PHP après la redirection.
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['roles'] = $user['roles']; // Stocke le rôle dans la session PHP aussi

            // Détermine l'URL de redirection à envoyer au JavaScript
            $redirectPage = ($user['roles'] === "ROLE_ADMIN") ? "/backend/Admin.php" : "/paiement";
            
            // Renvoie une réponse JSON indiquant le succès, un message, et l'URL de redirection.
            // Le frontend utilisera cette URL pour rediriger.
            echo json_encode([
                "success" => true,
                "message" => "Code valide, connexion réussie.",
                "redirect" => $redirectPage, // Clé ajoutée pour l'URL de redirection
                "roles" => $user['roles'] // Inclure le rôle si le frontend en a besoin pour d'autres logiques
            ]);
            exit;

        } else {
            // Code invalide ou expiré
            echo json_encode(["success" => false, "message" => "Code invalide ou expiré."]);
            exit; // Arrête l'exécution après l'envoi de la réponse JSON
        }
    } else {
        // Méthode de requête non autorisée (ex: GET au lieu de POST)
        echo json_encode(["success" => false, "message" => "Méthode de requête non autorisée."]);
        exit;
    }
} catch (Exception $e) {
    // En cas d'erreur serveur inattendue (ex: problème de base de données)
    error_log("Erreur dans VerifyCode.php: " . $e->getMessage()); // Log l'erreur pour le débogage sur le serveur
    echo json_encode(["success" => false, "message" => "Erreur serveur. Veuillez réessayer."]);
    exit;
}
?>