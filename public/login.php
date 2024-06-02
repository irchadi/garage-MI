<?php
session_start();
require_once __DIR__ . '/../config/connectdb.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = htmlspecialchars($_POST['username']);
    $password = htmlspecialchars($_POST['password']);

    // Vérifier les informations de l'utilisateur dans la base de données
    $sql = "SELECT * FROM users WHERE username = :username";
    $stmt = $bdd->prepare($sql);
    $stmt->execute([':username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Affichage des informations pour le débogage
        echo "Username: " . htmlspecialchars($user['username']) . "<br>";
        echo "Password (hashed): " . htmlspecialchars($user['password']) . "<br>";
        echo "Password (entered): " . htmlspecialchars($password) . "<br>";

        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            echo "Password verification successful.<br>";
            // Stocker les informations de l'utilisateur dans la session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_type'] = $user['user_type'];

            // Rediriger vers la page appropriée en fonction du type d'utilisateur
            if ($user['user_type'] == 'admin') {
                header('Location: moncompte.php');
            } elseif ($user['user_type'] == 'employe') {
                header('Location: moncompteemploye.php');
            }
            exit;
        } else {
            echo "Password verification failed.<br>";
            $message = "Nom d'utilisateur ou mot de passe incorrect.";
        }
    } else {
        echo "User not found.<br>";
        $message = "Nom d'utilisateur ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="assets/style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#contact-form input').on('input', function() {
                var isValid = true;
                $('#contact-form input').each(function() {
                    if ($(this).val() === '') {
                        isValid = false;
                        return false; // Sortir de la boucle si un champ est vide
                    }
                });
                if (isValid) {
                    $('#submit-btn').prop('disabled', false);
                } else {
                    $('#submit-btn').prop('disabled', true);
                }
            });
        });
    </script>
</head>
<body>
<header>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <a class="navbar-brand" href="index.php">GVP</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">Nos services</a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <?php foreach ($services as $service): ?>
                            <a class="dropdown-item" href="service-detail.php?id=<?php echo $service['id']; ?>">
                            <?php echo htmlspecialchars($service['nom']); ?>
                        </a>
                        <?php endforeach; ?>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Vehicules en vente
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="vehicules.php">Vehicules d'occasion</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="nous.php">Qui sommes-nous ?</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="moncompteemploye.php">Employé</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="moncompte.php">Administrateur</a>
                </li>
            </ul>
        </div>
    </nav>
</header>
    <div class="container">
        <h2>Connexion</h2>
        <?php if ($message) { echo "<p>$message</p>"; } ?>
        <form action="login.php" method="post">
            <div class="form-group">
                <label for="username">Nom d'utilisateur:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mot de passe:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Connexion</button>
        </form>
    </div>
</body>
</html>


