<?php
// Connexion à la base de données
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

try {
    $bdd = new PDO("mysql:host=$server;dbname=$db", $username, $password);
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>