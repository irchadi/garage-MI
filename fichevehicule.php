<?php
// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=garage_v_parrot', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

// Vérification de l'existence de l'ID du véhicule dans l'URL
if (isset($_GET['id'])) {
    $idVehicule = intval($_GET['id']); // Sécurisation de l'ID du véhicule récupéré

    // Récupération des informations du véhicule spécifique depuis la base de données
    $requete = $bdd->prepare('SELECT * FROM vehicules_occasion WHERE id = :id');
    $requete->execute(array(':id' => $idVehicule));
    $vehicule = $requete->fetch();

    if (!$vehicule) {
        die('Véhicule non trouvé.');
    }
} else {
    die('ID du véhicule non spécifié.');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage V. Parrot - <?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?></title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
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
<?php
session_start(); // Assurez-vous d'appeler session_start() au début

if (isset($_SESSION['message_succes'])) {
    echo "<div class='alert alert-success'>" . $_SESSION['message_succes'] . "</div>";
    unset($_SESSION['message_succes']); // Suppression du message de la session après affichage
}
?>
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
                </ul>
            </div>
        </nav>
    </header>

    <main class="container">
    <h1 class="text-center"><?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?></h1>
    <div class="row">
        <div class="col-md-6">
            <img class="img-fluid" src="<?= htmlspecialchars($vehicule['image_principale']) ?>" alt="<?= htmlspecialchars($vehicule['marque'] . ' ' . $vehicule['modele']) ?>">
        </div>
        <div class="col-md-6">
            <h2>Détails</h2>
            <p>Prix: <?= htmlspecialchars($vehicule['prix']) ?> €</p>
            <p>Année de mise en circulation: <?= htmlspecialchars($vehicule['annee_mise_en_circulation']) ?></p>
            <p>Kilométrage: <?= htmlspecialchars($vehicule['kilometrage']) ?> km</p>
            <!-- Plus de détails -->
            <h2>Plus de détails :</h2>
            <p>Description :</p>
            <p><?= htmlspecialchars($vehicule['description']) ?></p>

            <p>Caractéristiques techniques :</p>
            <p><?= htmlspecialchars($vehicule['caracteristiques_techniques']) ?></p>

            <p>Équipements et options :</p>
            <p><?= htmlspecialchars($vehicule['equipements_options']) ?></p>
            <!-- Formulaire de contact mis à jour -->
<h2>Contactez-nous au sujet de ce véhicule :</h2>
<form action="envoyer_message.php" method="post">
    <input type="hidden" name="annonce_associee" value="<?= $vehicule['id'] ?>">

    <div class="form-group">
        <label for="nom">Votre Nom</label>
        <input type="text" class="form-control" id="nom" name="nom" required>
    </div>
    <div class="form-group">
    <label for="prenom">Prénom :</label>
    <input type="text" class="form-control" id="prenom" name="prenom" required>
</div>

    <div class="form-group">
        <label for="email">Votre Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="form-group">
        <label for="telephone">Votre Téléphone</label>
        <input type="tel" class="form-control" id="telephone" name="telephone">
    </div>
    <div class="form-group">
    <label for="message">Votre Message</label>
    <textarea class="form-control" id="message" name="message" rows="4" required>Bonjour, 
Je suis intéressé(e) par le véhicule <?= htmlspecialchars($vehicule['marque']) . " " . htmlspecialchars($vehicule['modele']) ?>. Veuillez me contacter pour plus d'informations.</textarea>
    </div>
    <button type="submit" class="btn btn-primary">Envoyer</button>
</form>
        </div>
    </div>
</main>
<footer class="bg-light p-3 fixed">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
