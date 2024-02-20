<?php
// Inclure le fichier de connexion
require_once('connectdb.php');


// Initialisation des variables de filtrage
$kilometrageMax = isset($_GET['kilometrage_max']) ? intval($_GET['kilometrage_max']) : '';
$prixMax = isset($_GET['prix_max']) ? intval($_GET['prix_max']) : '';
$anneeMin = isset($_GET['annee_min']) ? intval($_GET['annee_min']) : '';

// Construction de la requête SQL en fonction des filtres
$sql = "SELECT * FROM vehicules_occasion WHERE 1";
if (!empty($kilometrageMax)) $sql .= " AND kilometrage <= :kilometrageMax";
if (!empty($prixMax)) $sql .= " AND prix <= :prixMax";
if (!empty($anneeMin)) $sql .= " AND annee_mise_en_circulation >= :anneeMin";

$query = $bdd->prepare($sql);

// Binding des paramètres
if (!empty($kilometrageMax)) $query->bindParam(':kilometrageMax', $kilometrageMax, PDO::PARAM_INT);
if (!empty($prixMax)) $query->bindParam(':prixMax', $prixMax, PDO::PARAM_INT);
if (!empty($anneeMin)) $query->bindParam(':anneeMin', $anneeMin, PDO::PARAM_INT);

$query->execute();
$vehicules = $query->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage V. Parrot</title>
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

    <main class="container mt-4">
        <h1>Véhicules d'occasion</h1>
        <form method="get" class="mb-4">
    <div class="form-row">
        <div class="col">
            <input type="number" class="form-control" name="kilometrage_max" placeholder="Kilométrage maximum" value="<?= $kilometrageMax ?>">
        </div>
        <div class="col">
            <input type="number" class="form-control" name="prix_max" placeholder="Prix maximum" value="<?= $prixMax ?>">
        </div>
        <div class="col">
            <input type="number" class="form-control" name="annee_min" placeholder="Année minimum" value="<?= $anneeMin ?>">
        </div>
        <div class="col">
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
        <!-- Bouton de réinitialisation -->
        <div class="col">
            <a href="vehicules.php" class="btn btn-secondary">Effacer les filtres</a>
        </div>
    </div>
</form>

        <div class="row">
            <?php foreach ($vehicules as $vehicule): ?>
                <div class="col-md-4 mb-4">
                    <div class="card">
                        <img src="<?= htmlspecialchars($vehicule['image_principale']) ?>" class="card-img-top" alt="<?= htmlspecialchars($vehicule['marque']) . ' ' . htmlspecialchars($vehicule['modele']) ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($vehicule['marque']) . ' ' . htmlspecialchars($vehicule['modele']) ?></h5>
                            <p class="card-text">Prix: <?= htmlspecialchars($vehicule['prix']) ?>€</p>
                            <p class="card-text">Année: <?= htmlspecialchars($vehicule['annee_mise_en_circulation']) ?></p>
                            <p class="card-text">Kilométrage: <?= htmlspecialchars($vehicule['kilometrage']) ?>km</p>
                            <a href="fichevehicule.php?id=<?= $vehicule['id'] ?>" class="btn btn-primary">Voir détails</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
            <?php if (empty($vehicules)): ?>
                <p>Aucun véhicule correspondant aux critères.</p>
            <?php endif; ?>
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
