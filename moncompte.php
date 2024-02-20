<?php
// Inclure le fichier de connexion
require_once('connectdb.php');

session_start(); // Démarrer la session pour accéder aux variables de session

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['utilisateur'])) {
    // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
    header("Location: login.php");
    exit();
}

// Vérifier si l'utilisateur est de type "admin"
if ($_SESSION['type'] !== 'admin') {
    // Rediriger vers une page d'erreur ou une autre page appropriée si l'utilisateur n'est pas autorisé
    header("Location: erreur.php");
    exit();
}

// Si l'utilisateur est connecté et est de type "admin", continuer l'exécution du code...

// Fonction pour changer le statut d'approbation d'un témoignage
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
    header("Location: moncompte.php");
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
// Chargement des services
$services = json_decode(file_get_contents('services.json'), true);

// Ajouter un nouveau service
if (isset($_POST['ajouter_service']) && !empty($_POST['nouveau_service'])) {
    $nouveauService = [
        "id" => count($services) + 1, // Attribution d'un nouvel ID
        "nom" => $_POST['nouveau_service'],
        "description" => $_POST['description_service'] // Assurez-vous d'ajouter un champ pour la description dans le formulaire
    ];
    $services[] = $nouveauService;
    file_put_contents('services.json', json_encode($services, JSON_PRETTY_PRINT));
    // Optionnel : Générer une page pour le service
}

// Supprimer un service
if (isset($_POST['supprimer_service'])) {
    $indexASupprimer = $_POST['index'];
    array_splice($services, $indexASupprimer, 1); // Supprime le service de l'array
    file_put_contents('services.json', json_encode($services, JSON_PRETTY_PRINT));
}

// Recharger les services après modification
$services = json_decode(file_get_contents('services.json'), true);
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

<div class="container">
    <h2>Ajouter un utilisateur</h2>
    <form action="traitement-ajouter-utilisateur.php" method="post">
        <div class="form-group">
            <label for="nom">Nom:</label>
            <input type="text" class="form-control" id="nom" name="nom" required>
        </div>
        <div class="form-group">
            <label for="prenom">Prénom:</label>
            <input type="text" class="form-control" id="prenom" name="prenom" required>
        </div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" class="form-control" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="mot_de_passe">Mot de passe:</label>
            <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
        </div>
        <div class="form-group">
            <label for="type">Type d'utilisateur:</label>
            <select class="form-control" id="type" name="type">
                <option value="admin">Admin</option>
                <option value="employe">Employé</option>
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Ajouter</button>
    </form>
</div>


<div class="container">
    <h2>Contacts</h2>
    <table class="table">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Message</th>
                <th>Date de Soumission</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $requete = $bdd->query("SELECT * FROM contacts");
            while ($ligne = $requete->fetch()) {
                echo "<tr>";
                echo "<td>".htmlspecialchars($ligne['nom'])."</td>";
                echo "<td>".htmlspecialchars($ligne['prenom'])."</td>";
                echo "<td>".htmlspecialchars($ligne['email'])."</td>";
                echo "<td>".htmlspecialchars($ligne['numero_telephone'])."</td>";
                echo "<td>".htmlspecialchars($ligne['message'])."</td>";
                echo "<td>".htmlspecialchars($ligne['date_soumission'])."</td>";
                // Ajoutez ici le bouton de suppression
                echo "<td><a href='supprimer-contact.php?id=".$ligne['id']."' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce contact ?\");'>Supprimer</a></td>";
                echo "</tr>";
            }
            ?>
        </tbody>
    </table>
</div>
<?php if (isset($_SESSION['message'])): ?>
    <div class="alert alert-success">
        <?= $_SESSION['message']; ?>
    </div>
    <?php unset($_SESSION['message']); ?>
<?php endif; ?>

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
        </tbody>
    </table>
</div>
<!-- Formulaire pour modifier les heures d'ouverture -->
<div>
    <h2>Modifier les heures d'ouverture</h2>
    <form action="moncompte.php" method="post">
        <label for="lundi_vendredi">Lundi - Vendredi :</label>
        <input type="text" id="lundi_vendredi" name="lundi_vendredi" value="<?php echo $horaires['lundi_vendredi']; ?>" required>
        <br>
        <label for="samedi">Samedi :</label>
        <input type="text" id="samedi" name="samedi" value="<?php echo $horaires['samedi']; ?>" required>
        <br>
        <button type="submit" name="save_horaires">Enregistrer les heures</button>
    </form>
</div>
<div class="container">
        <a href="logout.php">Se déconnecter</a>
</div>
</main>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    </body>
</html>

