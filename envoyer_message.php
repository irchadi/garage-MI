<?php
session_start(); // Démarrage ou reprise de la session

require_once 'connectdb.php'; // Inclusion de la connexion à la base de données

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération sécurisée des données du formulaire
    $nom = filter_input(INPUT_POST, 'nom', FILTER_SANITIZE_SPECIAL_CHARS);
    $prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $numero_telephone = filter_input(INPUT_POST, 'telephone', FILTER_SANITIZE_SPECIAL_CHARS);
    $message = filter_input(INPUT_POST, 'message', FILTER_SANITIZE_SPECIAL_CHARS);
    $annonce_associee = filter_input(INPUT_POST, 'annonce_associee', FILTER_SANITIZE_SPECIAL_CHARS);
    $date_soumission = date('Y-m-d H:i:s');

    // Préparation de la requête d'insertion
    $requete = $bdd->prepare("INSERT INTO contacts (nom, prenom, email, numero_telephone, message, date_soumission, annonce_associee) VALUES (?, ?, ?, ?, ?, ?, ?)");

    try {
        // Exécution de la requête
        $requete->execute([$nom, $prenom, $email, $numero_telephone, $message, $date_soumission, $annonce_associee]);
        
        // Stockage du message de succès dans $_SESSION
        $_SESSION['message_succes'] = "Demande de prise de contact envoyée !";
        
        // Redirection vers la page précédente
        $redirectUrl = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header('Location: ' . $redirectUrl);
        exit;
    } catch (Exception $e) {
        die('Erreur lors de l\'envoi du message : ' . $e->getMessage());
    }
} else {
    echo "Le formulaire n'a pas été soumis correctement.";
}
?>


