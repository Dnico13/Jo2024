<?php
require __DIR__ . '/../../pdo.php';



// Récupération des données POST
$id_user = isset($_POST["id"]) ? intval($_POST["id"]) : null;
$panier_raw = isset($_POST["panier"]) ? $_POST["panier"] : null;




// Décodage du JSON
$panier = json_decode($panier_raw, true);



// Vérification des données entrantes
if (!$id_user || $panier_raw === null || $panier === null || !is_array($panier)) {
    http_response_code(400); // Bad Request
    echo json_encode([
        "status" => "error",
        "message" => "Données de commande invalides. Veuillez vérifier l'ID utilisateur et le format du panier.",
        // Ceci est important pour le débogage côté JS :
        "debug_info" => [
            "id_user" => $id_user,
            "panier_raw" => $panier_raw,
            "panier_decoded" => $panier,
            "json_error_code" => json_last_error(),
            "json_error_message" => json_last_error_msg()
        ]
    ]);
    exit;
}


$pdo->beginTransaction();

try {
    // Récupérer la clé utilisateur
    $stmtUser = $pdo->prepare("SELECT clef1 FROM user WHERE id = ?");
    $stmtUser->execute([$id_user]);
    $cle_user = $stmtUser->fetchColumn();

    if (!$cle_user) {
        throw new Exception("Utilisateur non trouvé ou clé non disponible.");
    }

     // Initialisation de $final_clefs avant la boucle
    $final_clefs = [];

    // Traitement du panier et insertion des commandes
    foreach ($panier as $produit) { 
        if (!isset($produit["id"], $produit["quantite"])) {
            throw new Exception("Structure du produit invalide : 'id' ou 'quantite' manquant.");
        }

        $id_offre = intval($produit["id"]);
        $quantite = intval($produit["quantite"]);

        $cle_aleatoire = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $cle_finale = $cle_user . $cle_aleatoire;
        // inutile a virer de la table finalement
        $qrcode = "https://example.com/qrcode?id=" . $cle_finale;

        $stmtCommande = $pdo->prepare("INSERT INTO commande (id_user, id_offre, cle_aleatoire, cle_finale, qrcode, date) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmtCommande->execute([$id_user, $id_offre, $cle_aleatoire, $cle_finale, $qrcode]);

        $stmtUpdateVentes = $pdo->prepare("UPDATE offre SET ventes = ventes + ? WHERE id = ?");
        $stmtUpdateVentes->execute([$quantite, $id_offre]);

         
        $final_clefs[] = $cle_finale;


    }

    $pdo->commit();
    http_response_code(200); 
    echo json_encode(["status" => "success", "message" => "Commande enregistrée avec succès !","cle_finale" => $final_clefs]);

} catch (Exception $e) {
    $pdo->rollBack();
    http_response_code(500); // Internal Server Error
    echo json_encode([
        "status" => "error",
        "message" => "Une erreur est survenue lors de l'enregistrement de la commande.",
        
    ]);
}



exit; 
?>