<?php
session_start();


if (($_SESSION['roles']) !== "ROLE_ADMIN") {
    
    header("Location: /../404"); 
    exit(); 
}



require __DIR__ . '/./../pdo.php';

// Récupérer les offres
$query = $pdo->query("SELECT id, nom, description1, description2, description3, prix, ventes FROM offre");
$offres = $query->fetchAll(PDO::FETCH_ASSOC);
?>

<?php
require_once  'partials/head.php';
?>






<h2>Liste des Offres</h2>
<a href="create.php" class="btn btn-success mb-3">
    Ajouter une Offre

</a>
</div>

<table  class="table table-striped table-hover">
    <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Description 1</th>
        <th>Description 2</th>
        <th>Description 3</th>
        <th>Prix (€)</th>
        <th>Ventes</th>
        <th>Actions</th>
    </tr>
    <?php foreach ($offres as $offre): ?>
        <tr>
            <td><?= htmlspecialchars($offre['id']) ?></td>
            <td><?= htmlspecialchars($offre['nom']) ?></td>
            <td><?= htmlspecialchars($offre['description1']) ?></td>
            <td><?= htmlspecialchars($offre['description2']) ?></td>
            <td><?= htmlspecialchars($offre['description3']) ?></td>
            <td><?= htmlspecialchars($offre['prix']) ?></td>
            <td><?= htmlspecialchars($offre['ventes']) ?></td>
            <td>
                <a href="update.php?id=<?= htmlspecialchars($offre['id']) ?>">Modifier</a> |
                <a href="delete.php?id=<?= htmlspecialchars($offre['id']) ?>" onclick="return confirm('Supprimer cette offre ?');">Supprimer</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>



<?php
require_once  'partials/footer.php';
?>

</html>