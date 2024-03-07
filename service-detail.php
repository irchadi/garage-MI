<?php
require_once 'connectdb.php'; // Assurez-vous que ce fichier établit la connexion à votre base de données

// Récupérer les noms des services depuis la base de données
$requete = $bdd->query("SELECT id, nom FROM services");
$services = $requete->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage V. Parrot</title>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
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
<main class="container">
    <?php
    if (isset($_GET['id'])) {
        $id = $_GET['id'];
        $stmt = $bdd->prepare("SELECT * FROM services WHERE id = ?");
        $stmt->execute([$id]);
        $service = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($service) {
            echo "<h1>" . htmlspecialchars($service['nom']) . "</h1>";
            echo "<p>" . nl2br(htmlspecialchars($service['description'])) . "</p>";
            echo "<img src='" . htmlspecialchars($service['image']) . "' alt='" . htmlspecialchars($service['nom']) . "' style='max-width: 100%; height: auto;'/>";
        } else {
            echo "Service non trouvé.";
        }
    } else {
        echo "Aucun identifiant de service fourni.";
    }
    ?>
</main>

<footer class="bg-light p-3 fixed-bottom">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>


