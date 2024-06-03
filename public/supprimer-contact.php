<?php
session_start();

require_once __DIR__ . '/../config/connectdb.php';

// Vérifier si l'utilisateur est connecté et s'il a le droit de supprimer des contacts
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Vérifier si l'ID du contact à supprimer est passé en paramètre
if (isset($_GET['id'])) {
    $contactId = intval($_GET['id']);

    // Préparer la requête de suppression
    $sql = "DELETE FROM contacts WHERE id = :id";
    $stmt = $bdd->prepare($sql);

    // Exécuter la requête de suppression
    if ($stmt->execute([':id' => $contactId])) {
        $_SESSION['message'] = "Contact supprimé avec succès.";
    } else {
        $_SESSION['message'] = "Erreur lors de la suppression du contact.";
    }
} else {
    $_SESSION['message'] = "ID de contact non fourni.";
}

// Rediriger vers la page moncompte.php avec un message
header('Location: moncompte.php');
exit();
?>
