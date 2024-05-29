<?php
session_start(); // Démarrer la session au début du script
require_once __DIR__ . '/../config/connectdb.php'; // Inclure la connexion à la base de données

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $requete = $bdd->prepare("DELETE FROM temoignages WHERE id = :id");
        $requete->execute([':id' => $id]);

        // Stocker le message de notification dans une variable de session
        $_SESSION['notification'] = "Témoignage supprimé avec succès.";

        // Redirection vers la page précédente
        if (isset($_SERVER['HTTP_REFERER']) && parse_url($_SERVER['HTTP_REFERER'], PHP_URL_HOST) == $_SERVER['SERVER_NAME']) {
            header('Location: ' . htmlspecialchars($_SERVER['HTTP_REFERER']));
        } else {
            // Rediriger vers une page par défaut si la page précédente n'est pas définie ou appartient à un autre domaine
            header('Location: index.php');
        }
        exit;
    } catch (Exception $e) {
        die('Erreur lors de la suppression du témoignage : ' . $e->getMessage());
    }
} else {
    echo "Aucun identifiant de témoignage fourni.";
}
?>

