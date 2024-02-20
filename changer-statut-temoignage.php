<?php
require_once('connectdb.php'); // Connexion à la base de données

if (isset($_GET['id']) && isset($_GET['statut'])) {
    $id = $_GET['id'];
    $statut = $_GET['statut'] == '1' ? true : false; // Convertit le statut en boolean

    try {
        $requete = $bdd->prepare("UPDATE temoignages SET approuve = :statut WHERE id = :id");
        $requete->execute([':statut' => $statut, ':id' => $id]);

        header('Location: liste-temoignages.php'); // Redirige vers la liste des témoignages après la mise à jour
    } catch (Exception $e) {
        die('Erreur lors de la mise à jour du témoignage : ' . $e->getMessage());
    }
} else {
    echo "Informations manquantes pour changer le statut.";
}
?>
