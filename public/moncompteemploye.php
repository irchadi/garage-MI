<?php
// Inclure le fichier de connexion
require_once __DIR__ . '/../config/connectdb.php';

session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'employe') {
    header('Location: login.php');
    exit();
}


//VERIFICATION CONNEXION EMPLOYE
// Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Vérifier le type d'utilisateur pour accéder à cette page
if ($_SESSION['user_type'] !== 'employe') {
    header('Location: unauthorized.php'); // Créez une page unauthorized.php pour afficher un message d'accès refusé
    exit;
}

function changerStatutTemoignage($bdd, $id, $statut) {
    try {
        $requete = $bdd->prepare("UPDATE temoignages SET approuve = :statut WHERE id = :id");
        $requete->execute([':statut' => $statut, ':id' => $id]);
        echo "<p>Le statut du témoignage a été mis à jour.</p>";
    } catch (Exception $e) {
        die('Erreur lors de la mise à jour du témoignage : ' . $e->getMessage());
    }
}

// Vérifie si les paramètres de changement de statut sont présents
if (isset($_GET['id']) && isset($_GET['statut'])) {
    changerStatutTemoignage($bdd, $_GET['id'], $_GET['statut']);
    
    // Redirection pour éviter le rechargement du formulaire sur refresh
    if (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_NAME']) {
        header("Location: " . htmlspecialchars($_SERVER['HTTP_REFERER']));
    } else {
        // Rediriger vers une page par défaut si la page précédente n'est pas disponible ou n'est pas du même domaine
        header("Location: moncompte.php");
    }
    exit;
}


// Traitement de la suppression d'un service
if (isset($_POST['supprimer_service'])) {
    $indexASupprimer = $_POST['index'];
    if (isset($services[$indexASupprimer])) {
        unset($services[$indexASupprimer]); // Supprime le service
        $services = array_values($services); // Réindexe l'array
        file_put_contents('services.json', json_encode($services, JSON_PRETTY_PRINT)); // Sauvegarde la nouvelle liste
        echo "Service supprimé avec succès.";
    }
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

    <body>
    <main class="container">
    <div class="container">
    <h2>Ajouter une nouvelle voiture d'occasion</h2>
    <form action="traitement-ajouter-vehicule.php" method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label for="marque">Marque:</label>
            <input type="text" class="form-control" id="marque" name="marque" required>
        </div>
        <div class="form-group">
            <label for="modele">Modèle:</label>
            <input type="text" class="form-control" id="modele" name="modele" required>
        </div>
        <div class="form-group">
            <label for="prix">Prix (€):</label>
            <input type="number" step="0.01" class="form-control" id="prix" name="prix" required>
        </div>
        <div class="form-group">
            <label for="annee_mise_en_circulation">Année de mise en circulation:</label>
            <input type="number" class="form-control" id="annee_mise_en_circulation" name="annee_mise_en_circulation" required>
        </div>
        <div class="form-group">
            <label for="kilometrage">Kilométrage:</label>
            <input type="number" class="form-control" id="kilometrage" name="kilometrage" required>
        </div>
        <div class="form-group">
            <label for="description">Description:</label>
            <textarea class="form-control" id="description" name="description"></textarea>
        </div>
        <div class="form-group">
            <label for="caracteristiques_techniques">Caractéristiques techniques:</label>
            <textarea class="form-control" id="caracteristiques_techniques" name="caracteristiques_techniques"></textarea>
        </div>
        <div class="form-group">
            <label for="equipements_options">Équipements et options:</label>
            <textarea class="form-control" id="equipements_options" name="equipements_options"></textarea>
        </div>
        <div class="form-group">
            <label for="image_principale">Image principale :</label>
            <input type="file" class="form-control" id="image_principale" name="image_principale" accept="image/*">
    </div>
        <button type="submit" class="btn btn-primary">Ajouter la voiture</button>
    </form>
</div>

</div>
<div class="formulaire-temoignage">
    <h2>Laisser un témoignage client</h2>
    <form action="traitement_temoignage.php" method="post" id="formTemoignage">
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
    if (isset($_SESSION['notification'])) {
        echo '<p>' . htmlspecialchars($_SESSION['notification']) . '</p>';
        unset($_SESSION['notification']); // Supprimer le message après l'affichage
    }
    ?>
    </form>
    </div>

<div class="container">
    <h2>Témoignages</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nom du Client</th>
                <th>Commentaire</th>
                <th>Note</th>
                <th>Date</th>
                <th>Approuvé</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
    $requete = $bdd->query("SELECT * FROM temoignages");
    while ($ligne = $requete->fetch()) {
        echo "<tr>";
        echo "<td>".htmlspecialchars($ligne['nom_client'])."</td>";
        echo "<td>".htmlspecialchars($ligne['commentaire'])."</td>";
        echo "<td>".htmlspecialchars($ligne['note'])."</td>";
        echo "<td>".htmlspecialchars($ligne['date_temoignage'])."</td>";
        echo "<td>".($ligne['approuve'] ? "Oui" : "Non")."</td>";
        echo "<td><a href='changer-statut-temoignage.php?id=".$ligne['id']."&statut=".($ligne['approuve'] ? "0" : "1")."' class='btn btn-info'>".($ligne['approuve'] ? "Désapprouver" : "Approuver")."</a></td>";
        // Ajoutez ici le lien ou le bouton de suppression
        echo "<td><a href='supprimer-temoignage.php?id=".$ligne['id']."' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce témoignage ?\");'>Supprimer</a></td>";
        echo "</tr>";
    }
    ?>
        </tbody>
    </table>
</div>
<div class="container">
        <a href="logoutemploye.php">Se déconnecter</a>
</div>
<!-- Formulaire pour modifier les heures d'ouverture -->
</main>
<footer class="bg-light p-3 fixed">
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>