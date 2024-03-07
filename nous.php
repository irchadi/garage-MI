<?php
// Inclure le fichier de connexion
require_once('connectdb.php');

// Récupérer les noms des services depuis la base de données
$requete = $bdd->query("SELECT nom FROM services");
$services = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage V. Parrot</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets\style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contact-form input').on('input', function() {
                var isValid = true;
                $('#contact-form input').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        return false; // Sortir de la boucle si un champ est vide
                    }
                });
                if (isValid) {
                    $('#submit-btn').prop('disabled', false);
                } else {
                    $('#submit-btn').prop('disabled', true);
                }
            });
        });
    </script>
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
                            <?php foreach ($services as $service): ?>
                            <a class="dropdown-item" href="#"><?php echo htmlspecialchars($service['nom']); ?></a>
                            <?php endforeach; ?>
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
                        <a class="nav-link" href="nous.php">Qui sommes-nous ?</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="moncompteemploye.php">Employé</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="moncompte.php">Administrateur</a>
                    </li>
                </ul>
            </div>
        </nav>
</header>

<main>
    <section class="intro">
        <div class="container">
            <h2>À propos du Garage V. Parrot</h2>
            <p>
                Fondé en 2021 à Toulouse par <strong>Vincent Parrot</strong>, un expert en réparation automobile avec plus de 15 ans d'expérience, le <strong>Garage V. Parrot</strong> est devenu un lieu de confiance pour tous les besoins automobiles. Nous offrons une large gamme de services, incluant la réparation de carrosserie, la mécanique générale, et l'entretien régulier pour garantir la performance et la sécurité de votre véhicule. Nous proposons également une sélection de véhicules d'occasion de qualité pour répondre à tous les besoins et budgets.
            </p>
            <p>
                Chez Garage V. Parrot, nous traitons chaque voiture avec le plus grand soin, assurant un service de qualité et personnalisé à chaque client. Reconnaissant l'importance d'une présence en ligne dans le monde moderne, nous avons créé ce site web pour vous permettre de découvrir facilement nos services et de prendre contact avec nous.
            </p>
        </div>
    </section>
</main>
<footer class="bg-light p-3 fixed-bottom">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>