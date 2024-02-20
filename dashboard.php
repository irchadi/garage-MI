<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion s'il n'est pas connecté
    header('Location: login.php');
    exit();
}

// Le nom de l'utilisateur est stocké dans la session, récupérons-le
$nom_utilisateur = $_SESSION['user_nom'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - <?= $nom_utilisateur ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <a class="navbar-brand" href="index.php">GVP</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Nos services
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="#">Réparation</a>
                            <a class="dropdown-item" href="#">Contrôle technique</a>
                            <a class="dropdown-item" href="#">Entretien</a>
                        </div>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Vehicules en vente
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="vehicules.php">Vehicules d'occasion</a>
                        </div>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="">Mon compte</a>
                    </li>
                </ul>
            </div>
        </nav>
    <main class="container">
        <h1>Bienvenue dans votre tableau de bord, <?= $nom_utilisateur ?> !</h1>
        <!-- Contenu de votre tableau de bord -->
    </main>

    <footer class="bg-light p-3 fixed">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
