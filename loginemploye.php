<?php
session_start(); // Démarrer la session pour accéder aux variables de session


// Vérifier si l'utilisateur est déjà connecté, le rediriger vers moncompte.php s'il l'est
if (isset($_SESSION['utilisateur'])) {
    header("Location: moncompteemploye.php");
    exit();
}

// Vérifier si des données de formulaire ont été soumises
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier les identifiants de connexion dans la base de données
    // Vous devez utiliser la connexion à votre base de données et vérifier si l'utilisateur existe avec les identifiants fournis
    // Si l'utilisateur est trouvé, définissez les informations de l'utilisateur dans la session, y compris le type d'utilisateur
    // Exemple de vérification (à remplacer par votre propre logique de vérification) :
    $email = $_POST['email'];
    $mot_de_passe = $_POST['mot_de_passe'];
    if ($email === 'irchadi3@hotmail.fr' && $mot_de_passe === 'mot_de_passe_employe') {
        // L'utilisateur est authentifié avec succès
        $_SESSION['utilisateur'] = $email;
        $_SESSION['type'] = 'employe'; // Définir le type d'utilisateur comme "admin"
        // Rediriger l'utilisateur vers la page moncompte.php après la connexion réussie
        header("Location: moncompteemploye.php");
        exit();
    } else {
        // Afficher un message d'erreur si les identifiants sont incorrects
        $erreur = "Email ou mot de passe incorrect.";
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
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">Connexion</div>
                    <div class="card-body">
                        <?php if (isset($erreur)) : ?>
                            <div class="alert alert-danger" role="alert">
                                <?= $erreur ?>
                            </div>
                        <?php endif; ?>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <div class="form-group">
                                <label for="email">Email :</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="form-group">
                                <label for="mot_de_passe">Mot de passe :</label>
                                <input type="password" class="form-control" id="mot_de_passe" name="mot_de_passe" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Se connecter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
