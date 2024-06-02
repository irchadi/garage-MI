<?php
session_start();

require_once __DIR__ . '/../config/connectdb.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

//HORAIRES
// Chemin vers le fichier JSON
$jsonFile = __DIR__ . '/../resources/horaires.json';
$jsonDir = dirname($jsonFile);

// Vérifier l'existence du dossier et le créer si nécessaire
if (!is_dir($jsonDir)) {
    mkdir($jsonDir, 0777, true);
}

// Lire le fichier JSON
if (file_exists($jsonFile)) {
    $jsonData = file_get_contents($jsonFile);
    $horaires = json_decode($jsonData, true);
} else {
    // Si le fichier n'existe pas, initialiser avec des valeurs par défaut
    $horaires = [
        'lundi_vendredi' => '',
        'samedi' => ''
    ];
}

// Initialiser la variable pour les messages d'erreur ou de succès
$message = "";

// Traiter le formulaire de soumission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['save_horaires'])) {
    $lundi_vendredi = htmlspecialchars($_POST['lundi_vendredi']);
    $samedi = htmlspecialchars($_POST['samedi']);

    // Mettre à jour les heures d'ouverture dans le tableau
    $horaires['lundi_vendredi'] = $lundi_vendredi;
    $horaires['samedi'] = $samedi;

    // Encoder le tableau en JSON et écrire dans le fichier
    if (file_put_contents($jsonFile, json_encode($horaires))) {
        $message = "Les heures d'ouverture ont été mises à jour avec succès.";
    } else {
        $message = "Erreur lors de la mise à jour des heures d'ouverture.";
    }
}


//SERVICES
// Traiter le formulaire d'ajout de service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_service'])) {
    $nom = htmlspecialchars($_POST['nom']);
    $description = htmlspecialchars($_POST['description']);
    $image = $_FILES['image'];

    // Vérifier et télécharger l'image
    $target_dir = __DIR__ . '/../public/assets/img/';
    $target_file = $target_dir . basename($image["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Vérifier si le fichier est une image
    $check = getimagesize($image["tmp_name"]);
    if ($check !== false) {
        $uploadOk = 1;
    } else {
        $message = "Le fichier n'est pas une image.";
        $uploadOk = 0;
    }

    // Vérifier si le fichier existe déjà
    if (file_exists($target_file)) {
        $message = "Désolé, le fichier existe déjà.";
        $uploadOk = 0;
    }

    // Vérifier la taille du fichier
    if ($image["size"] > 500000) {
        $message = "Désolé, votre fichier est trop volumineux.";
        $uploadOk = 0;
    }

    // Autoriser certains formats de fichier
    if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
        $message = "Désolé, seuls les fichiers JPG, JPEG, PNG et GIF sont autorisés.";
        $uploadOk = 0;
    }

    // Vérifier si $uploadOk est défini à 0 par une erreur
    if ($uploadOk == 0) {
        $message .= " Votre fichier n'a pas été téléchargé.";
    } else {
        if (move_uploaded_file($image["tmp_name"], $target_file)) {
            // Insérer le service dans la base de données
            $sql = "INSERT INTO services (nom, description, image) VALUES (:nom, :description, :image)";
            $stmt = $bdd->prepare($sql);
            $stmt->execute([
                ':nom' => $nom,
                ':description' => $description,
                ':image' => 'assets/img/' . basename($image["name"]) // Sauvegarder le chemin relatif de l'image
            ]);
            $message = "Le service a été ajouté avec succès.";
        } else {
            $message = "Désolé, une erreur s'est produite lors du téléchargement de votre fichier.";
        }
    }
}

// Traiter la suppression de service
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['supprimer_service'])) {
    $id = intval($_POST['supprimer_service']);
    // Supprimer le service de la base de données
    $sql = "DELETE FROM services WHERE id = :id";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':id' => $id]);
    $message = "Le service a été supprimé avec succès.";
}

//USERS
// Traiter le formulaire d'ajout d'utilisateur
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter_utilisateur'])) {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);
    $user_type = htmlspecialchars($_POST['user_type']);

    // Hacher le mot de passe
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Insérer l'utilisateur dans la base de données
    $sql = "INSERT INTO users (username, password, user_type) VALUES (:username, :password, :user_type)";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([
        ':username' => $username,
        ':password' => $hashed_password,
        ':user_type' => $user_type
    ]);

    $message = "Utilisateur ajouté avec succès.";
}

// Récupérer les services depuis la base de données
$sql = "SELECT id, nom FROM services";
$stmt = $bdd->query($sql);
$services = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Garage V. Parrot - Mon Compte</title>
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

<main class="container">
    <div class="mb-4">
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

    <div class="mb-4">
        <h2>Ajouter un utilisateur</h2>
        <form action="moncompte.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="user_type">Type d'utilisateur:</label>
                <select class="form-control" id="user_type" name="user_type" required>
                    <option value="admin">Admin</option>
                    <option value="employe">Employé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" name="ajouter_utilisateur">Ajouter l'utilisateur</button>
        </form>
    </div>

    <div class="mb-4">
        <h2>Ajouter un service</h2>
        <?php if ($message) { echo "<p>$message</p>"; } ?>
        <form action="moncompte.php" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom du service:</label>
                <input type="text" class="form-control" id="nom" name="nom" required>
            </div>
            <div class="form-group">
                <label for="description">Description:</label>
                <textarea class="form-control" id="description" name="description" required></textarea>
            </div>
            <div class="form-group">
                <label for="image">Image:</label>
                <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
            </div>
            <button type="submit" class="btn btn-primary" name="ajouter_service">Ajouter le service</button>
        </form>
        <hr>
        <h2>Services existants</h2>
        <?php if (count($services) > 0): ?>
            <form action="moncompte.php" method="post">
                <?php foreach ($services as $service): ?>
                    <div class="form-group d-flex justify-content-between align-items-center">
                        <span><?php echo htmlspecialchars($service['nom']); ?></span>
                        <button type="submit" class="btn btn-danger" name="supprimer_service" value="<?php echo $service['id']; ?>">Supprimer</button>
                    </div>
                <?php endforeach; ?>
            </form>
        <?php else: ?>
            <p>Aucun service trouvé.</p>
        <?php endif; ?>
    </div>

    <div class="mb-4">
        <h2>Contacts</h2>
        <table class="table table-striped">
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
                    echo "<td><a href='supprimer-contact.php?id=".$ligne['id']."' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce contact ?\");'>Supprimer</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mb-4">
        <h2>Témoignages</h2>
        <table class="table table-striped">
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
                    echo "<td><a href='supprimer-temoignage.php?id=".$ligne['id']."' class='btn btn-danger' onclick='return confirm(\"Êtes-vous sûr de vouloir supprimer ce témoignage ?\");'>Supprimer</a></td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="mb-4">
    <h2>Modifier les heures d'ouverture</h2>
        <?php if ($message) { echo "<p>$message</p>"; } ?>
        <form action="moncompte.php" method="post">
            <div class="form-group">
                <label for="lundi_vendredi">Lundi - Vendredi :</label>
                <input type="text" class="form-control" id="lundi_vendredi" name="lundi_vendredi" value="<?php echo htmlspecialchars($horaires['lundi_vendredi']); ?>" required>
            </div>
            <div class="form-group">
                <label for="samedi">Samedi :</label>
                <input type="text" class="form-control" id="samedi" name="samedi" value="<?php echo htmlspecialchars($horaires['samedi']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary" name="save_horaires">Enregistrer les heures</button>
        </form>
    </div>

    <div class="mb-4">
        <a href="logout.php" class="btn btn-secondary">Se déconnecter</a>
    </div>
</main>

<footer>
    <p class="text-center">Garage V. Parrot est votre partenaire de confiance pour l'entretien, la réparation, et la vente de véhicules d'occasion à Toulouse.</p>
</footer>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>
</html>
