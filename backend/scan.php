<?php
// scan.php

// Connexion à la base de données
require_once __DIR__ . '/../pdo.php'; // Ajustez le chemin si nécessaire



$cle_finale = htmlspecialchars($_GET['cle_finale'] ?? '') ?: null; // Gére l'absence de la clé et la sécurise

$scan_result = [
    'status' => 'error',
    'message' => 'Billet invalide ou non trouvé.'
];

if ($cle_finale) {
    try {
        // Extraire la clef utilisateur (les 6 premiers chiffres)
        $cle_user = substr($cle_finale, 0, 6);

        // Requête SQL pour joindre les tables 'commande' et 'user'
        // Je récupère les infos de la commande et de l'utilisateur associé
        $sql = "SELECT
                    c.id AS commande_id,
                    c.date AS date_achat,
                    u.nom AS nom_acheteur,
                    u.prenom AS prenom_acheteur
                FROM
                    commande c
                JOIN
                    user u ON c.id_user = u.id
                WHERE
                    c.cle_finale = :cle_finale
                    AND u.clef1 = :cle_user"; 

        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            ':cle_finale' => $cle_finale,
            ':cle_user' => $cle_user // Utilise la clef utilisateur extraite
        ]);

        $billet_info = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($billet_info) {
            $scan_result = [
                'status' => 'success',
                'message' => 'Billet valide !',
                'details' => [
                    'nom_acheteur' => $billet_info['nom_acheteur'],
                    'prenom_acheteur' => $billet_info['prenom_acheteur'],
                    'date_achat' => date('d/m/Y H:i', strtotime($billet_info['date_achat'])) // Formater la date
                ]
            ];
        } else {
            $scan_result['message'] = 'Billet non trouvé ou clé utilisateur invalide.';
        }

    } catch (PDOException $e) {
        $scan_result['message'] = 'Erreur de base de données : ' . $e->getMessage();
        
        error_log("Erreur PDO dans scan.php: " . $e->getMessage());
    } catch (Exception $e) {
        $scan_result['message'] = 'Erreur interne : ' . $e->getMessage();
        error_log("Erreur générale dans scan.php: " . $e->getMessage());
    }
} else {
    $scan_result['message'] = 'Clé finale manquante dans l\'URL.';
}

//   layout HTML avec les résultats
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vérification de Billet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            max-width: 600px;
            margin: 50px auto;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            color: white;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            padding: 15px;
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-body h3 {
            color: #343a40;
            margin-bottom: 20px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
        }
    </style>
</head>
<body class="d-flex align-items-center justify-content-center min-vh-100">
    <div class="card text-center">
        <div class="card-header">
            Système de Vérification de Billet
        </div>
        <div class="card-body p-4">
            <?php if ($scan_result['status'] === 'success'): ?>
                <div class="alert alert-success" role="alert">
                    <h3><?= $scan_result['message'] ?></h3>
                    <p class="lead"><strong>Nom :</strong> <?= $scan_result['details']['nom_acheteur'] ?></p>
                    <p class="lead"><strong>Prénom :</strong> <?= $scan_result['details']['prenom_acheteur'] ?></p>
                    <p class="lead"><strong>Date d'achat :</strong> <?= $scan_result['details']['date_achat'] ?></p>
                </div>
            <?php else: ?>
                <div class="alert alert-danger" role="alert">
                    <h3><?= $scan_result['message'] ?></h3>
                    <p>Veuillez scanner un billet valide ou vérifier la clé.</p>
                </div>
            <?php endif; ?>
            <a href="/" class="btn btn-primary mt-3">Retour à l'accueil</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>