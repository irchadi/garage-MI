<?php
require_once('connectdb.php'); // Inclut votre script de connexion à la base de données

// Vérifie si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collecte les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $mot_de_passe = password_hash($_POST['mot_de_passe'], PASSWORD_DEFAULT); // Hashage du mot de passe pour la sécurité
    $type = $_POST['type'];

    try {
        // Prépare et lie les paramètres pour l'insertion SQL
        $requete = $bdd->prepare("INSERT INTO utilisateurs (nom, prenom, email, mot_de_passe, type) VALUES (?, ?, ?, ?, ?)");
        $requete->execute([$nom, $prenom, $email, $mot_de_passe, $type]);

        echo "Utilisateur ajouté avec succès.";
    } catch (Exception $e) {
        die('Erreur lors de l\'ajout de l\'utilisateur : ' . $e->getMessage());
    }
} else {
    echo "Erreur dans la soumission du formulaire.";
}
?>
