<?php
session_start(); // Démarrer la session pour accéder aux variables de session

// Vérifier les identifiants de connexion dans la base de données
// Vous devez utiliser la connexion à votre base de données et vérifier si l'utilisateur existe avec les identifiants fournis
// Si l'utilisateur est trouvé, définissez les informations de l'utilisateur dans la session, y compris le type d'utilisateur
// Exemple de vérification (à remplacer par votre propre logique de vérification) :
$email = $_POST['email'];
$mot_de_passe = $_POST['mot_de_passe'];
if ($email === 'vincent.parrot@outlook.fr' && $mot_de_passe === 'mot_de_passe_employe') {
    // L'utilisateur est authentifié avec succès
    $_SESSION['utilisateur'] = $email;
    $_SESSION['type'] = 'admin'; // Définir le type d'utilisateur comme "admin"
    // Rediriger l'utilisateur vers la page moncompte.php après la connexion réussie
    header("Location: moncompte.php");
    exit();
} else {
    // Afficher un message d'erreur si les identifiants sont incorrects
    $erreur = "Email ou mot de passe incorrect.";
    header("Location: login.php?erreur=$erreur"); // Rediriger avec le message d'erreur
    exit();
}
?>