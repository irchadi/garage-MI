<?php
require_once('connectdb.php'); // Assurez-vous d'inclure votre connexion à la base de données

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    try {
        $requete = $bdd->prepare("DELETE FROM temoignages WHERE id = :id");
        $requete->execute([':id' => $id]);
        
        // Redirection vers la page de liste des témoignages après la suppression
        header('Location: moncompte.php?suppression_reussie');
    } catch (Exception $e) {
        die('Erreur lors de la suppression du témoignage : ' . $e->getMessage());
    }
} else {
    echo "Aucun identifiant de témoignage fourni.";
}
?>
