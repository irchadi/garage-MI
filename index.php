<?php
session_start(); // Démarre la session
// Inclure le fichier de connexion
require_once('connectdb.php');

$horaires = json_decode(file_get_contents('horaires.json'), true);
$services = json_decode(file_get_contents('services.json'), true);


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
                        <a class="nav-link" href="nous.html">Qui sommes-nous ?</a>
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
        <div>
            <img src="assets\Garage V Parrot -logos\Garage V Parrot -logos_black.png" alt="Logo garage V. Parrot" style="width: 50%;">
        </div>
        <div class="container">
            <div class="coordonnees">
                <h2>Garage V. Parrot</h2>
                <p>123 Rue de la République, Toulouse</p>
                <p>+123 456 789</p>
            </div>
            <div class="horaires">
            <h2>Heures d'ouverture</h2>
            <p>Lundi - Vendredi : <?php echo $horaires['lundi_vendredi']; ?></p>
            <p>Samedi : <?php echo $horaires['samedi']; ?></p>
        </div>
        </div>
            <section id="testimonial" class="container">
            <div class="container">
        <h2 class="title">Témoignages</h2>
        <div id="temoignages-container">
        <?php
require_once('connectdb.php');
// Requête pour récupérer uniquement les témoignages approuvés
$requeteTemoignages = $bdd->query('SELECT * FROM temoignages WHERE approuve = 1 ORDER BY id ASC');
$temoignages = $requeteTemoignages->fetchAll(PDO::FETCH_ASSOC);

// Affichage des témoignages approuvés
if ($temoignages) {
    foreach ($temoignages as $temoignage) {
        echo "<div class='comment'>";
        echo "<h3>" . htmlspecialchars($temoignage['nom_client']);
        // Étant donné que la requête SQL assure déjà que les témoignages sont approuvés, cette vérification est redondante
        if ($temoignage['approuve']) {
            echo " <img src='assets/Garage V Parrot -logos/icone-approuve.png' alt='Approuvé' style='width: 20px; height: 20px;' />";
            echo "</h3>";
            echo "<p>" . htmlspecialchars($temoignage['commentaire']) . "</p>";
        }
        echo "</div>";
    }
} else {
    echo "Aucun témoignage trouvé.";
}

// Fermeture de la connexion à la base de données
$bdd = null;
?>
    </div>
    <h2>Laisser un témoignage</h2>
    <form action="traitement_temoignage.php" method="post">
        <div>
            <label for="nom_client">Votre nom :</label>
            <input type="text" id="nom_client" name="nom_client" required>
        </div>
        <div>
            <label for="commentaire">Votre commentaire :</label>
            <textarea id="commentaire" name="commentaire" rows="4" required></textarea>
        </div>
        <div>
            <label for="note">Votre note :</label>
            <input type="number" id="note" name="note" min="1" max="5" required>
        </div>
        <button type="submit">Envoyer</button>
        <?php
    // Vérifie si le témoignage a été soumis avec succès
    if (isset($_SESSION['message'])) {
        echo "<p>Merci pour votre témoignage !</p>";
        // Efface le message de session pour qu'il ne s'affiche pas à nouveau après un rechargement de la page
        unset($_SESSION['message']);
    }
    ?>
    </form>
    </main>
   

    <footer class="bg-light p-3 fixed">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

