<?php
require_once('connectdb.php'); // Assurez-vous que ce fichier contient vos informations de connexion à la base de données

// Vérification si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_FILES['image_principale'])) {
    // Traitement du téléchargement de l'image
    $uploadDir = 'assets/img/'; // Chemin du dossier où l'image sera enregistrée
    $uploadFile = $uploadDir . basename($_FILES['image_principale']['name']);
    $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
    $validExtensions = array('jpg', 'jpeg', 'png', 'gif');

    if (in_array($imageFileType, $validExtensions)) {
        // Vérification de la taille du fichier
        if ($_FILES['image_principale']['size'] < 5000000) { // Limite de 5MB
            $newFileName = $uploadDir . uniqid() . '.' . $imageFileType;
            if (move_uploaded_file($_FILES['image_principale']['tmp_name'], $newFileName)) {
                // Le fichier a été téléchargé et enregistré avec succès
                $imagePath = $newFileName;
            } else {
                echo "Il y a eu une erreur lors du téléchargement de votre fichier.";
                exit;
            }
        } else {
            echo "Désolé, votre fichier est trop volumineux.";
            exit;
        }
    } else {
        echo "Seuls les fichiers JPG, JPEG, PNG & GIF sont autorisés.";
        exit;
    }

    // Collecte et nettoyage des autres données du formulaire
    $marque = htmlspecialchars($_POST['marque']);
    $modele = htmlspecialchars($_POST['modele']);
    $prix = htmlspecialchars($_POST['prix']);
    $annee_mise_en_circulation = htmlspecialchars($_POST['annee_mise_en_circulation']);
    $kilometrage = htmlspecialchars($_POST['kilometrage']);
    $description = htmlspecialchars($_POST['description']);
    $caracteristiques_techniques = htmlspecialchars($_POST['caracteristiques_techniques']);
    $equipements_options = htmlspecialchars($_POST['equipements_options']);

    // Préparation de la requête d'insertion SQL
    try {
        $requete = $bdd->prepare("INSERT INTO vehicules_occasion (marque, modele, prix, annee_mise_en_circulation, kilometrage, description, caracteristiques_techniques, equipements_options, image_principale) VALUES (:marque, :modele, :prix, :annee_mise_en_circulation, :kilometrage, :description, :caracteristiques_techniques, :equipements_options, :image_principale)");

        // Liaison des paramètres et exécution de la requête
        $requete->execute(array(
            ':marque' => $marque,
            ':modele' => $modele,
            ':prix' => $prix,
            ':annee_mise_en_circulation' => $annee_mise_en_circulation,
            ':kilometrage' => $kilometrage,
            ':description' => $description,
            ':caracteristiques_techniques' => $caracteristiques_techniques,
            ':equipements_options' => $equipements_options,
            ':image_principale' => $imagePath // Utilisez le chemin de l'image sauvegardée
        ));

        echo "La voiture a été ajoutée avec succès.";
    } catch (Exception $e) {
        die('Erreur lors de l\'insertion dans la base de données : ' . $e->getMessage());
    }
} else {
    echo "Erreur dans la soumission du formulaire.";
}
?>
