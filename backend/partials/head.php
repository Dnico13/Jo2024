<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
     <meta name="robots" content="noindex">
    <title>Admin Dashboard</title>
    <!-- Lien Bootstrap CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

    <!-- HEADER -->
    <header class="bg-dark text-white p-3 d-flex justify-content-between align-items-center">
        <h1 class="h4 mb-0">JO-2024</h1>
        <nav>
            <ul class="nav">
                <!--<li class="nav-item"><a class="nav-link text-white" href="#">Tableau de bord</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Utilisateurs</a></li>
                <li class="nav-item"><a class="nav-link text-white" href="#">Paramètres</a></li>-->
                <li class="nav-item"><a class="nav-link text-danger fw-bold" href="./logOut.php">Déconnexion</a></li>
            </ul>
        </nav>
    </header>

    <!-- Contenu principal -->
    <main class="container mt-4">
        <p class="text-center display-6">Bienvenue dans votre interface d'administration.</p>
        <!-- Integration de la liste des Formules -->
        <div class="d-flex justify-content-between align-items-center mb-3">
