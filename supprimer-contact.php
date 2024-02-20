<?php
session_start();
require_once 'connectdb.php';

// Vérifiez si l'utilisateur est connecté et est de type "admin"
if (!isset($_SESSION['utilisateur']) || $_SESSION['type'] !== 'admin') {
    header("Location: login.php");
    exit();
}

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    try {
        $requete = $bdd->prepare("DELETE FROM contacts WHERE id = ?");
        $requete->execute([$id]);

        // Redirigez vers la page moncompte.php avec un message de succès
        $_SESSION['message'] = "Contact supprimé avec succès.";
        header("Location: moncompte.php");
        exit();
    } catch (Exception $e) {
        die('Erreur lors de la suppression du contact : ' . $e->getMessage());
    }
} else {
    // Redirection si l'ID n'est pas fourni
    header("Location: moncompte.php");
    exit();
}
?>