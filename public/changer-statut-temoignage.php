<?php
require_once __DIR__ . '/../config/connectdb.php'; // Connexion à la base de données

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id = $_GET['id'];
    $statut = $_GET['statut'] == '1' ? true : false; // Convertit le statut en boolean

    try {
        $requete = $bdd->prepare("UPDATE temoignages SET approuve = :statut WHERE id = :id");
        $requete->execute([':statut' => $statut, ':id' => $id]);

        // Vérifie si l'en-tête Referer est présent et redirige vers celui-ci
        if (isset($_SERVER['HTTP_REFERER'])) {
            header('Location: ' . htmlspecialchars($_SERVER['HTTP_REFERER']));
        } else {
            // Redirige vers une page par défaut si HTTP_REFERER n'est pas disponible
            header('Location: index.php'); // Assurez-vous que cette page existe
        }
        exit;
    } catch (Exception $e) {
        die('Erreur lors de la mise à jour du témoignage : ' . $e->getMessage());
    }
} else {
    echo "Informations manquantes pour changer le statut.";
}
?>


