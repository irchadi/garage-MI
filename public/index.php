<?php
session_start();

require_once __DIR__ . '/../config/connectdb.php';


// Chemin vers le fichier JSON
$jsonFile = __DIR__ . '/../public/resources/horaires.json';

// Lire le fichier JSON
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $horaires = json_decode($jsonData, true);
} else {
    // Si le fichier n'existe pas, afficher des messages d'erreur
    $horaires = [
        'lundi_vendredi' => 'Horaires non disponibles',
        'samedi' => 'Horaires non disponibles'
    ];
}

//////////////////////////////////////////////////////////////////////////////////////////////
// Déterminer la requête de l'URL
$request = trim($_SERVER['REQUEST_URI'], '/');

switch ($request) {
    case '':
    case 'home':
        // Logique pour afficher la page d'accueil
        break;
    case 'employe':
        // Assurez-vous que l'utilisateur est connecté en tant qu'employé
        if (isset($_SESSION['utilisateur']) && $_SESSION['type'] === 'employe') {
            require __DIR__ . '/../app/views/moncompteemploye.php';
        } else {
            header('Location: loginemploye.php');
            exit();
        }
        break;
    case 'admin':
        // Assurez-vous que l'utilisateur est connecté en tant qu'administrateur
        if (isset($_SESSION['utilisateur']) && $_SESSION['type'] === 'admin') {
            require __DIR__ . '/../app/views/moncompte.php';
        } else {
            header('Location: login.php');
            exit();
        }
        break;
    default:
        // Page non trouvée ou autre logique ici
        break;
}


// Requête pour récupérer uniquement les témoignages approuvés
$requeteTemoignages = $bdd->query('SELECT * FROM temoignages WHERE approuve = 1 ORDER BY id DESC');
$temoignages = $requeteTemoignages->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les noms des services depuis la base de données
$requeteServices = $bdd->query("SELECT id, nom FROM services");
$services = $requeteServices->fetchAll(PDO::FETCH_ASSOC);
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
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Nos services</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php foreach ($services as $service): ?>
                            <a class="dropdown-item" href="service-detail.php?id=<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['nom']); ?>
                        </a>
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
    <div class="logo-container">
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
            <p>Lundi - Vendredi : <?php echo htmlspecialchars($horaires['lundi_vendredi']); ?></p>
            <p>Samedi : <?php echo htmlspecialchars($horaires['samedi']); ?></p>
        </div>
    </div>
    <div>
        <h2 class="title">Témoignages</h2>
        <div id="temoignages-container">
            <?php
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
            ?>
        </div>
    </div>
    <div class="formulaire-temoignage mt-3">
    <h2 class="text-center">Laisser un témoignage</h2>
    <form action="traitement_temoignage.php" method="post" id="formTemoignage" class="d-flex flex-column align-items-center">
        <div class="mb-3">
            <label for="nom_client" class="form-label">Votre nom :</label>
            <input type="text" id="nom_client" name="nom_client" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="commentaire" class="form-label">Votre commentaire :</label>
            <textarea id="commentaire" name="commentaire" rows="4" class="form-control" required></textarea>
        </div>
        <div class="mb-3">
            <label for="note" class="form-label">Votre note (sur 5) :</label>
            <input type="number" id="note" name="note" min="1" max="5" class="form-control" required placeholder="Note sur 5">
        </div>
        <button type="submit" class="btn btn-primary">Envoyer</button>
        <?php
        // Vérifie si le témoignage a été soumis avec succès
        if (isset($_SESSION['notification'])) {
            echo '<p>' . htmlspecialchars($_SESSION['notification']) . '</p>';
            unset($_SESSION['notification']); // Supprimer le message après l'affichage
        }
        ?>
    </form>
</div>

<footer class="bg-light p-3">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>
    </main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>

