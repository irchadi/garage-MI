<?php
session_start();
// Inclure le fichier de connexion
require_once('connectdb.php');
// Fonction pour changer le statut d'approbation d'un témoignage


// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: loginemploye.php");
    exit();
}

// Vérifier si l'utilisateur est de type "employe"
if ($_SESSION['type'] !== 'employe') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé
    header("Location: erreur.php");
    exit();
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
    header("Location: moncompteemploye.php");
    exit;
}
?>

<?php
// Lecture des heures d'ouverture actuelles
$horaires = json_decode(file_get_contents('horaires.json'), true);

// Traitement du formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_horaires'])) {
    $horaires = [
        'lundi_vendredi' => $_POST['lundi_vendredi'],
        'samedi' => $_POST['samedi']
    ];
    file_put_contents('horaires.json', json_encode($horaires, JSON_PRETTY_PRINT));
    echo "Les heures d'ouverture ont été mises à jour.";
    header('Location: moncompte.php'); // Redirection pour éviter le rechargement du formulaire
    exit;
}
if (isset($_POST['ajouter_service'])) {
    $nouveauService = $_POST['nouveau_service'];
    
    // Lire les services existants
    $services = json_decode(file_get_contents('services.json'), true);
    if (!in_array($nouveauService, $services)) { // Éviter les doublons
        $services[] = $nouveauService; // Ajouter le nouveau service
        file_put_contents('services.json', json_encode($services, JSON_PRETTY_PRINT)); // Sauvegarder la liste mise à jour
        echo "Service ajouté avec succès.";
    } else {
        echo "Ce service existe déjà.";
    }
}

// Chargement des services existants
$services = json_decode(file_get_contents('services.json'), true);

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
    <h2>Témoignage client</h2>
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