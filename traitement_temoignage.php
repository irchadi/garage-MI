<?php
session_start(); // Démarre la session
require_once('connectdb.php'); // Inclure le fichier de connexion à la base de données

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $nom_client = $_POST['nom_client'];
    $commentaire = $_POST['commentaire'];
    $note = $_POST['note'];
    $date_temoignage = date('Y-m-d H:i:s'); // Date et heure actuelles

    // Préparer et exécuter la requête SQL pour insérer le témoignage dans la base de données
    $requete = $bdd->prepare("INSERT INTO temoignages (nom_client, commentaire, note, date_temoignage, approuve) VALUES (:nom_client, :commentaire, :note, :date_temoignage, :approuve)");
    $requete->execute([
        ':nom_client' => $nom_client,
        ':commentaire' => $commentaire,
        ':note' => $note,
        ':date_temoignage' => $date_temoignage,
        ':approuve' => false // Par défaut, le témoignage n'est pas approuvé
    ]);

  // Redirection vers la page précédente
  if (isset($_SERVER['HTTP_REFERER'])) {
}

    // Rediriger l'utilisateur vers la page d'accueil
    header('Location: ' . htmlspecialchars($_SERVER['PHP_SELF']));
    exit; // Assurez-vous d'utiliser exit après la redirection pour éviter l'exécution du reste du script
}
?>