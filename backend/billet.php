
<?php
// Connexion à la base de données
require_once '../pdo.php';
 
// recuperation de la clef finale  

$cle_finale = htmlspecialchars($_GET['cle_finale']) ?? null;


/* en local  */
//$cle_finale = 290937593165;

/* fin variable local tests */


// Requête pour récupérer l id de la  commande
$sql = "SELECT * FROM commande WHERE cle_finale = :cle_finale";
$stmt = $pdo->prepare($sql);
$stmt->execute(['cle_finale' => $cle_finale]);

$id = $stmt->fetchColumn();

// Récupération de la clé du billet depuis la table "commande"
$query = $pdo->prepare("SELECT cle_finale FROM commande WHERE id = :id");
$query->execute(['id' => $id]); 
$billet = $query->fetch();
$qr_data = "https://" . $_SERVER["HTTP_HOST"] . "/backend/scan.php?cle_finale=" . $billet['cle_finale'];

$qr_url = "https://quickchart.io/qr?text=" . urlencode($qr_data) . "&size=300";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>JO2024: Retrouvez vos billets</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex justify-content-center align-items-center vh-100">

<div class="card text-center shadow-sm p-3" id="ticket">
    <div class="card-body">
        <h2 class="card-title">Mon Billet</h2>
        <div class="d-flex justify-content-center">
            <img src="<?php echo $qr_url; ?>" alt="QR Code" class="img-fluid" style="max-width: 300px;">
        </div>
        <p class="card-text mt-3">Présentez ce billet lors de votre arrivée.</p>
        <button class="btn btn-primary mt-3" onclick="printTicket()">Imprimer</button>
    </div>
</div>

<script>
    function printTicket() {
        var ticketContent = document.getElementById("ticket").innerHTML;
        var originalContent = document.body.innerHTML;
        document.body.innerHTML = ticketContent;
        window.print();
        document.body.innerHTML = originalContent;
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
