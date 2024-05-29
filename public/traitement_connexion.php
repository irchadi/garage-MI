<?php
session_start();
require_once __DIR__ . '/../config/connectdb.php'; // Assurez-vous que le chemin est correct

if (isset($_SESSION['utilisateur'])) {
    header("Location: moncompte.php");
    exit;
}

$erreur = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && !empty($_POST['email']) && !empty($_POST['mot_de_passe'])) {
    $email = trim($_POST['email']);
    $mot_de_passe = trim($_POST['mot_de_passe']);

    try {
        $stmt = $bdd->prepare("SELECT id, email, mot_de_passe, type FROM utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        if ($stmt->rowCount() == 1) {
            $utilisateur = $stmt->fetch(PDO::FETCH_ASSOC);
            if (password_verify($mot_de_passe, $utilisateur['mot_de_passe'])) {
                $_SESSION['utilisateur'] = $utilisateur['email'];
                $_SESSION['type'] = $utilisateur['type'];
                header("Location: moncompte.php");
                exit;
            } else {
                $erreur = "Email ou mot de passe incorrect.";
            }
        } else {
            $erreur = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $erreur = "Erreur de connexion à la base de données: " . $e->getMessage();
    }
}

if (!empty($erreur)) {
    header("Location: login.php?erreur=" . urlencode($erreur));
    exit;
}
?>

