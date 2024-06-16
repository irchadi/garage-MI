<?php
// Connexion à la base de données
try {
    $bdd = new PDO('mysql:host=127.0.0.1;dbname=garage_v_parrot', 'root', '');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    // Log the error message and display a generic error message to the user
    error_log($e->getMessage());
    die('Erreur : Impossible de se connecter à la base de données.');
}
?>

<?php
// Définir les informations de connexion à la base de données
//$host = 'p1us8ottbqwio8hv.cbetxkdyhwsb.us-east-1.rds.amazonaws.com';
//$database = 'rb15q9s3qg6u44eb';
//$username = 'y85ea6wiysyp48c7';
//$password = 'mgvuh32rrgjmev3f';
//$port = '3306';

//try {
    // Connexion à la base de données
    //$bdd = new PDO("mysql:host=$host;dbname=$database;port=$port", $username, $password);
    // Définir le mode d'erreur PDO sur Exception
    //$bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connexion à la base de données réussie !";
//} catch (PDOException $e) {
    // En cas d'erreur, afficher le message d'erreur
    //echo "Erreur de connexion à la base de données : " . $e->getMessage();
//}
//?>