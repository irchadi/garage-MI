<?php
session_start(); // Démarre la session
require_once __DIR__ . '/../config/connectdb.php'; // Inclure le fichier de connexion à la base de données

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

    // Stocker le message de notification dans une variable de session
    $_SESSION['notification'] = "Votre témoignage a été soumis avec succès!";

    // Redirection vers la page précédente ou une page spécifique
    if (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_NAME']) {
        header('Location: ' . htmlspecialchars($_SERVER['HTTP_REFERER']));
    } else {
        header('Location: index.php');
    }
    exit; // Utiliser exit après la redirection
}
?>

